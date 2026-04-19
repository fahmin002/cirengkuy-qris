<?php

namespace App\Livewire\Admin\Payments;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class PaymentIndex extends Component
{

    use WithPagination;

    protected $paginationTheme = "tailwind";

    #[Url(history: true)]
    public $status = '';

    #[Url(history: true)]
    public $search = '';

    public $lastPaymentId = null;
    public function mount()
    {
        $this->lastPaymentId = Payment::latest()->value('id');
    }

    public function setStatus($status)
    {
        $this->status = $status;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Action: mark as paid
    public function markAsPaid($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'success',
            'settlement_time' => now()
        ]);

        // sync ke modul order
        $payment->order->update([
            'payment_status' => 'paid',
            'order_status' => 'processing'
        ]);
    }

    // Action: Refund
    public function refund($id)
    {
        $payment = Payment::findOrFail($id);

        $payment->update([
            'status' => 'refunded',
            'refund_time' => now()
        ]);

        $payment->order->update([
            'order_status' => 'cancelled'
        ]);
    }

    public function getPaymentsProperty()
    {
        return Payment::with(['order.user'])
            ->when($this->status, fn($q) => $q->where('status', $this->status))
            ->when(
                $this->search,
                fn($q) =>
                $q->whereAs(
                    'order',
                    fn($q2) =>
                    $q2->where('order_number', 'like', '%', $this->search . '%')
                )
            )
            ->latest()
            ->paginate(10);
    }

    public function checkNewPayment()
    {
        $latest = Payment::latest()->value('id');

        if ($latest > $this->lastPaymentId) {
            $this->dispatch('new-payment');
            $this->lastPaymentId = $latest;
        }
    }
    public function getStatsProperty()
    {
        return [
            'total' => Payment::where('status', 'success')->sum('amount'),
            'today' => Payment::whereDate('created_at', today())
                ->where('status', 'success')
                ->sum('amount'),
            'pending' => Payment::where('status', 'pending')->count(),
        ];
    }
    public function render()
    {
        return view('livewire.admin.payments.payment-index', [
            'payments' => $this->payments,
            'stats' => $this->stats
        ])->layout('components.layouts.app');
    }
}
