<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;

Route::get('/', function () {
    return view('order');
});
Route::get('/edit', function () {
    return view('editOrder');
});
Route::get('/orders', function () {
    return view('queue');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::post('/category/save', [CategoryController::class, 'store'])->name('category.store');
Route::post('/item/save', [ItemController::class, 'store'])->name('item.store');