
<div class="flex flex-col gap-4">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Orders</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Orders</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        <div class="flex items-center gap-2">
            <flux:input 
                wire:model.live.debounce.500ms="search"
                size="sm"
                icon="magnifying-glass"
                placeholder="Search order..."
            />

            <select wire:model="perPage" class="text-sm rounded-lg border-zinc-300 dark:bg-zinc-800">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>
    </div>

    {{-- STATS --}}
    <div class="flex gap-3 flex-wrap">
        <div class="px-4 py-2 bg-zinc-100 dark:bg-white/5 rounded-lg text-sm font-bold">
            {{ $stats['pending'] }} Pending
        </div>
        <div class="px-4 py-2 bg-zinc-100 dark:bg-white/5 rounded-lg text-sm font-bold">
            {{ $stats['processing'] }} Diproses
        </div>
        <div class="px-4 py-2 bg-zinc-100 dark:bg-white/5 rounded-lg text-sm font-bold">
            {{ $stats['completed'] }} Selesai
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex gap-2 flex-wrap">
        @foreach ([
            '' => [
                'status' => '',
                'label' => 'All',
                'color' => ''
            ],
            'pending' => [
                'status' => 'pending',
                'label' => 'Pending',
                'color' => 'blue'
            ],
            'processing' => [
                'status' => 'processing',
                'label' => 'Processing',
                'color' => 'yellow'
            ],
            'completed' => [
                'status' => 'completed',
                'label' => 'Completed',
                'color' => 'green'
            ],
            'cancelled' => [
                'status' => 'cancelled',
                'label' => 'Cancelled',
                'color' => 'red'
            ],
        ] as $key)

            <flux:button
                wire:click="setStatus('{{ $key['status'] }}')"
                size="sm"
                variant="{{ $order_status === $key['status'] ? 'primary' : 'ghost' }}"
                color="{{ $key['color'] }}"
                class="rounded-full font-bold"
            >
                {{ $key['label'] }}
            </flux:button>

        @endforeach
    </div>

    {{-- TABLE --}}
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm overflow-hidden">

        <table class="w-full text-sm">

            <thead class="bg-zinc-50 dark:bg-white/5 text-zinc-500 text-[11px] uppercase">
                <tr>
                    <th class="px-6 py-4 text-left">Order</th>
                    <th class="px-6 py-4 text-left">Customer</th>
                    <th class="px-6 py-4 text-left">Payment</th>
                    <th class="px-6 py-4 text-right">Total</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right"></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">

                @forelse ($orders as $order)

                    <tr class="hover:bg-zinc-50/50 dark:hover:bg-white/[0.02]">

                        {{-- ORDER --}}
                        <td class="px-6 py-5">
                            <div class="font-bold text-zinc-900 dark:text-white">
                                #{{ $order->order_number }}
                            </div>
                            <div class="text-xs text-zinc-500">
                                {{ $order->created_at->format('H:i') }} • {{ $order->created_at->diffForHumans() }}
                            </div>
                        </td>

                        {{-- CUSTOMER --}}
                        <td class="px-6 py-5">
                            <div class="font-semibold">
                                {{ $order->recipient_name }}
                            </div>
                            <div class="text-xs text-zinc-500 truncate max-w-[200px]">
                                {{ $order->delivery_address }}
                            </div>
                        </td>

                        {{-- PAYMENT --}}
                        @php
                            $paymentStatus = $order->payment_status;

                            $statusMap = [
                                'pending' => ['color' => 'bg-yellow-500', 'label' => 'Pending'],
                                'unpaid' => ['color' => 'bg-red-500', 'label' => 'Unpaid'],
                                'paid' => ['color' => 'bg-blue-500', 'label' => 'Paid'],
                            ];

                            $status = $statusMap[$paymentStatus] ?? [
                                'color' => 'bg-zinc-400',
                                'label' => strtoupper($paymentStatus)
                            ];
                        @endphp

                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">

                                <div class="w-2 h-2 rounded-full {{ $status['color'] }}"></div>

                                <span class="text-xs font-bold uppercase">
                                    {{ $status['label'] }}
                                </span>

                            </div>
                        </td>

                        {{-- TOTAL --}}
                        <td class="px-6 py-5 text-right">
                            <div class="font-black text-zinc-900 dark:text-white">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </div>
                            <div class="text-xs text-zinc-500">
                                {{ $order->orderItems->count() }} items
                            </div>
                        </td>

                        {{-- STATUS --}}
                        <td class="px-6 py-5 text-center">

                            <flux:badge 
                                color="{{ match($order->order_status) {
                                    'pending' => 'zinc',
                                    'processing' => 'yellow',
                                    'completed' => 'emerald',
                                    'cancelled' => 'red',
                                    default => 'zinc'
                                } }}"
                                size="sm"
                                class="uppercase"
                            >
                                {{ $order->order_status }}
                            </flux:badge>

                        </td>

                        {{-- ACTION --}}
                        <td class="px-6 py-5 text-right">
                            <flux:modal.trigger name="show-order-{{ $order->id }}">
                                <flux:button size="sm" variant="primary">
                                    Detail →
                                </flux:button>
                            </flux:modal.trigger>
                        </td>

                    </tr>
                    <tr>
                        <flux:modal name="show-order-{{ $order->id }}">

                            <div class="space-y-6">

                                {{-- HEADER --}}
                                <div>
                                    <flux:heading size="lg">
                                        Order #{{ $order->order_number }}
                                    </flux:heading>
                                    <p class="text-sm text-zinc-500">
                                        {{ $order->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>

                                {{-- STATUS + PAYMENT --}}
                                <div class="flex items-center justify-between">

                                    {{-- ORDER STATUS --}}
                                    <flux:badge 
                                        color="{{ match($order->order_status) {
                                            'pending' => 'zinc',
                                            'processing' => 'yellow',
                                            'completed' => 'emerald',
                                            'cancelled' => 'red',
                                            default => 'zinc'
                                        } }}"
                                        class="uppercase"
                                    >
                                        {{ $order->order_status }}
                                    </flux:badge>

                                    {{-- PAYMENT STATUS --}}
                                    <div class="text-right">
                                        <div class="text-xs text-zinc-500">Payment</div>
                                        <div class="font-bold">
                                            {{ strtoupper($order->payment_status) }}
                                        </div>
                                    </div>

                                </div>

                                {{-- CUSTOMER --}}
                                <div class="p-4 bg-zinc-50 dark:bg-white/5 rounded-xl">
                                    <div class="font-bold text-sm">Customer</div>
                                    <div>{{ $order->recipient_name }}</div>
                                    <div class="text-sm text-zinc-500">{{ $order->recipient_phone }}</div>
                                    <div class="text-sm text-zinc-500">{{ $order->delivery_address }}</div>
                                </div>

                                {{-- ITEMS --}}
                                <div>
                                    <div class="font-bold mb-2">Items</div>

                                    <div class="space-y-2">
                                        @foreach ($order->items as $item)
                                            <div class="flex justify-between items-center text-sm">

                                                <div>
                                                    {{ $item->product->name }}
                                                    <span class="text-zinc-500">x{{ $item->quantity }}</span>
                                                </div>

                                                <div class="font-semibold">
                                                    Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                                </div>

                                            </div>
                                        @endforeach
                                    </div>

                                    {{-- TOTAL --}}
                                    <div class="border-t mt-3 pt-3 flex justify-between font-bold">
                                        <span>Total</span>
                                        <span>Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- PAYMENT DETAIL --}}
                                @if ($order->payment)
                                    <div class="p-4 bg-zinc-50 dark:bg-white/5 rounded-xl text-sm space-y-1">
                                        <div><b>Method:</b> {{ $order->payment->payment_method }}</div>
                                        <div><b>Status:</b> {{ $order->payment->status }}</div>

                                        @if ($order->payment->transaction_time)
                                            <div><b>Paid at:</b> {{ $order->payment->transaction_time }}</div>
                                        @endif

                                        @if ($order->payment->qris_url)
                                            <a href="{{ $order->payment->qris_url }}" target="_blank"
                                                class="text-blue-500 underline text-xs">
                                                Lihat QRIS
                                            </a>
                                        @endif
                                    </div>
                                @endif
                                {{-- NOTES --}}
                                <div class="p-4 bg-yellow-50 dark:bg-yellow/5 rounded-xl">
                                    <div class="font-bold text-sm">Notes</div>
                                    <div class="text-sm text-zinc-500">{{ $order->delivery_notes }}</div>
                                </div>

                                {{-- ACTION --}}
                                <div class="flex justify-between items-center">

                                    {{-- STATUS ACTION --}}
                                    <div class="flex gap-2">

                                        @if ($order->order_status === 'pending')
                                            <flux:button 
                                                wire:click="updateStatus({{ $order->id }}, 'processing')"
                                                size="sm"
                                                color="yellow"
                                            >
                                                Proses
                                            </flux:button>
                                        @endif

                                        @if ($order->order_status === 'processing')
                                            <flux:button 
                                                wire:click="updateStatus({{ $order->id }}, 'completed')"
                                                size="sm"
                                                color="emerald"
                                            >
                                                Selesai
                                            </flux:button>
                                        @endif

                                        @if ($order->order_status !== 'cancelled')
                                            <flux:button 
                                                wire:click="updateStatus({{ $order->id }}, 'cancelled')"
                                                size="sm"
                                                variant="danger"
                                            >
                                                Cancel
                                            </flux:button>
                                        @endif

                                    </div>

                                    {{-- CLOSE --}}
                                    <flux:modal.close>
                                        <flux:button variant="ghost">
                                            Tutup
                                        </flux:button>
                                    </flux:modal.close>

                                </div>

                            </div>

                        </flux:modal>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-zinc-500">
                            No orders yet 🍃
                        </td>
                    </tr>
                @endforelse

            </tbody>

        </table>

    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $orders->links() }}
    </div>

</div>
