<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ProductCategory;
use App\Models\Product as P; // fallback alias not needed if using Product directly
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ReportTransaction;
use App\Models\StockLog;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // user
        $user = User::create([
            'name' => 'Admin JP',
            'email' => 'admin@jp.com',
            'password' => Hash::make('12345'),
        ]);

        // categories
        $cat1 = ProductCategory::create(['pdc_name' => 'Baju', 'user_id' => $user->id]);
        $cat2 = ProductCategory::create(['pdc_name' => 'Celana', 'user_id' => $user->id]);
        $cat3 = ProductCategory::create(['pdc_name' => 'Aksesoris', 'user_id' => $user->id]);

        // products
        $item1 = Product::create([
            'prd_name' => 'Kaos Hitam',
            'prd_price' => 50000,
            'prd_stock' => 20,
            'prd_color' => 'Hitam',
            'prd_size' => 'L',
            'pdc_id' => $cat1->id,
        ]);

        $item2 = Product::create([
            'prd_name' => 'Kaos Putih',
            'prd_price' => 45000,
            'prd_stock' => 30,
            'prd_color' => 'Putih',
            'prd_size' => 'M',
            'pdc_id' => $cat1->id,
        ]);

        $item3 = Product::create([
            'prd_name' => 'Celana Jeans',
            'prd_price' => 120000,
            'prd_stock' => 15,
            'prd_color' => 'Biru',
            'prd_size' => '32',
            'pdc_id' => $cat2->id,
        ]);

        // transaction header
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'trs_subtotal' => 170000,
            'trs_total' => 170000,
            'payment_method' => 'CASH',
        ]);

        // details
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $item1->id,
            'qty' => 2,
            'price_at_sale' => $item1->prd_price,
            'subtotal' => 100000,
        ]);

        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'product_id' => $item3->id,
            'qty' => 1,
            'price_at_sale' => $item3->prd_price,
            'subtotal' => 120000,
        ]);

        // report transactions
        ReportTransaction::create([
            'transaction_id' => $transaction->id,
            'product_id' => $item1->id,
            'dtr_subtotal' => 100000,
            'dtr_period' => Carbon::now()->format('Y-m-d'),
        ]);

        ReportTransaction::create([
            'transaction_id' => $transaction->id,
            'product_id' => $item3->id,
            'dtr_subtotal' => 120000,
            'dtr_period' => Carbon::now()->format('Y-m-d'),
        ]);

        // stock logs (seeded before -> after)
        StockLog::create([
            'product_id' => $item1->id,
            'before' => 20,
            'after' => 18,
            'description' => 'Seeded sale',
        ]);

        StockLog::create([
            'product_id' => $item3->id,
            'before' => 15,
            'after' => 14,
            'description' => 'Seeded sale',
        ]);
    }
}
