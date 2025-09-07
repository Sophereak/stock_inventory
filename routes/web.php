<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes: only login/logout, no register, no reset, no verify
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

// Redirect /home to dashboard
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Inventory routes
    Route::resource('inventory', InventoryController::class);

    // ✅ Inventory export route
    Route::get('inventory/export', [InventoryController::class, 'export'])->name('inventory.export');

    // Sales routes
    Route::resource('sales', SaleController::class);

    // Quick sales API
    Route::post('/sales/quick', [SaleController::class, 'quickSale'])->name('sales.quick');

    // Daily sales report
    Route::get('sales/report/daily', [SaleController::class, 'dailyReport'])->name('sales.report.daily');
    Route::get('/test-telegram', function () {
        $telegram = new \App\Helpers\Telegram();
        return $telegram->sendMessage("✅ Test message from ChoronaiStore Laravel app!");
    });

    // Send daily report to Telegram
Route::get('sales/report/daily/send', [SaleController::class, 'sendDailyReportTelegram'])->name('sales.report.daily.send');

});
