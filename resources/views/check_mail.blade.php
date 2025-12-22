<x-guest-layout>
    <div class="max-w-lg w-full mx-auto py-12 slide-up">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Gradient Header -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-8 text-center">
                <svg class="w-12 h-12 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h1 class="text-2xl font-bold text-white mb-1">Email Sent!</h1>
                <p class="text-indigo-100 text-sm">Please check your inbox</p>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="space-y-4 mb-6">
                    <div class="flex items-start gap-3">
                        <div
                            class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                            1</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Open Your Email</p>
                            <p class="text-gray-600 text-xs mt-1">Check inbox for AstrologyByMari</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                            2</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Check SPAM Folder</p>
                            <p class="text-gray-600 text-xs mt-1">Also check spam/junk folder</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div
                            class="w-7 h-7 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">
                            3</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Click Activation Link</p>
                            <p class="text-gray-600 text-xs mt-1">Complete your registration</p>
                        </div>
                    </div>
                </div>

                <a href="{{ route('login') }}">
                    <div class="bg-indigo-500 border text-white border-indigo-100 mt-4 rounded-lg p-4 text-center">
                        Login >
                    </div>
                </a>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 text-center border-t">
                <p class="text-xs text-gray-400">© 2025 AstrologyByMari</p>
            </div>
        </div>

    </div>
</x-guest-layout>