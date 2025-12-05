<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>

    <div class="py-12 mx-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 gap-4 justify-center sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($orders as $order)
                <div
                    class="bg-white border border-slate-200 rounded-2xl mt-3 shadow-sm hover:shadow-lg transition-all duration-200 p-6 max-w-sm">

                    {{-- Header --}}
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-slate-800 text-lg">Order #{{ $order->id }}</span>
                        <span class="text-xs text-slate-500 font-medium">{{ $order->created_at->format('M d, Y')
                            }}</span>
                    </div>

                    {{-- Total + Type Badge --}}
                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total</p>
                            <span class="text-2xl font-bold text-emerald-600">${{ $order->total_amount }}</span>
                        </div>

                        <div class="text-right">
                            @php $firstItem = $order->orderItems->first(); @endphp

                            @if($firstItem)
                            <span class="text-xs font-semibold px-2 py-1 rounded-full whitespace-nowrap
                            @if($firstItem->product->type == 'digital')
                                bg-blue-100 text-blue-700
                            @else
                                bg-slate-100 text-slate-600
                            @endif">
                                {{ ucfirst($firstItem->product->type) }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Order Items --}}
                    <div class="mt-3 text-sm text-slate-700 space-y-3">
                        @foreach ($order->orderItems as $item)
                        <div class="p-4 border rounded-xl bg-slate-50 shadow-sm">

                            {{-- Product Name & Price --}}
                            <div class="flex justify-between mb-2">
                                <strong class="text-slate-800">{{ $item->product->name }}</strong>
                                <span class="text-slate-700 font-semibold">${{ number_format($item->price, 2) }}</span>
                            </div>

                            {{-- DIGITAL PRODUCT --}}
                            @if($item->product->type === 'digital')
                            @php
                            $extra = json_decode($item->extra_information ?? '[]', true) ?: [];
                            $files = [];
                            if (isset($extra['file_ids'])) {
                            $files = \App\Models\ProductFile::whereIn('id', $extra['file_ids'])->get();
                            }
                            @endphp

                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($files as $file)
                                <a href="{{ asset($file->file_path) }}" download
                                    class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition">
                                    Download {{ $file->file_name }}
                                </a>
                                @endforeach
                            </div>

                            {{-- NON-DIGITAL --}}
                            @else
                            @php $extra = json_decode($item->extra_information, true); @endphp

                            @if(is_array($extra) && !empty($extra))
                            <div class="mt-2 text-xs text-slate-600 space-y-1">
                                @foreach($extra as $key => $val)
                                @if($key === 'file_ids')
                                @continue
                                @endif
                                @php if (is_array($val)) { $val = implode(', ', $val); } @endphp
                                @if(!empty($val))
                                <div><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $val }}</div>
                                @endif
                                @endforeach
                            </div>
                            @else
                            <p class="text-xs text-slate-500 mt-2">No extra information</p>
                            @endif

                            @endif
                        </div>
                        @endforeach
                    </div>

                    {{-- Footer Status --}}
                    <div class="pt-4 mt-3 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Status</span>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium whitespace-nowrap
                        @if($order->status === 'Pending') 
                            bg-amber-100 text-amber-800
                        @elseif($order->status === 'Failed')
                            bg-red-100 text-red-800
                        @else
                            bg-green-100 text-green-800
                        @endif">
                            <span class="w-2 h-2 mr-1.5 rounded-full
                            @if($order->status === 'Pending') bg-amber-500
                            @elseif($order->status === 'Failed') bg-red-500
                            @else bg-green-500 @endif"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>