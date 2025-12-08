<x-guest-layout>
    <div class="flex flex-col justify-center pt-12 items-center bg-gray-500">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-green-600 text-center text-2xl font-bold mb-4">Payment Successful!</h2>
            <p class="text-gray-700 text-center">Thank you for your purchase. Your transaction has been completed successfully.</p>
            <div class="mt-6 text-center">
                <a href="/" class="text-blue-500 hover:underline">Return to Home</a>
                <a href="{{ route('dashboard') }}" class="text-blue-500 hover:underline">Go to Dashboard</a>
            </div>
        </div>
    </div>
</x-guest-layout>