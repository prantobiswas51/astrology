<x-guest-layout>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl mx-auto mt-16 p-8 text-center">

        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            ✨ Astrology by Mari ✨
        </h2>

        <div class="space-y-4">

            <!-- Email -->
            <a href="mailto:support@astrologybymari.com"
                class="flex items-center justify-center gap-3 w-full py-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 transition">
                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-18 8h18a2 2 0 002-2V6a2 2 0 00-2-2H3a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="font-medium text-gray-700">
                    Email Support
                </span>
            </a>



            <!-- Instagram -->
            <a href="https://instagram.com/astrologybymari" target="_blank"
                class="flex items-center justify-center gap-3 w-full py-3 rounded-xl bg-pink-50 hover:bg-pink-100 transition">
                <svg class="w-5 h-5 text-pink-600" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M7.75 2h8.5A5.75 5.75 0 0122 7.75v8.5A5.75 5.75 0 0116.25 22h-8.5A5.75 5.75 0 012 16.25v-8.5A5.75 5.75 0 017.75 2zm8.9 2.5H7.35A2.85 2.85 0 004.5 7.35v9.3a2.85 2.85 0 002.85 2.85h9.3a2.85 2.85 0 002.85-2.85v-9.3a2.85 2.85 0 00-2.85-2.85zM12 7a5 5 0 110 10 5 5 0 010-10zm0 2.2a2.8 2.8 0 100 5.6 2.8 2.8 0 000-5.6zm5.1-.9a1.1 1.1 0 11-2.2 0 1.1 1.1 0 012.2 0z" />
                </svg>
                <span class="font-medium text-gray-700">
                    Instagram
                </span>
            </a>

            <!-- TikTok -->
            <a href="https://www.tiktok.com/@astrologybymari" target="_blank"
                class="flex items-center justify-center gap-3 w-full py-3 rounded-xl bg-gray-100 hover:bg-gray-200 transition">
                <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M16.7 5.3a5.1 5.1 0 01-3.6-1.5V14a4.5 4.5 0 11-4.5-4.5c.3 0 .6 0 .9.1v2.4a2.1 2.1 0 10 1.5 2V2h2.2a5.1 5.1 0 004 4v1.9z" />
                </svg>
                <span class="font-medium text-gray-700">
                    TikTok
                </span>
            </a>

            <p>Alternative : astrologybymari@gmail.com</p>

        </div>

        <div class="mt-8 text-xs text-gray-400">
            © {{ date('Y') }} Astrology by Mari
        </div>

    </div>
</x-guest-layout>