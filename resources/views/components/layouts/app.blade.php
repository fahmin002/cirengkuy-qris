<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-2 rounded">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 p-2 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-red-500">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        {{ $slot }}
    </flux:main>
</x-layouts.app.sidebar>