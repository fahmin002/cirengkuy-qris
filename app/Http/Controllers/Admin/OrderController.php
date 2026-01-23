<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     $orders = Order::with('user')
    //         ->with('payment')
    //         ->with('orderItems.product')
    //         ->orderBy("created_at", "desc")
    //         ->paginate(10);
    //     return view("livewire.admin.orders.index", compact("orders"));
    // }
    
    public function print(Order $order)
    {
        // Eager load agar data item & produk ikut terbawa
        $order->load(['user', 'orderItems.product', 'payment']);
        
        return view('livewire.admin.orders.print', compact('order'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $rules = [
            'status' => 'required|in:pending,paid,processing,completed,cancelled',
            'delivery_notes' => 'nullable|string'
        ];

        if (in_array($order->status, ['pending', 'paid'])) {
            $rules += [
                'recipient_name' => 'required|string',
                'recipient_phone' => 'required|string',
                'delivery_address' => 'required|string',
            ];
        }

        $validated = $request->validate($rules);

        $order->update($validated);

        return back()->with('success', 'Order Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
