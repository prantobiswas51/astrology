<x-guest-layout>
    <div class="bg-sky-300 pb-6 min-h-screen">
        {{-- Navigation links --}}
        <section class="nav_tag gap-2 w-full flex justify-between bg-gray-200 p-2 rounded">
            <a href="#birthchart" class="underline">Birth Chart</a>
            <a href="#birthchart" class="underline">Video Response</a>
            <a href="#birthchart" class="underline">Phone Call</a>
            <a href="#birthchart" class="underline">Travel Chart</a>
        </section>

        {{-- Hero Section --}}
        <section class="hero_section ">
            <img src="{{ asset('images/mari_profile.png') }}" class="w-40 rounded-full mx-auto bg-gray-300 mt-6"
                alt="hero image">
            <div class="des bg-white p-4 m-4 mt-[-30px] drop-shadow shadow-lg rounded-xl">
                <h1 class="text-3xl font-bold text-center mt-4">Mari Astrology</h1>
                <h2 class="text-center  text-xl mt-2">Astrologer reader! At your service</h2>
                <div class="text-center italic font-mono font-semibold text-lg">*whispers* I am not a psychic lol</div>
            </div>
        </section>

        {{-- Title section --}}
        <div class="title_section text-center mt-6">
            <h2 class="text-xl font-bold bg-white rounded-full m-4 p-2">
                Instant Astrology,
                <span class="text-green-600">Instant Answers—No Wait.</span> Start Reading Now.
            </h2>
        </div>

        {{-- ann section --}}
        <section
            class="ann_section justify-center flex flex-col bg-white bg-gradient-to-b from-purple-300 py-10 px-6 to-amber-300 m-4 p-4 rounded-lg drop-shadow shadow-lg mt-6">
            <h3 class="text-xl text-center font-semibold my-2">Want your reading right now?</h3>
            <h3 class="text-xl text-center font-semibold my-2">Instant e-books + chart guides are finally here.</h3>
            <button class="bg-gray-900 text-white p-2 mt-6 rounded-lg px-5">Get your done for you readings here! No
                waiting!</button>
        </section>

        {{-- pdf products --}}
        <section class="pdf_products bg-gradient-to-b from-purple-300 to-amber-300 p-2 m-4 rounded-lg my-10">
            <h2 class="text-xl font-bold text-center my-6">Get your done for you readings here! No waiting!</h2>

            {{-- product 1 --}}
            <div
                class="products_grid grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 max-w-7xl mx-auto px-4 pb-6">

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">Your Zodiac Sign Compatibility E-Book</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $30</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <button
                        class="mt-auto bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                        Now</button>
                </div>

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">Your Zodiac Sign Compatibility E-Book</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $30</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <button
                        class="mt-auto bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                        Now</button>
                </div>

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">Your Zodiac Sign Compatibility E-Book</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $30</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <button
                        class="mt-auto bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                        Now</button>
                </div>
            </div>
        </section>

    </div>

</x-guest-layout>