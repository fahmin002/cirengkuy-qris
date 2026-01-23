<flux:modal name="show-order-{{ $order->id }}" class="w-full max-w-2xl">
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <flux:heading size="xl" class="text-2xl font-black">Order #{{ $order->order_number }}</flux:heading>
                <flux:subheading class="text-sm uppercase tracking-tighter">{{ $order->created_at->format('d M Y, H:i') }}</flux:subheading>
            </div>
            <div class="flex flex-col items-end gap-2">
                @php
                    $statusColor = match ($order->status) {
                        'paid' => 'blue',
                        'completed' => 'emerald',
                        'processing' => 'yellow',
                        'cancelled' => 'red',
                        default => 'zinc',
                    };
                @endphp
                <flux:badge color="{{ $statusColor }}" size="lg" class="px-4 py-1 font-bold">{{ strtoupper($order->status) }}</flux:badge>
                
                @if ($order->payment->refund_time != null)
                    <flux:badge color="red" size="sm" variant="solid">REFUNDED</flux:badge>
                @endif
            </div>
        </div>

        <flux:separator variant="subtle" />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 bg-zinc-50 dark:bg-white/5 p-4 rounded-2xl">
            <div class="space-y-1">
                <flux:heading size="sm" class="uppercase opacity-50 text-[10px]">Customer</flux:heading>
                <flux:text class="font-black text-lg">{{ $order->user->name }}</flux:text>
                <flux:text size="sm" class="flex items-center gap-2">
                    <flux:icon.credit-card variant="micro" />
                    {{ strtoupper($order->payment->payment_method) }}
                </flux:text>
            </div>

            <div class="space-y-1 border-t sm:border-t-0 sm:border-l border-zinc-200 dark:border-white/10 pt-4 sm:pt-0 sm:pl-6">
                <flux:heading size="sm" class="uppercase opacity-50 text-[10px]">Delivery To</flux:heading>
                <flux:text class="font-bold text-base">{{ $order->recipient_name }}</flux:text>
                <flux:text size="sm" class="leading-tight">{{ $order->delivery_address }}</flux:text>
            </div>
        </div>

        <div class="space-y-3">
            <flux:heading size="sm" class="uppercase opacity-50 text-[10px]">Items Purchased</flux:heading>
            <div class="rounded-2xl border border-zinc-200 dark:border-white/10 overflow-hidden shadow-sm">
                <table class="w-full text-sm text-left">
                    <thead class="bg-zinc-100 dark:bg-white/10 text-zinc-500">
                        <tr>
                            <th class="px-5 py-3 font-bold uppercase text-[10px]">Product</th>
                            <th class="px-5 py-3 text-center font-bold uppercase text-[10px]">Qty</th>
                            <th class="px-5 py-3 text-right font-bold uppercase text-[10px]">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-white/5">
                        @foreach($order->orderItems as $item)
                        <tr class="text-base"> <td class="px-5 py-4 font-bold text-zinc-800 dark:text-zinc-200">
                                {{ $item->product->name }}
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="bg-zinc-100 dark:bg-white/10 px-3 py-1 rounded-md font-black">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right font-mono font-bold">
                                Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-blue-50 dark:bg-blue-900/20 border-t-2 border-blue-100 dark:border-blue-900/50">
                        <tr>
                            <td colspan="2" class="px-5 py-4 text-right font-bold uppercase text-xs text-blue-700 dark:text-blue-300">Total Payment</td>
                            <td class="px-5 py-4 text-right text-xl font-black text-blue-600 dark:text-blue-400">
                                Rp{{ number_format($order->total_price, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        @if($order->delivery_notes)
        <div class="p-4 bg-amber-50 dark:bg-amber-900/20 border-l-4 border-amber-400 rounded-xl">
            <flux:heading size="sm" class="text-amber-800 dark:text-amber-400 font-black uppercase text-[10px] mb-1">Notes dari Pembeli:</flux:heading>
            <flux:text class="text-amber-900 dark:text-amber-200 font-medium">{{ $order->delivery_notes }}</flux:text>
        </div>
        @endif

        <div class="flex gap-3 pt-4">
            <flux:modal.close class="flex-1">
                <flux:button variant="ghost" class="w-full py-3 text-base">Close</flux:button>
            </flux:modal.close>
            
            <flux:button variant="primary" icon="printer"
                as="a"
                href="{{ route('admin.orders.print', $order->id) }}"
                target="_blank"
                class="flex-1 py-3 text-base"
            >Print Invoice</flux:button>
        </div>
    </div>
</flux:modal>