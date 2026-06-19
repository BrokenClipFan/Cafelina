<?php

use Illuminate\Support\Facades\Route;

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
