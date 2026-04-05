<x-layouts.app :title="__('Add Product')">

    <div class="max-w-2xl mx-auto space-y-6">

        {{-- HEADER --}}
        <div>
            <flux:heading size="xl">Add Product 🍥</flux:heading>
            <flux:breadcrumbs>
                <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                <flux:breadcrumbs.item href="{{ route('admin.products.index') }}">Products</flux:breadcrumbs.item>
                <flux:breadcrumbs.item>Add</flux:breadcrumbs.item>
            </flux:breadcrumbs>
        </div>

        {{-- FORM --}}
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- NAME --}}
            <div>
                <flux:label>Name</flux:label>
                <flux:input name="name" value="{{ old('name') }}" />
                @error('name') <div class="text-red-500 text-sm">{{ $message }}</div> @enderror
            </div>

            {{-- DESCRIPTION --}}
            <div>
                <flux:label>Description</flux:label>
                <flux:textarea name="description">{{ old('description') }}</flux:textarea>
            </div>

            {{-- PRICE --}}
            <div>
                <flux:label>Price</flux:label>
                <flux:input type="number" name="price" value="{{ old('price') }}" />
            </div>

            {{-- STOCK --}}
            <div>
                <flux:label>Stock</flux:label>
                <flux:input type="number" name="stock" value="{{ old('stock') }}" />
            </div>

            {{-- IMAGE --}}
            <div>
                <flux:label>Image</flux:label>
                <input type="file" name="image" class="block w-full text-sm">
            </div>

            {{-- ACTIVE --}}
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" value="1" checked>
                <span>Active</span>
            </div>

            {{-- ACTION --}}
            <div class="flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}">
                    <flux:button variant="ghost">Cancel</flux:button>
                </a>

                <flux:button type="submit" variant="primary">
                    Save Product
                </flux:button>
            </div>

        </form>

    </div>

</x-layouts.app>