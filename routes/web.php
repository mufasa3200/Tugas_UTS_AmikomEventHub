<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\MidtransWebhookController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EventController as EventAdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\TransactionController;

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

// Rute User Area 
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
Route::get('/checkout', [EventController::class, 'checkout'])->name('checkout');
Route::get('/my-ticket', [EventController::class, 'ticket'])->name('ticket');

//rute proses transaksi
Route::get('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'create'])->name('checkout.create');
Route::post('/checkout/{event}', [App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/payment/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/success/{order_id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

Route::post('rans/c/midtallback', [\App\Http\Controllers\MidtransWebhookController::class, 'handle']); 

// Rute Admin Area 
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
    
    // Rute Login bebas akses 
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Mengamankan seluruh Route Administrasi di balik tembok Middleware
    Route::middleware(['auth', 'admin'])->group(function () {
        
        // Dashboard & Laporan
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard-alias');
        Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
        
        // Resource CRUD Admin 
        Route::resource('events', EventAdminController::class);
        Route::resource('categories', CategoryController::class)->except(['create', 'edit', 'show']);
        Route::resource('partners', PartnerController::class)->except(['create', 'edit', 'show']);
    });

    Route::get('transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
});