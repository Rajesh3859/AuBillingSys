<nav x-data="{ open: false }" class="bg-slate-900 border-b border-slate-800 text-white shadow-lg sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-6">
                <!-- Logo / Brand -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-xl font-bold tracking-tight text-white">
                        <div class="bg-gradient-to-tr from-amber-500 to-emerald-400 p-2 rounded-lg text-slate-950 font-black shadow-md">
                            ⚡
                        </div>
                        <span>Rajesh <span class="text-emerald-400">Pro</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:flex">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('billing.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        💳 POS Billing
                    </a>
                    <a href="{{ route('spares.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('spares.*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        🔧 Spares Inventory
                    </a>
                    <a href="{{ route('invoices.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        🧾 Invoices & Sales
                    </a>
                    <a href="{{ route('customers.index') }}" class="px-3 py-2 rounded-md text-sm font-medium transition-colors {{ request()->routeIs('customers.*') ? 'bg-slate-800 text-emerald-400 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        👥 Customers
                    </a>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-1.5 border border-slate-700 text-sm leading-4 font-medium rounded-lg text-slate-200 bg-slate-800 hover:bg-slate-700 focus:outline-none transition ease-in-out duration-150">
                            <span class="mr-2">👤</span>
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-t border-slate-800">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">📊 Dashboard</a>
            <a href="{{ route('billing.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('billing.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">💳 POS Billing</a>
            <a href="{{ route('spares.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('spares.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">🔧 Spares Inventory</a>
            <a href="{{ route('invoices.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('invoices.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">🧾 Invoices & Sales</a>
            <a href="{{ route('customers.index') }}" class="block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('customers.*') ? 'bg-slate-800 text-emerald-400' : 'text-slate-300' }}">👥 Customers</a>
        </div>

        <div class="pt-4 pb-3 border-t border-slate-800 px-4">
            <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
            <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            <div class="mt-3 space-y-1">
                <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-2 rounded-md text-sm font-medium text-slate-300 hover:text-white hover:bg-slate-800">Log Out</button>
                </form>
            </div>
        </div>
    </div>
</nav>
