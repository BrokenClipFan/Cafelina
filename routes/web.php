<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return view('order');
});

Route::get('/orders', function () {
    return view('queue');
});
Route::get('/dashboard', function () {
    return view('dashboard');
});

Route::post('/category/save', [CategoryController::class, 'store'])->name('category.store');
Route::post('/item/save', [ItemController::class, 'store'])->name('item.store');

Route::get('/edit/order', [PageController::class, 'editOrder']);