<?php

namespace App\Livewire\Admin\Kitchen;

use Livewire\Component;
use App\Models\Order;

class KitchenIndex extends Component
{
    public $filterType = 'all'; // all | cooked | frozen

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);

        $order->update([
            'order_status' => $status
        ]);
    }

    public function getOrdersProperty()
    {
        return Order::query()
            ->whereIn('order_status', ['pending', 'processing'])
            ->when($this->filterType !== 'all', function ($q) {
                $q->where('order_type', $this->filterType);
            })
            ->orderBy('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.kitchen.kitchen-index', [
            'orders' => $this->orders
        ])->layout('components.layouts.app');
    }
}