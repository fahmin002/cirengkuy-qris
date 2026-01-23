<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class OrderIndex extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $status = ''; // Nyimpen status filter (?status=paid)

    #[Url(history: true)]
    public $search = ''; // Nyimpen keyword pencarian

    // Fungsi buat ganti status lewat tombol tap
    public function setStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    // Reset pagination pas ngetik di search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $orders = Order::query()
            ->with(['user', 'orderItems.product', 'payment'])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when($this->search, fn($q) => $q->where('order_number', 'like', '%'.$this->search.'%'))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.orders.order-index', [
            'orders' => $orders
        ])->layout('components.layouts.app'); // Sesuaikan dengan nama layout utamamu
    }
}