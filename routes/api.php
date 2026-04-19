<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MidtransWebhookController;

Route::post('/midtrans/webhook', [MidtransWebhookController::class, 'handle']);