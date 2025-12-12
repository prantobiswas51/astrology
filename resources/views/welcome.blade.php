<x-guest-layout>
    <div class="max-w-3xl pb-6 min-h-screen mx-auto">
        {{-- Navigation links --}}
        {{-- <section class="nav_tag gap-2 w-full flex justify-between bg-gray-200 p-2 rounded">
            <a href="#birthchart" class="underline">&#8595;Birth Chart</a>
            <a href="#video_response" class="underline">&#8595;Video Response</a>
            <a href="#phone_call" class="underline">&#8595;Phone Call</a>
            <a href="#travel_chart" class="underline">&#8595;Travel Chart</a>
        </section> --}}

        {{-- Hero Section --}}


        <section class="">
            <img src="{{ asset('images/mari_profile.png') }}"
                class="w-40 rounded-full mx-auto bg-gray-300 mt-6 relative z-20">
            <div class="des bg-white p-4 m-4 mt-[-30px] drop-shadow shadow-lg rounded-xl relative z-10">

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
            <p class="text-center text-lg mt-3">Digital downloads are instant & final — please choose carefully 💜</p>

        </section>

        {{-- pdf products --}}
        <section class="pdf_products bg-gradient-to-b from-purple-300 to-amber-300 p-2 m-4 rounded-lg my-10">
            <h2 class="text-xl font-bold text-center my-6">Get your done for you readings here! No waiting!</h2>

            {{-- product 1 --}}
            <div
                class="products_grid grid grid-cols-1  gap-6 max-w-7xl mx-auto px-4 pb-6">

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">Your Zodiac Sign Compatibility E-Book</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $30</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <a href="{{ route('product_view', ['slug' => 'zodiac-compatibility-e-books']) }}">
                        <button
                            class="mt-auto w-full bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                            Now</button></a>
                </div>

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">How to make them Obsessed Using Astrology E-Book</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $30</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <a href="{{ route('product_view', ['slug' => 'love-and-obsession']) }}"><button
                            class="mt-auto w-full bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                            Now</button></a>
                </div>

                {{-- Product Item --}}
                <div
                    class="product_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">
                    <h3 class="text-xl font-semibold mb-2">The Love & Obsession and Zodiac Compatibility Bundle</h3>
                    <p class="text-sky-600 drop-shadow text-md font-semibold mb-4">Who’s your soulmate—and who’s your
                        mistake? Find out in your sign’s guide.</p>
                    <h2 class="text-2xl font-extrabold drop-shadow text-sky-800">#E-Book - $50</h2>
                    <h2 class="py-2">Ready To Download</h2>
                    <a href="{{ route('product_view', ['slug' => 'love-and-obsession-zodiac-compatibility-bundle']) }}">
                        <button
                            class="mt-auto w-full bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors">Download
                            Now</button></a>
                </div>
            </div>

        </section>

        {{-- Simple readings --}}

        <section class="simple_readings ">
            <h2 class="text-2xl font-bold text-center text-gray-100 my-6">Personal Astro Readings ⬇️</h2>
            <div class="grid grid-cols-1  gap-6 max-w-7xl mx-auto px-4 pb-6">


                <div id="birthchart_area" class="p-2 max-h-10 font-bold text-center bg-white rounded-full">
                    Birth Chart Readings here ⬇️
                </div>
                {{-- Birth Chart reading --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Birth Chart Reading</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Unlock the secrets of your destiny with a personalized birth chart reading!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$35</del> <span class="text-gray-800">$30</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'birth-chart-reading']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Birth Chart reading and phone call --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Birth Chart Reading and Phone Call</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Get a personal birth chart + a call with me! Easy, luxe, and perfect for your starry
                                journey
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$35</del> <span class="text-gray-800">$50</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'birthchart-reading-and-phone-call']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Birth Chart Compatibility reading --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Birth Chart Compatibility Reading</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Discover the connection between you and your partner with a birth chart compatibility
                                reading!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$70</del> <span class="text-gray-800">$60</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'birth-chart-compatibility-reading']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Human Design call --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Human Design Chart</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Unlock your potential with a personalized human design chart reading!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$70</del> <span class="text-gray-800">$30</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'human-design-chart-reading']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Birth Chart Madness --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Birth Chart MADNESS!</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Get every birth chart reading, plus a secret bonus!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$120</del> <span class="text-gray-800">$90</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'birth-chart-madness']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>



                <div id="video_area" class="p-2 max-h-10 font-bold text-center bg-white rounded-full">
                    Video Response Readings here ⬇️
                </div>
                {{-- Video Response reading --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Video Response Reading</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Ask anything, 1 long question! Get a personalized video + text response tailored to you!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$35</del> <span class="text-gray-800">$30</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'astro-video-response']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Video Response reading Plus --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Video Response Reading Plus</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Ask 2 question of your choice, make it as long as a detailed as you’d like even if it’s
                                a BOOK
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$60</del> <span class="text-gray-800">$50</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'video-response-reading-plus']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>



                <div id="phonecall_area" class="p-2 max-h-10 font-bold text-center bg-white rounded-full">
                    Phone Call Readings here ⬇️
                </div>
                {{-- Astro phone call --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Phone Call Reading</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Hop on a 1on1 call with me and ask me anything astrology related!
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$70</del> <span class="text-gray-800">$30</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'phone-call-reading']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>

                {{-- Personal Astro Therapy --}}
                <div
                    class="reading_item bg-white border border-black rounded-lg shadow-lg p-4 flex flex-col hover:scale-105 transition-transform">

                    <div class="flex">
                        <img src="{{ asset('images/ph_product.png') }}" alt="Product Image"
                            class="min-w-32 min-h-32 max-w-32 max-h-32 mr-3 object-cover bg-gray-200 rounded-lg">

                        <div>
                            <h3 class="text-xl font-semibold mb-1">Personal Astrologer Therapy!</h3>
                            <p class="text-sky-600 drop-shadow text-sm font-semibold mb-2">
                                Where I call you for the month! Some people need more than just one 30 min phone call
                            </p>

                            <h2 class="font-bold text-lg text-sky-800/70">
                                <del>$120</del> <span class="text-gray-800">$90</span>
                            </h2>
                        </div>
                    </div>

                    <a href="{{ route('product_view', ['slug' => 'personal-astrology-therapy']) }}">
                        <button
                            class="mt-4 bg-gray-900 font-extrabold py-2 px-4 rounded-lg text-yellow-300 hover:bg-gray-800 transition-colors w-full">
                            Get Your Reading Now
                        </button>
                    </a>

                </div>


            </div>
        </section>


    </div>

</x-guest-layout>