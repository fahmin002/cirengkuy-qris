<?php

namespace App\Livewire\Admin\Orders;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class OrderIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = "tailwind";

    #[Url(history: true)]
    public $order_status = ''; // Nyimpen status filter (?status=paid)

    #[Url(history: true)]
    public $search = ''; // Nyimpen keyword pencarian

    #[Url(history: true)]
    public $perPage = 10;
    // Fungsi buat ganti status lewat tombol tap
    public function setStatus($order_status)
    {
        $this->order_status = $order_status;
        $this->resetPage();
    }

    // Reset pagination pas ngetik di search
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatePerPage()
    {
        $this->resetPage();
    }

    public function getOrdersProperty()
    {
        return Order::query()
            ->with([
                'user:id, name',
                'orderItems.product:id, name',
                'payment:id, order_id, status',
            ])
            ->when(
                $this->order_status,
                fn($q) =>
                $q->where('order_status', $this->order_status)
            )
            ->when(
                $this->search,
                fn($q) =>
                $q->where('order_number', 'like', '%' . $this->search . '%')
                ->orWhere('recipient_name', 'like', '%' . $this->search . '%')
                ->orWhere('delivery_address', 'like', '%' . $this->search . '%')
                ->orWhere('delivery_notes', 'like', '%' . $this->search . '%')
            )
            ->latest()
            ->paginate($this->perPage);
    }

    public function getStatsProperty()
    {
        return [
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'completed' => Order::where('order_status', 'completed')->count(),
        ];
    }

    public function updateStatus($orderId, $status)
    {
        Order::where('id', $orderId)->update([
            'order_status' => $status
        ]);
    }

    public function render()
    {
        return view('livewire.admin.orders.order-index', [
            'orders' => $this->orders,
            'stats' => $this->stats,
        ])->layout('components.layouts.app');
    }
}