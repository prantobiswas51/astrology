<nav class="bg-white border-b bg-sky-100 border-gray-100">

    <style>
        /* Hide navigation links on small screens */
        @media (max-width: 640px) {

            .nav-link,
            .desktop_link {
                display: none;
            }
        }

        /* Replace hidden and responsive visibility classes */
        .hidden {
            display: none;
        }

        .block {
            display: block;
        }

        @media (min-width: 640px) {
            .sm\\:hidden {
                display: none;
            }

            .sm\\:flex {
                display: flex;
            }

            .sm\\:-my-px {
                margin-top: -1px;
                margin-bottom: -1px;
            }

            .sm\\:ms-10 {
                margin-left: 2.5rem;
            }
        }
    </style>

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <div class="flex items-center justify-between w-full">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('home') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Nav Links -->
                <div class="p-2 nav-link flex items-center space-x-4">
                    <a class="p-2" href="{{ route('home') }}">Home</a>
                    <a class="p-2" href="{{ route('home') }}">Shop</a>
                    <a class="p-2" href="{{ route('home') }}">Cart</a>
                    <a class="p-2" href="{{ route('home') }}">Privacy Policy</a>
                    <a class="p-2" href="{{ route('home') }}">Terms & Conditions</a>
                </div>

                <!-- Dashboard (desktop only) -->
                <div class="desktop_link space-x-8 sm:-my-px sm:ms-10">
                    @auth
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}"
                            class="text-gray-700 hover:text-black font-medium">Dashboard</a>
                        <form action="" method="post">
                            @csrf
                            <button formaction="{{ route('logout') }}"
                                class="px-4 py-2 rounded-lg font-medium hover:underline">Logout</button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="text-black font-medium">Sign In</a>
                    <a href="{{ route('register') }}"
                        class="bg-black text-white px-6 py-2 rounded-lg font-medium hover:bg-gray-800">Register</a>
                    @endauth
                </div>
            </div>

            <!-- Hamburger (mobile only) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button id="menu-toggle"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg id="menu-icon" class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div id="responsive-menu" class="hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                {{ __('Home') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('cart')" :active="request()->routeIs('cart')">
                {{ __('Cart') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('privacy_policy')" :active="request()->routeIs('privacy_policy')">
                {{ __('Privacy Policy') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('terms_conditions')" :active="request()->routeIs('terms_conditions')">
                {{ __('Terms & Conditions') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    @auth
                    {{ Auth::user()->name }}
                    @endauth
                </div>
                <div class="font-medium text-sm text-gray-500">
                    @auth
                    {{ Auth::user()->email }}
                    @endauth
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('menu-toggle');
        const menu = document.getElementById('responsive-menu');
        const icon = document.getElementById('menu-icon');

        toggleBtn.addEventListener('click', () => {
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                `;
            } else {
                menu.classList.add('hidden');
                icon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                `;
            }
        });
    </script>

</nav>