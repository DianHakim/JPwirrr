<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // ============================
    // LIST TRANSAKSI
    // ============================
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('trs_code', 'like', "%{$request->search}%")
                  ->orWhere('payment_method', 'like', "%{$request->search}%")
                  ->orWhereHas('user', function ($q2) use ($request) {
                      $q2->where('name', 'like', "%{$request->search}%");
                  });
            });
        }

        $transactions = $query->orderBy('id', 'desc')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    // ============================
    // FORM TRANSAKSI BARU
    // ============================
    public function create()
    {
        $products = Product::orderBy('prd_name')->get();
        return view('transactions.create', compact('products'));
    }

    // ============================
    // SIMPAN TRANSAKSI
    // ============================
    public function store(Request $request)
{
    $request->validate([
        'items' => 'required|array|min:1',
        'payment_method' => 'required|string',
        'cash' => 'nullable|numeric|min:0',
        'discount_type' => 'nullable|string|in:none,percent,nominal',
        'discount_percent' => 'nullable|numeric|min:0|max:100',
        'discount_nominal' => 'nullable|numeric|min:0',
    ]);

    $items = $request->items;

    // VALIDASI STOK
    foreach ($items as $item) {
        $product = Product::find($item['product_id']);
        if (!$product) return back()->withErrors(['msg' => 'Produk tidak ditemukan.']);
        if ($product->prd_stock < $item['qty']) {
            return back()->withErrors(['msg' => "Stok tidak cukup untuk produk {$product->prd_name}."]);
        }
    }

    DB::beginTransaction();
    try {

        // HITUNG SUBTOTAL
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        // HITUNG DISKON
        $discountType = $request->discount_type ?? 'none';
        $discountPercent = $request->discount_percent ?? 0;
        $discountNominal = 0;

        if ($discountType === 'percent') {
            $discountNominal = floor($subtotal * $discountPercent / 100);
        } elseif ($discountType === 'nominal') {
            $discountNominal = $request->discount_nominal ?? 0;
        }

        if ($discountNominal > $subtotal) $discountNominal = $subtotal;

        $totalAfterDiscount = $subtotal - $discountNominal;

        // CASH & CHANGE
        $cash = $request->cash ?? 0;
        $change = $cash - $totalAfterDiscount;

        if ($change < 0 && $request->payment_method === 'cash') {
            return back()->withErrors(['msg' => 'Uang tunai tidak cukup!']);
        }

        // ================= SIMPAN TRANSAKSI =================
        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'trs_subtotal' => $subtotal,
            'trs_total' => $totalAfterDiscount,

            // PEMANGGILAN HARUS SAMA DENGAN BLADE DAN DATABASE
            'discount_type' => $discountType,
            'discount_percent' => $discountType === 'percent' ? $discountPercent : 0,
            'discount_nominal' => $discountNominal,

            'payment_method' => $request->payment_method,
            'cash' => $cash,
            'change' => $change,
        ]);

        // ================= SIMPAN DETAIL =================
        foreach ($items as $item) {
            $product = Product::lockForUpdate()->find($item['product_id']);
            $before = $product->prd_stock;
            $after = $before - $item['qty'];

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $product->id,
                'qty' => $item['qty'],
                'price_at_sale' => $item['price'],
                'subtotal' => $item['qty'] * $item['price']
            ]);

            $product->update(['prd_stock' => $after]);

            StockLog::create([
                'product_id' => $product->id,
                'before' => $before,
                'after' => $after,
                'description' => 'Transaksi #' . $transaction->trs_code . ' - Barang keluar ' . $item['qty'] . ' pcs'
            ]);
        }

        DB::commit();
        return redirect()->route('transactions.show', $transaction->id)
                         ->with('success', 'Transaksi berhasil disimpan!');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['msg' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
}

    // ============================
    // DETAIL TRANSAKSI
    // ============================
    public function show($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);
        return view('transactions.show', compact('transaction'));
    }

    // ============================
    // CETAK STRUK PDF
    // ============================
    public function printPDF($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);
        $pdf = Pdf::loadView('transactions.print-pdf', compact('transaction'))
                  ->setPaper([0, 0, 283, 600]);
        return $pdf->stream('struk_' . $transaction->trs_code . '.pdf');
    }

    // ============================
    // HAPUS TRANSAKSI
    // ============================
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $trans = Transaction::with('details')->findOrFail($id);

            foreach ($trans->details as $d) {
                $product = Product::lockForUpdate()->find($d->product_id);
                $before = $product->prd_stock;
                $after = $before + $d->qty;

                $product->update(['prd_stock' => $after]);

                StockLog::create([
                    'product_id' => $product->id,
                    'before' => $before,
                    'after' => $after,
                    'description' => 'Pembatalan Transaksi #' . $trans->trs_code . ' - Barang dikembalikan ' . $d->qty
                ]);
            }

            TransactionDetail::where('transaction_id', $id)->delete();
            $trans->delete();

            DB::commit();
            return back()->with('success', 'Transaksi berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['msg' => 'Gagal menghapus transaksi: ' . $e->getMessage()]);
        }
    }
}
