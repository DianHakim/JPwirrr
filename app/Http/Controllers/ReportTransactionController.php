<?php

namespace App\Http\Controllers;

use App\Models\ReportTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportTransactionController extends Controller
{
    // ===================== INDEX =====================
    public function index(Request $request)
    {
        $month = $request->month;   // angka bulan (1–12)
        $year  = $request->year;    // angka tahun

        $query = ReportTransaction::with(['transaction', 'product']);

        // ============================
        // DEFAULT: jika tidak pilih apapun → tampil hari ini
        // ============================
        if (empty($month) && empty($year)) {
            $query->whereDate('dtr_period', today());
        } else {

            // --- Jika user pilih bulan tapi tidak pilih tahun → gunakan tahun ini
            if ($month) {
                $query->whereMonth('dtr_period', $month);

                if (!$year) {
                    $year = date('Y'); // tahun sekarang
                }
            }

            // --- Jika user pilih tahun → filter tahun
            if ($year) {
                $query->whereYear('dtr_period', $year);
            }
        }

        $reports = $query->orderBy('dtr_period', 'desc')->get();
        $totalIncome = $reports->sum('dtr_subtotal');

        return view('reports.index', compact(
            'reports',
            'month',
            'year',
            'totalIncome'
        ));
    }

    // ===================== EXPORT PDF =====================
    public function exportPDF(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        $query = ReportTransaction::with(['transaction', 'product']);

        if (!$month && !$year) {
            $query->whereDate('dtr_period', today());
        } else {

            if ($year) {
                $query->whereYear('dtr_period', $year);
            }

            if ($month) {
                $query->whereMonth('dtr_period', $month);
            }
        }

        $reports = $query->orderBy('dtr_period', 'desc')->get();
        $totalIncome = $reports->sum('dtr_subtotal');

        $pdf = Pdf::loadView('reports.pdf', compact(
            'reports',
            'month',
            'year',
            'totalIncome'
        ));

        return $pdf->download('Laporan.pdf');
    }

    // ===================== SIMPAN SAAT TRANSAKSI =====================
    public static function storeFromTransaction(Transaction $trx)
    {
        foreach ($trx->details as $detail) {
            ReportTransaction::create([
                'transaction_id' => $trx->id,
                'product_id'     => $detail->product_id,
                'product_name'   => $detail->product_name,
                'dtr_subtotal'   => $detail->subtotal,
                'dtr_period'     => now()->toDateString(), // YYYY-MM-DD
            ]);
        }
    }

    // ===================== DETAIL LAPORAN =====================
    public function show($id)
    {
        $data = ReportTransaction::where('transaction_id', $id)
            ->with(['transaction', 'product'])
            ->get();

        if ($data->isEmpty()) {
            return redirect()->route('reports.index')
                ->with('error', 'Data laporan tidak ditemukan');
        }

        return view('reports.show', compact('data'));
    }
}
