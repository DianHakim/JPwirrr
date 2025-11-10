<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductCategoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::resource('kategori', ProductCategoryController::class)->names([
    'index' => 'productcategory.index',
    'create' => 'productcategory.create',
    'store' => 'productcategory.store',
    'edit' => 'productcategory.edit',
    'update' => 'productcategory.update',
    'destroy' => 'productcategory.destroy',
]);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
