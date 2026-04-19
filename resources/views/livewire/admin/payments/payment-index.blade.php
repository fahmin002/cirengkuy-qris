
<div class="flex flex-col gap-4" wire:poll.5s="checkNewPayment">
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('new-payment', () => {
                alert('💰 Payment baru masuk!');
            });
        });
    </script>
    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Payments</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Payments</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <div class="w-72">
            <flux:input wire:model.live.debounce.500ms="search"
                icon="magnifying-glass"
                placeholder="Search Order..." />
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-2 overflow-x-auto pb-2">

    @foreach ([
        [
            'status' => '',
            'label' => 'Semua',
            'color' => 'zinc'
        ],
        [
            'status' => 'pending',
            'label' => 'Menunggu',
            'color' => 'yellow'
        ],
        [
            'status' => 'success',
            'label' => 'Berhasil',
            'color' => 'emerald'
        ],
        [
            'status' => 'unpaid',
            'label' => 'Belum Bayar',
            'color' => 'red'
        ],
        [
            'status' => 'failed',
            'label' => 'Gagal',
            'color' => 'red'
        ],
        [
            'status' => 'expired',
            'label' => 'Kadaluarsa',
            'color' => 'zinc'
        ],
    ] as $filter)

        <flux:button
            wire:click="setStatus('{{ $filter['status'] }}')"
            size="sm"
            variant="{{ $status === $filter['status'] ? 'primary' : 'ghost' }}"
            color="{{ $filter['color'] }}"
            class="rounded-full font-bold whitespace-nowrap"
        >
            {{ $filter['label'] }}
        </flux:button>

    @endforeach

    </div>


    <div class="grid grid-cols-3 gap-4">

    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        <div class="text-sm text-zinc-500">Total Revenue</div>
        <div class="text-xl font-black">
            Rp{{ number_format($stats['total'], 0, ',', '.') }}
        </div>
    </div>

    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800">
        <div class="text-sm text-zinc-500">Today</div>
        <div class="text-xl font-black">
            Rp{{ number_format($stats['today'], 0, ',', '.') }}
        </div>
    </div>

    <div class="p-4 bg-white dark:bg-zinc-900 rounded-xl bborder border-zinc-200 dark:border-zinc-800">
        <div class="text-sm text-zinc-500">Pending</div>
        <div class="text-xl font-black">
            {{ $stats['pending'] }}
        </div>
    </div>

</div>
    {{-- TABLE --}}
    <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <table class="w-full text-sm">

            <thead class="text-xs text-zinc-500 bg-zinc-50 dark:bg-white/5 uppercase">
                <tr>
                    <th class="px-6 py-4">Order</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Amount</th>
                    <th class="px-6 py-4">Method</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Action</th>
                </tr>
            </thead>

            <tbody class="divide-zinc-200 dark:divide-zinc-800 divide-y">

                @foreach ($payments as $payment)

                <tr class="hover:bg-zinc-50 dark:hover:bg-white/[0.02]">

                    {{-- ORDER --}}
                    <td class="px-6 py-4 font-bold">
                        #{{ $payment->order->order_number }}
                    </td>

                    {{-- CUSTOMER --}}
                    <td class="px-6 py-4">
                        {{ $payment->order->user->name }}
                    </td>

                    {{-- AMOUNT --}}
                    <td class="px-6 py-4 font-bold">
                        Rp{{ number_format($payment->amount, 0, ',', '.') }}
                    </td>

                    {{-- METHOD --}}
                    <td class="px-6 py-4 uppercase">
                        {{ $payment->payment_method }}
                    </td>

                    {{-- STATUS --}}
                    <td class="px-6 py-4 text-center">
                    @php
                        $color = match($payment->status) {
                            'success' => 'emerald',
                            'paid' => 'emerald', 
                            'pending' => 'yellow',
                            'unpaid' => 'red',
                            'failed' => 'red',
                            'expired' => 'zinc',
                            default => 'zinc'
                        };
                    @endphp

                        <flux:badge color="{{ $color }}">
                            {{ strtoupper($payment->status) }}
                        </flux:badge>
                    </td>

                    {{-- ACTION --}}
                    <td class="px-6 py-4 text-right">

                        <div class="flex justify-end gap-2">

                            {{-- CASH APPROVE --}}
                            @if ($payment->status === 'pending')
                                <flux:button
                                    wire:click="markAsPaid({{ $payment->id }})"
                                    size="sm"
                                    variant="primary">
                                    Approve
                                </flux:button>
                            @endif

                            {{-- REFUND --}}
                            @if ($payment->status === 'success')
                                <flux:button
                                    wire:click="refund({{ $payment->id }})"
                                    size="sm"
                                    variant="danger">
                                    Refund
                                </flux:button>
                            @endif

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>
    </div>

    {{ $payments->links() }}

</div>
