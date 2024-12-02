<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProductController::class, 'index']);
Route::post('/add-product', [ProductController::class, 'store'])->name('add-product');
Route::get('/fetch-products', [ProductController::class, 'fetch'])->name('fetch-products');
Route::get('/get-product-by-id/{id}', [ProductController::class, 'delete'])->name('get-product-by-id');
