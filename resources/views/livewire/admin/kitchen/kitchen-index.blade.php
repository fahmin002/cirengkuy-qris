<div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" wire:poll.5s>

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <div>
            <flux:heading size="xl">Kitchen</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Kitchen</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="flex flex-wrap gap-2 mb-2">
        <flux:button wire:click="$set('filterType', 'all')" variant="{{ $filterType === 'all' ? 'primary' : 'ghost' }}"
            size="sm" class="rounded-full">
            All
        </flux:button>

        <flux:button wire:click="$set('filterType', 'cooked')"
            variant="{{ $filterType === 'cooked' ? 'primary' : 'ghost' }}"
            color="{{ $filterType === 'cooked' ? 'orange' : '' }}" size="sm" class="rounded-full flex gap-2">
            Cooked
        </flux:button>

        <flux:button wire:click="$set('filterType', 'frozen')"
            variant="{{ $filterType === 'frozen' ? 'primary' : 'ghost' }}"
            color="{{ $filterType === 'frozen' ? 'blue' : '' }}" size="sm" class="rounded-full flex gap-2">
            Frozen
        </flux:button>
    </div>

    {{-- GRID KITCHEN --}}
    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-4">

        @forelse ($orders as $order)
            <div
                class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl p-4 shadow-sm flex flex-col justify-between">

                {{-- HEADER --}}
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <div class="font-black text-lg text-zinc-900 dark:text-white">
                            #{{ $order->order_number }}
                        </div>
                        <div class="text-xs text-zinc-500">
                            {{ $order->created_at->format('H:i') }} • {{ $order->created_at->diffForHumans() }}
                        </div>
                        <flux:badge color="{{ $order->order_status === 'processing' ? 'yellow' : 'zinc' }}" size="sm"
                            class="uppercase">
                            {{ $order->order_status }}
                        </flux:badge>
                    </div>

                </div>

                {{-- CUSTOMER --}}
                <div class="text-sm text-zinc-600 dark:text-zinc-400 mb-3">
                    {{ $order->recipient_name ?? 'Walk-in' }}
                </div>

                {{-- ITEMS --}}
                <div class="mb-4 text-sm space-y-1">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between">
                            <span>{{ $item->quantity }}x {{ $item->product->name }}</span>
                        </div>
                    @endforeach
                </div>

                {{-- ACTION --}}
                <div class="flex gap-2 mt-auto">

                    @if ($order->order_status === 'pending')
                        <flux:button wire:click="updateStatus({{ $order->id }}, 'processing')" size="sm" variant="primary"
                            class="w-full">
                            Start 🔥
                        </flux:button>
                    @endif

                    @if ($order->order_status === 'processing')
                        <flux:button wire:click="updateStatus({{ $order->id }}, 'completed')" size="sm" variant="filled"
                            class="w-full bg-emerald-500 hover:bg-emerald-600">
                            Done ✅
                        </flux:button>
                    @endif

                </div>

            </div>

        @empty
            <div class="col-span-full text-center text-zinc-500 py-10">
                Tidak ada pesanan di dapur 🍃
            </div>
        @endforelse

    </div>
</div>