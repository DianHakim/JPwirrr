<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Total produk terjual
        $totalProdukTerjual = Transaction::sum('qty');

        // Total transaksi
        $totalTransaksi = Transaction::count();

        // Total penjualan (uang)
        $totalPenjualan = Transaction::sum('total_price');

        // Produk terlaris
        $produkTerlaris = Transaction::selectRaw('product_id, SUM(qty) as total')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        // Map nama produk
        $produkTerlaris->map(function ($item) {
            $item->nama = Product::find($item->product_id)->name ?? '-';
        });

        // Penjualan per bulan (chart)
        $penjualanBulanan = Transaction::selectRaw('MONTH(created_at) as bulan, SUM(total_price) as total')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Data kategori untuk pie chart
        $kategoriData = ProductCategory::withCount('products')->get();

        return view('dashboard', compact(
            'totalProdukTerjual',
            'totalTransaksi',
            'totalPenjualan',
            'produkTerlaris',
            'penjualanBulanan',
            'kategoriData'
        ));
    }
}
