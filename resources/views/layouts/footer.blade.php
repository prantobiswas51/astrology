<div class="bg-light text-center text-lg-start bg-gray-900 text-white w-full mt-auto">
    <div class="max-w-6xl flex py-4 flex-col md:flex-row justify-between items-center mx-auto px-4 w-full">
        <div class="text-center">
            © {{ date('Y') }} AstrologyByMari. All rights reserved. <span>Design and Developed by <a class="font-bold underline" href="https://pranto.bongomaker.com">Pranto Biswas</a></span>
        </div>
        {{-- footer menu --}}

        <ul class="flex justify-center space-x-4">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('shop') }}">Shop</a></li>
            <li><a href="{{ route('terms_conditions') }}">Terms and Conditions</a></li>
            <li><a href="{{ route('privacy_policy') }}">Privacy Policy</a></li>
        </ul>
    </div>
</div>