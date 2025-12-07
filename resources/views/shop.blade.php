<x-guest-layout>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Shop</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="border rounded-lg p-4 shadow bg-sky-100">
                    <img src="{{ asset('storage/' . $product->image1_path) }}" alt="{{ $product->name }}" class="w-full h-auto max-h-[260px] object-cover mb-4">
                    <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-4">${{ number_format($product->price, 2) }}</p>
                    <a href="{{ route('product_view', $product->slug) }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">View Product</a>
                </div>
            @endforeach
        </div>
    </div>

</x-guest-layout>