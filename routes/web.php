<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('kategori')->name('productcategory.')->middleware('auth')->group(function () {
    Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
    Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
    Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductCategoryController::class, 'destroy'])->name('destroy');
});

Route::prefix('produk')->name('products.')->middleware('auth')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/create', [ProductController::class, 'create'])->name('create');
    Route::post('/', [ProductController::class, 'store'])->name('store');
    Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
    Route::put('/{product}', [ProductController::class, 'update'])->name('update');
    Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
    Route::get('/tambah-stok', [ProductController::class, 'addStockPage'])->name('addstock');
    Route::post('/tambah-stok', [ProductController::class, 'addStock'])->name('addstock.store');
    Route::get('/stock-history', [ProductController::class, 'stockHistory'])->name('stockhistory');
});

Route::prefix('transaksi')->name('transactions.')->middleware('auth')->group(function () {
    Route::get('/', [TransactionController::class, 'index'])->name('index');
    Route::get('/create', [TransactionController::class, 'create'])->name('create');
    Route::post('/', [TransactionController::class, 'store'])->name('store');
    Route::get('/transactions/{id}/print', [TransactionController::class, 'print'])->name('print');
    Route::get('/transactions/{id}/print-pdf', [TransactionController::class, 'printPDF'])->name('print-pdf');
    Route::get('/{id}', [TransactionController::class, 'show'])->name('show');
    Route::delete('/{id}', [TransactionController::class, 'destroy'])->name('destroy');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
