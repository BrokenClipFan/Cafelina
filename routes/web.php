<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QueueListController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders', function () {
    return view('queue');
});

Route::get('/get/queue', [QueueListController::class, 'getOrders']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/', [PageController::class, 'index'])->name('home');
    
    Route::post('/cart/checkout', [PurchaseController::class, 'purchase']);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/edit/order', [PageController::class, 'editOrder'])->name('orders.edit_mode');
    Route::post('/category/save', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/categories/reorder', [CategoryController::class, 'categoryReorder']);
    Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy']);
    Route::post('/item/save', [ItemController::class, 'store'])->name('item.store');
    Route::post('/items/reorder', [ItemController::class, 'itemReorder']);
    Route::put('/items/{id}/update', [ItemController::class, 'update']);
    Route::delete('/items/{id}/delete', [ItemController::class, 'destroy']);

    Route::get('/admin/dashboard', function () {
        return view('admin.adminDashboard');
    });
});



require __DIR__.'/auth.php';







// });
// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

