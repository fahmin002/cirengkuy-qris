<x-layouts.app :title="__('Orders')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Orders</flux:heading>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Orders</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
            <div class="w-72">
                <flux:input size="sm" icon="magnifying-glass" placeholder="Search Order Number..." />
            </div>
        </div>
        <div class="flex flex-wrap gap-2 mb-4 overflow-x-auto pb-2">
            <flux:button variant="filled" size="sm" class="rounded-full">All Orders</flux:button>
            <flux:button variant="ghost" size="sm" class="rounded-full flex gap-2">
                <div class="w-2 h-2 rounded-full bg-zinc-400"></div> Pending
            </flux:button>
            <flux:button variant="ghost" size="sm" class="rounded-full flex gap-2">
                <div class="w-2 h-2 rounded-full bg-blue-500"></div> Paid
            </flux:button>
            <flux:button variant="ghost" size="sm" class="rounded-full flex gap-2">
                <div class="w-2 h-2 rounded-full bg-yellow-500"></div> Processing
            </flux:button>
            <flux:button variant="ghost" size="sm" class="rounded-full flex gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Completed
            </flux:button>
        </div>

        <div
            class="overflow-x-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead
                    class="bg-zinc-50 dark:bg-white/5 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Order / Date</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Items</th>
                        <th class="px-6 py-4 text-right">Total Amount</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    @foreach ($orders as $order)
                        <tr class="group hover:bg-zinc-50/50 dark:hover:bg-white/[0.02]">
                            {{-- Order Info --}}
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-zinc-100 dark:bg-white/5 rounded-lg text-zinc-600">
                                        <flux:icon.shopping-bag />
                                    </div>
                                    <div>
                                        <div class="font-black text-lg text-zinc-900 dark:text-white">
                                            #{{ $order->order_number }}</div>
                                        <div class="text-sm text-zinc-500">{{ $order->created_at->format('H:i') }} •
                                            {{ $order->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Customer & Address --}}
                            <td class="px-6 py-6">
                                <div class="font-bold text-base">{{ $order->user->name }}</div>
                                <div class="text-sm text-zinc-500 truncate max-w-[200px]">{{ $order->delivery_address }}
                                </div>
                            </td>

                            {{-- Status Besar untuk Tap --}}
                            <td class="px-6 py-6 text-center">
                                @php
                                    $statusColor = match ($order->status) {
                                        'paid' => 'blue',
                                        'completed' => 'emerald',
                                        'processing' => 'yellow',
                                        'cancelled' => 'red',
                                        default => 'zinc',
                                    };
                                @endphp
                                {{-- Trigger Modal Detail saat Status di Tap --}}
                                <flux:modal.trigger name="show-order-{{ $order->id }}">
                                    <button class="w-full">
                                        <flux:badge color="{{ $statusColor }}" size="lg"
                                            class="w-full justify-center py-2 cursor-pointer uppercase font-bold tracking-wider">
                                            {{ $order->status }}
                                        </flux:badge>
                                    </button>
                                </flux:modal.trigger>
                            </td>

                            {{-- Total --}}
                            <td class="px-6 py-6 text-right">
                                <div class="text-lg font-black text-zinc-900 dark:text-white">
                                    Rp{{ number_format($order->total_price, 0, ',', '.') }}
                                </div>
                                <div class="text-xs text-zinc-500">{{ $order->orderItems->count() }} Items</div>
                            </td>

                            {{-- Quick Action Buttons --}}
                            <td class="px-6 py-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <flux:modal.trigger name="edit-order-{{ $order->id }}">
                                        <flux:button icon="pencil" variant="ghost" size="sm"></flux:button>
                                    </flux:modal.trigger>

                                    {{-- Tombol Detail Utama --}}
                                    <flux:modal.trigger name="show-order-{{ $order->id }}">
                                        <flux:button variant="primary" size="sm" class="px-4">VIEW</flux:button>
                                    </flux:modal.trigger>
                                </div>
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
            @include('livewire.admin.orders.partials.detail-modal', ['order' => $order])
            @include('livewire.admin.orders.partials.edit-modal', ['order' => $order])
        </div>
    </div>
</x-layouts.app>