<flux:modal name="edit-order-{{ $order->id }}" class="w-full max-w-lg">
    <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="space-y-6 p-2">
        @csrf
        @method('PUT')

        <div class="flex items-center gap-4 mb-2">
            <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-2xl text-blue-600">
                <flux:icon.pencil-square variant="outline" />
            </div>
            <div>
                <flux:heading size="xl" class="font-black">Update Order #{{ $order->order_number }}</flux:heading>
                <flux:subheading>Atur status pengerjaan atau revisi data</flux:subheading>
            </div>
        </div>

        <flux:separator variant="subtle" />

        <div class="space-y-5">
            {{-- Status: Dibuat lebih lega --}}
            <div class="space-y-2">
                <flux:label class="font-bold text-sm uppercase tracking-wider opacity-60">Status Pesanan</flux:label>
                <flux:select name="status" icon="arrow-path" class="py-3 shadow-sm rounded-xl">
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
                        <option value="{{ $st }}" @selected($order->status === $st)>
                            {{ strtoupper($st) }}
                        </option>
                    @endforeach
                </flux:select>
            </div>

            @php $canEdit = in_array($order->status, ['pending', 'paid']); @endphp

            {{-- Input Nama & Phone: Full width di tablet biar gampang di tap --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:input name="recipient_name" label="Nama Penerima" 
                    class="py-2"
                    value="{{ $order->recipient_name }}" 
                    :disabled="!$canEdit" />
                <flux:input name="recipient_phone" label="No. Handphone" 
                    class="py-2"
                    value="{{ $order->recipient_phone }}" 
                    :disabled="!$canEdit" />
            </div>

            <flux:textarea name="delivery_address" label="Alamat Lengkap" 
                rows="3"
                class="rounded-xl"
                :disabled="!$canEdit">{{ $order->delivery_address }}</flux:textarea>
            
            <flux:textarea name="delivery_notes" label="Catatan Internal / Pengiriman" 
                rows="2"
                placeholder="Contoh: Titip di satpam..."
                class="rounded-xl">{{ $order->delivery_notes }}</flux:textarea>
        </div>

        {{-- Warning Zone --}}
        @if(!$canEdit)
            <div class="p-4 bg-zinc-100 dark:bg-white/5 border-2 border-dashed border-zinc-200 dark:border-white/10 rounded-2xl flex gap-4 items-center">
                <flux:icon.lock-closed class="text-zinc-400" />
                <flux:text size="sm" class="leading-tight">
                    Info alamat <b>dikunci</b> karena pesanan sedang diproses/selesai.
                </flux:text>
            </div>
        @endif

        {{-- Footer Buttons: Dibuat besar dan kontras --}}
        <div class="flex gap-3 pt-4">
            <flux:modal.close class="flex-1">
                <flux:button variant="ghost" class="w-full py-4 font-bold">BATAL</flux:button>
            </flux:modal.close>
            
            <flux:button type="submit" variant="primary" class="flex-1 py-4 font-black shadow-lg shadow-blue-500/20">
                SIMPAN PERUBAHAN
            </flux:button>
        </div>
    </form>
</flux:modal>