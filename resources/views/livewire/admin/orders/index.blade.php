
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

        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden shadow-sm">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-white/5 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase text-[11px] tracking-wider">
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
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-white/2 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-zinc-900 dark:text-white">#{{ $order->order_number }}</div>
                                <div class="text-xs text-zinc-500">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium">{{ $order->user->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $order->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-zinc-600">
                                <flux:badge variant="subtle" size="sm">{{ $order->orderItems->count() }} Items</flux:badge>
                            </td>
                            <td class="px-6 py-4 text-right font-semibold">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    @php
                                        $statusColor = match ($order->status) {
                                            'paid' => 'blue',
                                            'completed' => 'emerald',
                                            'processing' => 'yellow',
                                            'cancelled' => 'red',
                                            default => 'zinc',
                                        };
                                    @endphp
                                    <flux:badge color="{{ $statusColor }}" size="sm" inset="top bottom">
                                        {{ strtoupper($order->status) }}
                                    </flux:badge>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <flux:dropdown>
                                    <flux:button variant="ghost" size="sm" icon="ellipsis-horizontal"></flux:button>
                                    <flux:menu>
                                        <flux:modal.trigger name="show-order-{{ $order->id }}">
                                            <flux:menu.item icon="eye">View Details</flux:menu.item>
                                        </flux:modal.trigger>
                                        <flux:modal.trigger name="edit-order-{{ $order->id }}">
                                            <flux:menu.item icon="pencil">Edit Order</flux:menu.item>
                                        </flux:modal.trigger>
                                        <flux:menu.separator />
                                        <flux:menu.item icon="printer">
                                            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank">Print Invoice</a>
                                        </flux:menu.item>
                                    </flux:menu>
                                </flux:dropdown>
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