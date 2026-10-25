<footer class="mt-auto w-full bg-gradient-to-r from-indigo-950 via-purple-900 to-indigo-950 text-indigo-100 border-t border-amber-300/30">
    <div class="max-w-6xl mx-auto px-4 py-6 flex flex-col md:flex-row gap-4 justify-between items-center text-sm">
        <div class="text-center md:text-left">
            <span class="text-amber-300">&#10022;</span> &copy; {{ date('Y') }} AstrologyByMari. All rights reserved.
            <span class="block md:inline md:ml-2 text-indigo-300">Design and Developed by
                <a class="font-semibold text-amber-300 hover:text-amber-200 underline underline-offset-2"
                    href="https://pranto.bongomaker.com">Pranto Biswas</a></span>
        </div>

        <ul class="flex flex-wrap justify-center gap-x-5 gap-y-2">
            <li><a class="hover:text-amber-300 transition-colors" href="{{ route('home') }}">Home</a></li>
            <li><a class="hover:text-amber-300 transition-colors" href="{{ route('shop') }}">Shop</a></li>
            <li><a class="hover:text-amber-300 transition-colors" href="{{ route('terms_conditions') }}">Terms and Conditions</a></li>
            <li><a class="hover:text-amber-300 transition-colors" href="{{ route('privacy_policy') }}">Privacy Policy</a></li>
        </ul>
    </div>
</footer>
