<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class MidtransWebhookController extends Controller
{
    // public function __construct()
    // {
    //     // Set your Midtrans server key
    //     \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
    //     \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
    //     \Midtrans\Config::$isSanitized = true;
    //     \Midtrans\Config::$is3ds = true;
    // }
    public function handle(Request $request)
    {
        // Ambil data dari midtrans
        $orderId = $request->order_id;
        $status = $request->transaction_status;

        // Cari order di database lewat Eloquent ORM
        $order = Order::where('order_number', $orderId)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        /* 
        | Midtrans   | Artinya      | Kita    |
        | ---------- | ------------ | ------- |
        | settlement | sukses       | success |
        | capture    | sukses       | success |
        | pending    | nunggu bayar | pending |
        | expire     | kadaluarsa   | expired |
        | cancel     | batal        | failed  |
        */

        $payment = $order->payment;
        if ($status === 'settlement' || $status === 'capture') {
            $payment->status = 'success';
            $order->order_status = 'processing';
        }

        if ($status === 'pending') {
            $payment->status = 'pending';
            $order->order_status = 'pending';
        }

        if ($status === 'expire') {
            $payment->status = 'expired';
            $order->order_status = 'cancelled';
        }

        if ($status === 'cancel') {
            $payment->status = 'failed';
            $order->order_status = 'cancelled';
        }

        $payment->save();

        return response()->json(['message' => 'Webhook received']);
    }
}
