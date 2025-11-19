<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ReportTransaction;
use App\Models\StockLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================
        // USER ADMIN
        // ============================
        $user = User::create([
            'name' => 'Admin JP',
            'email' => 'admin@jp.com',
            'password' => Hash::make('12345'),
        ]);

        // ============================
        // CATEGORY
        // ============================
        $categoryNames = [
            'Baju','Celana','Aksesoris','Sepatu','Topi','Tas','Jaket',
            'Hoodie','Kemeja','Celana Pendek'
        ];

        $categories = [];
        foreach ($categoryNames as $name) {
            $categories[$name] = ProductCategory::create(['name' => $name]);
        }

        // ============================
        // PRODUCTS
        // ============================
        $productData = [
            ['Kaos Hitam Polos', 50000, rand(50,100), 'Hitam', 'M', 'Baju'],
            ['Kaos Putih Lengan Panjang', 60000, rand(50,100), 'Putih', 'L', 'Baju'],
            ['Hoodie Abu-Abu', 120000, rand(50,100), 'Abu-Abu', 'L', 'Hoodie'],
            ['Jaket Jeans', 180000, rand(50,100), 'Biru', 'M', 'Jaket'],
            ['Kemeja Flanel Merah', 90000, rand(50,100), 'Merah', 'L', 'Kemeja'],
            ['Celana Jeans Slim Fit', 120000, rand(50,100), 'Biru', '32', 'Celana'],
            ['Celana Chino Coklat', 110000, rand(50,100), 'Coklat', '32', 'Celana'],
            ['Celana Pendek Hitam', 75000, rand(50,100), 'Hitam', 'M', 'Celana Pendek'],
            ['Topi Baseball', 40000, rand(50,100), 'Hitam', 'All', 'Topi'],
            ['Tas Ransel', 150000, rand(50,100), 'Biru', 'All', 'Tas'],
            ['Sepatu Sneakers Putih', 250000, rand(50,100), 'Putih', '42', 'Sepatu'],
            ['Sandal Jepit', 50000, rand(50,100), 'Coklat', '42', 'Sepatu'],
            ['Kacamata Hitam', 80000, rand(50,100), 'Hitam', 'All', 'Aksesoris'],
            ['Gelang Kulit', 60000, rand(50,100), 'Coklat', 'All', 'Aksesoris'],
            ['Kalung Stainless', 70000, rand(50,100), 'Silver', 'All', 'Aksesoris'],
            ['Kaos Polo Navy', 90000, rand(50,100), 'Biru', 'L', 'Baju'],
            ['Hoodie Hitam', 130000, rand(50,100), 'Hitam', 'M', 'Hoodie'],
            ['Jaket Bomber Hijau', 200000, rand(50,100), 'Hijau', 'L', 'Jaket'],
            ['Celana Jogger Abu', 95000, rand(50,100), 'Abu-Abu', 'M', 'Celana'],
            ['Topi Bucket', 50000, rand(50,100), 'Krem', 'All', 'Topi'],
        ];

        foreach ($productData as $data) {
            [$name, $price, $stock, $color, $size, $catName] = $data;

            Product::create([
                'prd_name' => $name,
                'prd_price' => $price,
                'prd_stock' => $stock,
                'prd_color' => $color,
                'prd_size' => $size,
                'prd_photo' => 'products/default.jpg',
                'pdc_id' => $categories[$catName]->id,
            ]);
        }

        // ============================
        // SEED DUMMY TRANSAKSI & RESTOCK
        // ============================
        $allProducts = Product::all();

        $dates = [];
        for ($d = 50; $d >= 1; $d--) {
            $dates[] = Carbon::now()->subDays($d);
        }

        foreach ($dates as $date) {

            // 50% transaksi – 50% restock
            if (rand(0,1) == 0) {

                // TRANSAKSI
                $picked = $allProducts->random(rand(1,3));
                $subtotal = 0;

                $last = Transaction::orderBy('id','desc')->first();
                $next = $last ? $last->id + 1 : 1;

                $code = "TRX".$date->format("Ymd")."-".str_pad($next,3,'0',STR_PAD_LEFT);

                $trx = Transaction::create([
                    'user_id' => $user->id,
                    'trs_code' => $code,
                    'trs_subtotal' => 0,
                    'trs_discount' => 0,
                    'trs_total' => 0,
                    'payment_method' => ['CASH','TRANSFER','QRIS'][rand(0,2)],
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);

                foreach ($picked as $p) {
                    if ($p->prd_stock <= 0) continue;

                    $qty = rand(1, min(3, $p->prd_stock));
                    $sub = $qty * $p->prd_price;
                    $subtotal += $sub;

                    TransactionDetail::create([
                        'transaction_id' => $trx->id,
                        'product_id' => $p->id,
                        'product_name' => $p->prd_name,
                        'qty' => $qty,
                        'price_at_sale' => $p->prd_price,
                        'subtotal' => $sub,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    // REPORT MASUK
                    ReportTransaction::create([
                        'transaction_id' => $trx->id,
                        'product_id' => $p->id,
                        'product_name' => $p->prd_name,
                        'dtr_subtotal' => $sub,
                        'dtr_period' => $date->format('Y-m-d'),
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    // LOG STOK
                    $before = $p->prd_stock;
                    $after = $before - $qty;

                    StockLog::create([
                        'product_id' => $p->id,
                        'before' => $before,
                        'after' => $after,
                        'description' => "Barang keluar $qty pcs",
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $p->update(['prd_stock' => $after]);
                }

                // DISKON
                $discount = rand(0,1) ? rand(5000,20000) : 0;
                if ($discount > $subtotal) $discount = $subtotal;

                $totalAfter = $subtotal - $discount;

                $cash = $totalAfter + rand(0,15000);
                $change = $cash - $totalAfter;

                // UPDATE TRX
                $trx->update([
                    'trs_subtotal' => $subtotal,
                    'trs_discount' => $discount,
                    'trs_total' => $totalAfter,
                    'cash' => $cash,
                    'change' => $change,
                ]);

            } else {

                // RESTOCK
                $picked = $allProducts->random(rand(1,3));

                foreach ($picked as $p) {
                    $qty = rand(5,20);
                    $before = $p->prd_stock;
                    $after = $before + $qty;

                    StockLog::create([
                        'product_id' => $p->id,
                        'before' => $before,
                        'after' => $after,
                        'description' => "Penambahan stok $qty pcs",
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);

                    $p->update(['prd_stock' => $after]);
                }
            }
        }
    }
}
