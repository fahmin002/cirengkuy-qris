<x-layouts.app :title="__('Products')">

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        {{-- HEADER --}}
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl">Products</flux:heading>
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="{{ route('admin.dashboard') }}">Dashboard</flux:breadcrumbs.item>
                    <flux:breadcrumbs.item>Products</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>

            <div class="flex gap-2">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <flux:input name="search" value="{{ request('search') }}" size="sm" icon="magnifying-glass"
                        placeholder="Search product..." />
                </form>
                <a href="{{ route('admin.products.create') }}">
                    <flux:button variant="primary" size="sm">+ Add</flux:button>
                </a>
            </div>
        </div>
        {{-- TABLE --}}
        <div
            class="overflow-x-auto bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl shadow-sm">

            <table class="w-full text-sm text-left">

                <thead
                    class="bg-zinc-50 dark:bg-white/5 border-b border-zinc-200 dark:border-zinc-800 text-zinc-500 uppercase text-[11px] tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Product</th>
                        <th class="px-6 py-4">Price</th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-right"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">

                    @forelse ($products as $product)
                        <tr class="group hover:bg-zinc-50/50 dark:hover:bg-white/[0.02]">

                            {{-- PRODUCT --}}
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-lg overflow-hidden bg-zinc-100">
                                        <img src="{{ asset('storage/' . $product->image) }}"
                                            class="w-full h-full object-cover">
                                    </div>

                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">
                                            {{ $product->name }}
                                        </div>
                                        <div class="text-sm text-zinc-500 line-clamp-1">
                                            {{ $product->description }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            {{-- PRICE --}}
                            <td class="px-6 py-5">
                                <div class="font-bold">
                                    Rp{{ number_format($product->price, 0, ',', '.') }}
                                </div>
                            </td>

                            {{-- STOCK --}}
                            <td class="px-6 py-5">
                                <div class="font-semibold">
                                    {{ $product->stock }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-5 text-center">
                                <flux:badge color="{{ $product->is_active ? 'emerald' : 'zinc' }}" size="sm"
                                    class="uppercase">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </flux:badge>
                            </td>

                            {{-- ACTION --}}
                            <td class="px-6 py-5 text-right">
                                <div class="flex justify-end gap-2">

                                    <flux:modal.trigger name="edit-product-{{ $product->id }}">
                                        <flux:button icon="pencil" variant="ghost" size="sm"
                                            onclick='openEditModal(@json($product))'>
                                        </flux:button>
                                    </flux:modal.trigger>

                                    <!-- <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        <flux:button icon="trash" variant="ghost" size="sm"
                                                            onclick="return confirm('Delete product?')">
                                                        </flux:button>
                                                    </form> -->
                                    <flux:modal.trigger name="delete-product-{{ $product->id }}">
                                        <flux:button icon="trash" variant="danger" size="sm"></flux:button>
                                    </flux:modal.trigger>

                                </div>
                            </td>

                        </tr>
                        <flux:modal name="edit-product-{{ $product->id }}">
                            @error('name')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                            <div class="space-y-4">

                                <flux:heading size="lg">Edit Product</flux:heading>

                                <form action="{{ route('admin.products.update', $product->id) }}" method="POST"
                                    enctype="multipart/form-data" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    {{-- NAME --}}
                                    <div>
                                        <flux:label>Name</flux:label>
                                        <flux:input name="name" value="{{ old('name', $product->name) }}" />
                                    </div>

                                    {{-- DESCRIPTION --}}
                                    <div>
                                        <flux:label>Description</flux:label>
                                        <flux:textarea name="description">
                                            {{ old('description', $product->description) }}
                                        </flux:textarea>
                                    </div>

                                    {{-- PRICE --}}
                                    <div>
                                        <flux:label>Price</flux:label>
                                        <flux:input type="number" name="price"
                                            value="{{ old('price', $product->price) }}" />
                                    </div>

                                    {{-- STOCK --}}
                                    <div>
                                        <flux:label>Stock</flux:label>
                                        <flux:input type="number" name="stock"
                                            value="{{ old('stock', $product->stock) }}" />
                                    </div>

                                    {{-- IMAGE --}}
                                    <div>
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="w-20 h-20 object-cover rounded">
                                        @endif
                                        <flux:label>Image (optional)</flux:label>
                                        <input value="{{ old('image', asset('storage/' . $product->image)) }}" type="file"
                                            name="image">
                                    </div>

                                    {{-- ACTIVE --}}
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                        <span>Active</span>
                                    </div>

                                    {{-- ACTION --}}
                                    <div class="flex justify-end gap-2">
                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="primary">
                                            Update
                                        </flux:button>
                                    </div>

                                </form>

                            </div>

                        </flux:modal>
                        <flux:modal name="delete-product-{{ $product->id }}">

                            <div class="space-y-4">

                                <flux:heading size="lg">Delete Product</flux:heading>

                                <p class="text-sm text-gray-600">
                                    Yakin mau hapus <b>{{ $product->name }}</b>?
                                    Ini gak bisa dibalikin 😬
                                </p>

                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <div class="flex justify-end gap-2">
                                        <flux:modal.close>
                                            <flux:button variant="ghost">Cancel</flux:button>
                                        </flux:modal.close>

                                        <flux:button type="submit" variant="danger">
                                            Yes, Delete
                                        </flux:button>
                                    </div>
                                </form>

                            </div>

                        </flux:modal>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-10 text-zinc-500">
                                No products yet 🍃
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>

        </div>

    </div>

</x-layouts.app>