<flux:modal name="show-order-{{ $order->id }}" class="md:w-[650px]">
    <div class="space-y-6">
        <div class="flex justify-between items-start">
            <div>
                <flux:heading size="xl">Order Detail</flux:heading>
                <flux:subheading>ID: #{{ $order->order_number }} • {{ $order->created_at->format('d M Y, H:i') }}</flux:subheading>
            </div>
            <div class="text-right">
                @php
                    $statusColor = match ($order->status) {
                        'paid' => 'blue',
                        'completed' => 'emerald',
                        'processing' => 'yellow',
                        'cancelled' => 'red',
                        default => 'zinc',
                    };
                @endphp
                <flux:badge color="{{ $statusColor }}" inset="top bottom">{{ strtoupper($order->status) }}</flux:badge>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <div class="grid grid-cols-2 gap-8">
            <div class="space-y-3">
                <flux:heading size="sm" class="uppercase tracking-widest opacity-50">Customer</flux:heading>
                <div>
                    <flux:text class="font-bold text-zinc-800 dark:text-white">{{ $order->user->name }}</flux:text>
                    <flux:text size="sm" variant="subtle">{{ $order->user->email }}</flux:text>
                </div>
                <div>
                    <flux:label>Payment Method</flux:label>
                    <flux:text size="sm" class="flex items-center gap-2">
                        <flux:icon.credit-card variant="micro" />
                        {{ strtoupper($order->payment->payment_method) }}
                    </flux:text>
                </div>
            </div>

            <div class="space-y-3">
                <flux:heading size="sm" class="uppercase tracking-widest opacity-50">Shipping Info</flux:heading>
                <div>
                    <flux:text class="font-bold text-zinc-800 dark:text-white">{{ $order->recipient_name }}</flux:text>
                    <flux:text size="sm" variant="subtle">{{ $order->recipient_phone }}</flux:text>
                </div>
                <flux:text size="sm" class="leading-relaxed italic">{{ $order->delivery_address }}</flux:text>
            </div>
        </div>

        <div class="space-y-3">
            <flux:heading size="sm" class="uppercase tracking-widest opacity-50">Items Purchased</flux:heading>
            
            <div class="rounded-xl border border-zinc-200 dark:border-white/10 overflow-hidden shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-50 dark:bg-white/5 text-zinc-500 border-b border-zinc-200 dark:border-white/10">
                        <tr>
                            <th class="px-4 py-2 font-medium text-[11px] uppercase">Product</th>
                            <th class="px-4 py-2 text-center font-medium text-[11px] uppercase">Qty</th>
                            <th class="px-4 py-2 text-right font-medium text-[11px] uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-white/5">
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td class="px-4 py-3 font-medium text-zinc-800 dark:text-zinc-200">
                                {{ $item->product->name }}
                            </td>
                            <td class="px-4 py-3 text-center text-zinc-600 dark:text-zinc-400">
                                {{ $item->quantity }}x
                            </td>
                            <td class="px-4 py-3 text-right font-mono text-zinc-800 dark:text-zinc-200">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-zinc-50 dark:bg-white/5 border-t border-zinc-200 dark:border-white/10 font-bold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-right uppercase text-[10px] tracking-wider text-zinc-500">Total Payment</td>
                            <td class="px-4 py-3 text-right text-lg font-black text-blue-600 dark:text-blue-400">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->delivery_notes)
        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 rounded-r-xl">
            <flux:label class="text-amber-800 dark:text-amber-400 font-bold uppercase text-[10px]">Buyer's Notes:</flux:label>
            <flux:text size="sm" class="text-amber-900 dark:text-amber-200">{{ $order->delivery_notes }}</flux:text>
        </div>
        @endif

        <div class="flex justify-end gap-3 pt-2">
            <flux:modal.close>
                <flux:button variant="ghost">Close</flux:button>
            </flux:modal.close>
            <flux:button variant="primary" icon="printer" 
                icon="printer" 
                as="a" 
                href="{{ route('admin.orders.print', $order->id) }}" 
                target="_blank"
            >Print Invoice</flux:button>
        </div>
    </div>
</flux:modal>