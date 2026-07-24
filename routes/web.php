<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\QueueListController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\EmployeeManagementController;

Route::get('/orders', function () {
    return view('queue');
})->name('queue.display');

Route::get('/get/queue', [QueueListController::class, 'getOrders']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::post('/cart/checkout', [PurchaseController::class, 'purchase']);
    Route::put('/api/orders/{orderName}/ready', [QueueListController::class, 'update']);
    Route::delete('/api/orders/{orderName}/remove', [QueueListController::class, 'destroy']);
    
    Route::get('/dashboard', [SalesController::class, 'index'])->name('dashboard');
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

    Route::get('/admin/dashboard', [SaleController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/items/basta', [SaleController::class, 'getPopularItemsData'])->name('admin.popular_items_data');
    
    Route::get('/admin/employees', [EmployeeManagementController::class, 'index'])->name('admin.employees');
    Route::post('/admin/employee/toggle/{id}', [EmployeeManagementController::class, 'toggleStatus'])->name('admin.employees.toggle_status');
    Route::delete('/admin/employee/destroy/{id}', [EmployeeManagementController::class, 'destroy'])->name('admin.employees.destroy');
    
    Route::post('/admin/employee/assign', [EmployeeManagementController::class, 'assignShift'])->name('admin.employees.assign_shift');
    Route::delete('/admin/employee/schedule/destroy/{id}', [EmployeeManagementController::class, 'destroySchedule'])->name('admin.employees.destroy_schedule');
    
    Route::post('/admin/receipt/', [PurchaseController::class, 'search'])->name('receipt.search');
    Route::post('/admin/receipt/update', [PurchaseController::class, 'updateStatus'])->name('orders.update_status');
});

require __DIR__.'/auth.php';

