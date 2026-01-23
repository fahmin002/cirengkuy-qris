<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Livewire\Admin\Orders\OrderIndex; // Pastikan import ini ada
use Illuminate\Support\Facades\Route;

Route::get("/dashboard", [AdminDashboardController::class, "dashboard"])->name("dashboard");

// 1. Route Livewire untuk Halaman Utama Orders (Order Center)
Route::get('orders', OrderIndex::class)->name('orders.index');

// 2. Tetap gunakan resource untuk fungsi lain (store, update, destroy)
// Kecuali 'index' dan 'show' karena sudah kita handle di Livewire
Route::resource('orders', OrderController::class)->except(['index']);

// 3. Route khusus Print
Route::get('orders/{order}/print', [OrderController::class, 'print'])->name('orders.print');

Route::resource('products', ProductController::class);
Route::resource('payments', PaymentController::class);