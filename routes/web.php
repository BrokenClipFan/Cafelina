<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/orders', function () {
    return view('queue');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/', [PageController::class, 'index'])->name('/');
    
    Route::post('/category/save', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/edit/order', [PageController::class, 'editOrder']);
    Route::post('/categories/reorder', [CategoryController::class, 'categoryReorder']);
    Route::post('/item/save', [ItemController::class, 'store'])->name('item.store');
    Route::post('/items/reorder', [ItemController::class, 'itemReorder']);
    Route::put('/items/{id}/update', [ItemController::class, 'update']);
    Route::delete('/items/{id}/delete', [ItemController::class, 'destroy']);
    Route::delete('/category/{id}/delete', [CategoryController::class, 'destroy']);
});



require __DIR__.'/auth.php';







// });
// Route::get('/dashboard', function () {
//     return view('dashboard');
// });

