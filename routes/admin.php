<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProductController;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Route;

Route::get("/dashboard", [AdminDashboardController::class, "dashboard"])->name("dashboard");
Route::resource('orders', OrderController::class);
Route::resource('orderitems', OrderItemController::class);
Route::resource('products', ProductController::class);
Route::resource('payments', PaymentController::class);
