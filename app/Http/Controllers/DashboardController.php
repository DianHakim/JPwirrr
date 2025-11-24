<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\ProductCategory;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // ======================
        // CARD ATAS
        // ======================
        $totalProducts       = Product::count();  
        $totalTransactions   = Transaction::count();
        $totalRevenue        = Transaction::sum('trs_total');

        // ======================
        // GRAFIK PENJUALAN (Bulanan)
        // ======================
        $salesMonthlyRaw = Transaction::selectRaw('MONTH(created_at) as bulan, SUM(trs_total) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $salesMonthly = [];
        foreach ($salesMonthlyRaw as $row) {
            $salesMonthly[(int)$row->bulan] = (int)$row->total;
        }

        // ======================
        // PIE KATEGORI
        // ======================
        $kategoriChart = ProductCategory::withCount('products')->get();

        // ======================
        // PRODUK TERLARIS
        // ======================
        $topProducts = TransactionDetail::selectRaw('product_id, SUM(qty) as total_qty')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalTransactions',
            'totalRevenue',
            'salesMonthly',
            'kategoriChart',
            'topProducts'
        ));
    }
}
