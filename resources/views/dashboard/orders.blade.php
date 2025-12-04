<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Your orders are here.
                </div>


            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($orders as $order)
                <div
                    class="bg-white border border-slate-200 rounded-xl mt-3 shadow-sm hover:shadow-md transition-all duration-200 p-5 max-w-sm">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-bold text-slate-800">Order #{{ $order->id }}</span>
                        <span class="text-xs text-slate-500 font-medium">{{ $order->created_at->format('M d, Y')
                            }}</span>
                    </div>

                    <div class="flex justify-between items-end mb-4">
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-wide mb-1">Total</p>
                            <span class="text-2xl font-bold text-emerald-600">${{ $order->total_amount }}</span>
                        </div>
                        <div class="text-right">
                            <a href="" class="underline text-slate-400 mb-1">View Order</a>

                            
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-400">Status</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            <span class="w-1.5 h-1.5 bg-amber-500 rounded-full mr-1.5"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>