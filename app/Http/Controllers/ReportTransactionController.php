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
        // default bulan sekarang (YYYY-MM)
        $month = $request->month ?? date('Y-m');

        // extract tahun & bulan
        [$tahun, $bulan] = explode('-', $month);

        $reports = ReportTransaction::with(['transaction', 'product'])
            ->whereYear('dtr_period', $tahun)
            ->whereMonth('dtr_period', $bulan)
            ->orderBy('dtr_period', 'desc')
            ->get();

        $totalIncome = $reports->sum('dtr_subtotal');

        return view('reports.index', compact('reports', 'month', 'totalIncome'));
    }

    // ===================== EXPORT PDF =====================
    public function exportPDF(Request $request)
    {
        $month = $request->month ?? date('Y-m');

        [$tahun, $bulan] = explode('-', $month);

        $reports = ReportTransaction::with(['transaction', 'product'])
            ->whereYear('dtr_period', $tahun)
            ->whereMonth('dtr_period', $bulan)
            ->orderBy('dtr_period', 'desc')
            ->get();

        $totalIncome = $reports->sum('dtr_subtotal');

        $pdf = Pdf::loadView('reports.pdf', compact('reports', 'month', 'totalIncome'));

        return $pdf->download('Laporan-'.$month.'.pdf');
    }

    // ===================== SIMPAN SAAT TRANSAKSI =====================
    public static function storeFromTransaction(Transaction $trx)
    {
        foreach ($trx->details as $detail) {
            ReportTransaction::create([
                'transaction_id' => $trx->id,
                'product_id'     => $detail->product_id,
                'dtr_subtotal'   => $detail->subtotal,
                'dtr_period'     => now()->toDateString(),
            ]);
        }
    }

    // ===================== DETAIL LAPORAN PER TRANSAKSI =====================
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
