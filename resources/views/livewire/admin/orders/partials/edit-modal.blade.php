<flux:modal name="edit-order-{{ $order->id }}" class="md:w-lg">
    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="mb-2">
            <flux:heading size="lg">Edit Order #{{ $order->order_number }}</flux:heading>
            <flux:subheading>Update status atau informasi pengiriman</flux:subheading>
        </div>

        <flux:separator variant="subtle" />
        <div class="space-y-4 mt-1">
            <flux:select name="status" label="Order Status" icon="arrow-path">
                @php
                    $statusOptions = match ($order->status) {
                        'pending' => ['pending', 'paid', 'cancelled'],
                        'paid' => ['paid', 'processing', 'cancelled'],
                        'processing' => ['processing', 'completed', 'cancelled'],
                        'completed' => ['completed'],
                        'cancelled' => ['cancelled'],
                        default => [],
                    };
                @endphp
                @foreach($statusOptions as $st)
                    <option value="{{ $st }}" @selected($order->status === $st)>{{ ucfirst($st) }}</option>
                @endforeach
            </flux:select>
            @php $canEdit = in_array($order->status, ['pending', 'paid']); @endphp

            <div class="grid grid-cols-2 gap-4">
                <flux:input name="recipient_name" label="Recipient Name" value="{{ $order->recipient_name }}" :disabled="!$canEdit" />
                <flux:input name="recipient_phone" label="Recipient Phone" value="{{ $order->recipient_phone }}" :disabled="!$canEdit" />
            </div>

            <flux:textarea name="delivery_address" label="Delivery Address" :disabled="!$canEdit">{{ $order->delivery_address }}</flux:textarea>
            
            <flux:textarea name="delivery_notes" label="Delivery Notes (Internal/Shipping)">{{ $order->delivery_notes }}</flux:textarea>
        </div>

        @if(!$canEdit)
            <div class="p-3 bg-zinc-100 mt-2 dark:bg-white/5 rounded-lg flex gap-3 items-center">
                <flux:icon.information-circle class="text-zinc-500" />
                <flux:text size="xs">Data alamat dikunci karena status sudah <b>{{ $order->status }}</b>.</flux:text>
            </div>
        @endif

        <div class="flex pt-4">
            <flux:spacer />
            <flux:modal.close>
                <flux:button variant="ghost" class="mr-2">Cancel</flux:button>
            </flux:modal.close>
            <flux:button type="submit" variant="primary">Save Changes</flux:button>
        </div>
    </form>
</flux:modal>