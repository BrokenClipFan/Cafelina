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
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\UserVerificationController;

Route::get('/orders', function () {
    return view('queue');
})->name('queue.display');

Route::get('/get/queue', [QueueListController::class, 'getOrders']);

Route::middleware('auth')->group(function () {
    // Pending / rejected accounts land here (no 'approved' middleware on purpose).
    Route::get('/account/pending', function () {
        $user = auth()->user();
        if ($user->isApproved()) {
            return redirect()->route($user->homeRoute());
        }
        return view('auth.pending', compact('user'));
    })->name('account.pending');
});

Route::middleware(['auth', 'approved'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Cashier (and admin): POS, kitchen queue, personal dashboard
Route::middleware(['auth', 'approved', 'role:cashier,admin'])->group(function () {
    Route::get('/', [PageController::class, 'index'])->name('home');
    Route::post('/cart/checkout', [PurchaseController::class, 'purchase']);
    Route::put('/api/orders/{orderName}/ready', [QueueListController::class, 'update']);
    Route::delete('/api/orders/{orderName}/remove', [QueueListController::class, 'destroy']);
    
    Route::get('/dashboard', [SalesController::class, 'index'])->name('dashboard');
});

// Stock Clerk (and admin): inventory management
Route::middleware(['auth', 'approved', 'role:stock_clerk,admin'])->group(function () {
    Route::get('/admin/inventory', [InventoryController::class, 'index'])->name('admin.inventory');
    Route::post('/admin/inventory', [InventoryController::class, 'store'])->name('admin.inventory.store');
    Route::put('/admin/inventory/{id}', [InventoryController::class, 'update'])->name('admin.inventory.update');
    Route::delete('/admin/inventory/{id}', [InventoryController::class, 'destroy'])->name('admin.inventory.destroy');
    Route::post('/admin/inventory/{id}/restock', [InventoryController::class, 'restock'])->name('admin.inventory.restock');
    Route::get('/admin/inventory/{id}/history', [InventoryController::class, 'history'])->name('admin.inventory.history');
});

// Admin only
Route::middleware(['auth', 'approved', 'admin'])->group(function () {
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
    Route::get('/admin/dashboard/chart-data', [SaleController::class, 'getChartData'])->name('admin.chart_data');
    Route::get('/admin/dashboard/recent-orders', [SaleController::class, 'getRecentOrdersData'])->name('admin.recent_orders_data');
    
    Route::get('/admin/employees', [EmployeeManagementController::class, 'index'])->name('admin.employees');
    Route::get('/admin/employees/{employee}', [EmployeeManagementController::class, 'show'])->name('admin.employee');
    Route::post('/admin/employee/toggle/{id}', [EmployeeManagementController::class, 'toggleStatus'])->name('admin.employees.toggle_status');
    Route::delete('/admin/employee/destroy/{id}', [EmployeeManagementController::class, 'destroy'])->name('admin.employees.destroy');
    
    Route::post('/admin/employee/assign', [EmployeeManagementController::class, 'assignShift'])->name('admin.employees.assign_shift');
    Route::delete('/admin/employee/schedule/destroy/{id}', [EmployeeManagementController::class, 'destroySchedule'])->name('admin.employees.destroy_schedule');
    
    Route::post('/admin/receipt/', [PurchaseController::class, 'search'])->name('receipt.search');
    Route::get('/admin/receipt/autocomplete', [PurchaseController::class, 'autocomplete'])->name('receipt.autocomplete');
    Route::post('/admin/receipt/update', [PurchaseController::class, 'updateStatus'])->name('orders.update_status');

    Route::get('/admin/settings', [SettingController::class, 'index'])->name('admin.settings');
    Route::post('/admin/setting/update', [SettingController::class, 'update'])->name('settings.update');

    // Account verification & roles
    Route::get('/admin/users', [UserVerificationController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{user}/approve', [UserVerificationController::class, 'approve'])->name('admin.users.approve');
    Route::post('/admin/users/{user}/reject', [UserVerificationController::class, 'reject'])->name('admin.users.reject');
    Route::put('/admin/users/{user}/role', [UserVerificationController::class, 'updateRole'])->name('admin.users.role');
    Route::delete('/admin/users/{user}', [UserVerificationController::class, 'destroy'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';

