<x-guest-layout>
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-red-600 text-center text-2xl font-bold mb-4">Payment Failed!</h2>
            <p class="text-gray-700 text-center">Unfortunately, your transaction could not be completed. Please try again.</p>
            <div class="mt-6 text-center">
                <a href="/" class="text-blue-500 hover:underline">Return to Home</a>
            </div>
        </div>
    </div>
</x-guest-layout>