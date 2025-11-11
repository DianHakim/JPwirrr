<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::prefix('kategori')->name('productcategory.')->group(function () {
    Route::get('/', [ProductCategoryController::class, 'index'])->name('index');
    Route::get('/create', [ProductCategoryController::class, 'create'])->name('create');
    Route::post('/', [ProductCategoryController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ProductCategoryController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ProductCategoryController::class, 'update'])->name('update');
    Route::delete('/{id}', [ProductCategoryController::class, 'destroy'])->name('destroy');
});

Route::prefix('produk')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');           // tampil data produk
    Route::get('/create', [ProductController::class, 'create'])->name('create');   // form tambah produk
    Route::post('/', [ProductController::class, 'store'])->name('store');          // simpan produk baru
    Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');    // form edit produk
    Route::put('/{id}', [ProductController::class, 'update'])->name('update');     // update produk
    Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');// hapus produk
});


Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
