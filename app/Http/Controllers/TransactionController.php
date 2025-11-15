<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // ============================
    // LIST TRANSAKSI
    // ============================
    public function index(Request $request)
    {
        $query = Transaction::with('user');

        if ($request->search) {
            // Bungkus dengan where agar orWhere jadi grup query yang benar
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', "%{$request->search}%")
                    ->orWhere('payment_method', 'like', "%{$request->search}%")
                    ->orWhereHas('user', function ($q2) use ($request) {
                        $q2->where('name', 'like', "%{$request->search}%");
                    });
            });
        }

        // Gunakan orderBy id agar urutan konsisten dan tidak ngacak
        $transactions = $query->orderBy('id', 'asc')->paginate(10);

        return view('transactions.index', compact('transactions'));
    }


    // ============================
    // HALAMAN TRANSAKSI (FORM)
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
            'items' => 'required|array',
            'payment_method' => 'required|string'
        ]);

        $items = $request->items;

        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'trs_subtotal' => $subtotal,
            'trs_total' => $subtotal,
            'payment_method' => $request->payment_method
        ]);

        // simpan detail + kurangi stok + catat log
        foreach ($items as $item) {

            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'qty' => $item['qty'],
                'price_at_sale' => $item['price'],
                'subtotal' => $item['qty'] * $item['price']
            ]);

            // ----- UPDATE STOK + LOG -----
            $product = Product::find($item['product_id']);

            $before = $product->prd_stock;
            $after = $before - $item['qty'];

            // update stok
            $product->update([
                'prd_stock' => $after
            ]);

            // simpan log stok
            StockLog::create([
                'product_id' => $product->id,
                'before' => $before,
                'after' => $after,
                'description' => 'Transaksi #' . $transaction->id . ' - Barang keluar ' . $item['qty']
            ]);
        }

        return redirect()->route('transactions.show', $transaction->id)
            ->with('success', 'Transaksi berhasil disimpan!');
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
    // CETAK STRUK
    // ============================
    public function print($id)
    {
        $transaction = Transaction::with(['details.product', 'user'])->findOrFail($id);

        return view('transactions.print', compact('transaction'));
    }

    // ============================
    // HAPUS TRANSAKSI
    // ============================
    public function destroy($id)
    {
        $trans = Transaction::with('details')->findOrFail($id);

        // Kembalikan stok + catat log
        foreach ($trans->details as $d) {

            $product = Product::find($d->product_id);

            $before = $product->prd_stock;
            $after = $before + $d->qty;

            // update stok
            $product->update([
                'prd_stock' => $after
            ]);

            // log stok kembali
            StockLog::create([
                'product_id' => $product->id,
                'before' => $before,
                'after' => $after,
                'description' => 'Pembatalan Transaksi #' . $trans->id . ' - Barang dikembalikan ' . $d->qty
            ]);
        }

        // hapus detail dulu
        TransactionDetail::where('transaction_id', $id)->delete();

        $trans->delete();

        return back()->with('success', 'Transaksi berhasil dihapus.');
    }
}
