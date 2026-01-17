<x-layouts.app :title="__('Orders')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl">Orders Page</flux:heading>
        <flux:breadcrumbs>
            <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
            <flux:breadcrumbs.item>Orders</flux:breadcrumbs.item>
        </flux:breadcrumbs>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3 items-left">
            <flux:input class="" size="sm" placeholder="Search Order..." />
        </div>


        <div
            class="relative overflow-x-auto bg-neutral-primary-soft dark:border-zinc-700 shadow-xs rounded-xl border border-default">
            <table class="w-full text-sm text-left rtl:text-right text-body">
                <thead
                    class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base dark:border-zinc-700 border-default">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Order Number
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Customer
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3 font-medium">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="bg-neutral-primary border-default">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            {{ $order->order_number }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $order->user->name }}
                        </td>
                        <td class="px-6 py-4">
                            Rp. {{ number_format($order->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $paymentColor = match ($order->payment->payment_method) {
                                    'qris' => 'red',
                                    'cash' => 'green',
                                    default => 'zinc',
                                };
                            @endphp
                            <flux:badge color="{{ $paymentColor }}">
                                {{ ucfirst($order->payment->payment_method) }}
                            </flux:badge>
                        </td>
                        <td class="px-6 py-4">
                        @php
                            $statusColor = match ($order->status) {
                                'paid' => 'blue',
                                'pending' => 'zinc',
                                'processing' => 'yellow',
                                'completed' => 'emerald',
                                'cancelled' => 'red',
                                default => 'rose',
                            };
                        @endphp
                        <flux:badge color="{{ $statusColor }}">{{ ucfirst($order->status) }}</flux:badge>
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <flux:dropdown>
                                <flux:button size="sm" icon:trailing="chevron-down">
                                    Show
                                </flux:button>

                                <flux:menu>
                                    <flux:menu.item
                                        icon="eye"
                                        href="{{ route('admin.orders.show', $order->id) }}"
                                    >
                                        Detail
                                    </flux:menu.item>
                                    <flux:modal.trigger as="span" name="edit-order-{{ $order->id }}">
                                        <flux:menu.item
                                            icon="pencil"
                                        >
                                        Edit
                                    </flux:menu.item>
                                    </flux:modal.trigger>

                                
                                    {{-- <flux:menu.item
                                        icon="pencil"
                                        href="{{ route('admin.orders.edit', $order->id) }}"
                                    >
                                        Edit
                                    </flux:menu.item> --}}
                                </flux:menu>
                            </flux:dropdown>

                            <form
                                id="delete-order-{{ $order->id }}"
                                {{-- action="{{ route('admin.orders.destroy', $order->id) }}" --}}
                                method="POST"
                                class="hidden"
                            >
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>    
                    @php
                        $canEditRecipient = in_array($order->status, ['pending', 'paid']);
                    @endphp       
                    <flux:modal name="edit-order-{{ $order->id }}" class="md:w-[32rem]">
                        <form
                            method="POST"
                            action="{{ route('admin.orders.update', $order->id) }}"
                            class="space-y-6"
                        >
                            @csrf
                            @method('PUT')

                            <div class="mb-2 flx">
                                <flux:heading size="lg">
                                    Edit Order - <span class="text-accent/50">{{ $order->order_number }}</span>
                                </flux:heading>
                                {{-- <flux:text class="mt-1">
                                    Order #{{ $order->order_number }}
                                </flux:text> --}}
                            </div>

                            {{-- STATUS (SELALU BOLEH) --}}
                            <flux:select
                                name="status"
                                label="Status Order"
                                {{-- value="{{ $order->status }}" --}}
                                class="mb-2"
                            >
                                <option value="pending" @selected($order->status === 'pending')>Pending</option>
                                <option value="paid" @selected($order->status === 'paid')>Paid</option>
                                <option value="processing" @selected($order->status === 'processing')>Processing</option>
                                <option value="completed" @selected($order->status === 'completed')>Completed</option>
                                <option value="cancelled" @selected($order->status === 'cancelled')>Cancelled</option>
                            </flux:select>

                            {{-- RECIPIENT NAME --}}
                            <flux:input
                                name="recipient_name"
                                label="Recipient Name"
                                value="{{ $order->recipient_name }}"
                                :disabled="!$canEditRecipient"
                                class="mb-2"
                            />

                            {{-- RECIPIENT PHONE --}}
                            <flux:input
                                name="recipient_phone"
                                label="Recipient Phone"
                                value="{{ $order->recipient_phone }}"
                                :disabled="!$canEditRecipient"
                                class="mb-2"
                            />

                            {{-- DELIVERY ADDRESS --}}
                            <flux:textarea
                                name="delivery_address"
                                label="Delivery Address"
                                :disabled="!$canEditRecipient"
                                class="mb-2"
                            >{{ $order->delivery_address }}</flux:textarea>

                            {{-- DELIVERY NOTES (SELALU BOLEH) --}}
                            <flux:textarea
                                name="delivery_notes"
                                label="Delivery Notes (optional)"
                                class="mb-2"
                            >{{ $order->delivery_notes }}</flux:textarea>

                            @unless($canEditRecipient)
                                <flux:text size="sm" class="text-yellow-600 mb-2">
                                    Data penerima tidak bisa diubah karena order sudah masuk tahap <b>{{ $order->status }}</b>.
                                </flux:text>
                            @endunless

                            <div class="flex mt-2">
                                <flux:spacer />
                                <flux:button type="submit" variant="primary">
                                    Save Changes
                                </flux:button>
                            </div>
                        </form>
                    </flux:modal>
        
                    @endforeach
                    {{-- <tr class="bg-neutral-primary border-b border-default">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            Apple MacBook Pro 17"
                        </th>
                        <td class="px-6 py-4">
                            Silver
                        </td>
                        <td class="px-6 py-4">
                            Laptop
                        </td>
                        <td class="px-6 py-4">
                            $2999
                        </td>
                        <td class="px-6 py-4">
                            231
                        </td>
                    </tr>
                    <tr class="bg-neutral-primary border-b border-default">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            Microsoft Surface Pro
                        </th>
                        <td class="px-6 py-4">
                            White
                        </td>
                        <td class="px-6 py-4">
                            Laptop PC
                        </td>
                        <td class="px-6 py-4">
                            $1999
                        </td>
                        <td class="px-6 py-4">
                            423
                        </td>
                    </tr>
                    <tr class="bg-neutral-primary">
                        <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                            Magic Mouse 2
                        </th>
                        <td class="px-6 py-4">
                            Black
                        </td>
                        <td class="px-6 py-4">
                            Accessories
                        </td>
                        <td class="px-6 py-4">
                            $99
                        </td>
                        <td class="px-6 py-4">
                            121
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div
                class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern
                    class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
</x-layouts.app>