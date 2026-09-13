<!-- Sidebar Navigation Component -->
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
       class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white flex flex-col justify-between transition-transform duration-300 ease-in-out border-r border-slate-800 shadow-xl">
    
    <div>
        <!-- Brand / Header -->
        <div class="h-16 px-6 flex items-center justify-between border-b border-slate-800 bg-slate-950">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-base font-bold tracking-tight text-white">
                <span class="text-emerald-400 font-extrabold">AutoSpares</span>
                <span class="text-slate-300 font-normal">Pro</span>
            </a>
            <!-- Mobile Close Button -->
            <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white text-xs font-mono p-1">
                CLOSE
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="px-3 py-6 space-y-1 text-table-body font-medium">
            
            <a href="{{ route('dashboard') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('dashboard') ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                <span>Dashboard</span>
            </a>

            <a href="{{ route('billing.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('billing.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                <span>POS Billing</span>
                <span class="text-micro-label uppercase font-bold px-2 py-0.5 rounded bg-emerald-950 text-emerald-300 border border-emerald-800">POS</span>
            </a>

            <a href="{{ route('spares.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('spares.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                <span>Spares Inventory</span>
            </a>

            <a href="{{ route('invoices.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('invoices.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                <span>Invoices & Sales</span>
            </a>

            <a href="{{ route('customers.index') }}"
               class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-all {{ request()->routeIs('customers.*') ? 'bg-emerald-600 text-white font-semibold shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                <span>Customer Directory</span>
            </a>

        </nav>
    </div>

    <!-- Bottom Action & User Info -->
    <div class="p-4 space-y-4 border-t border-slate-800 bg-slate-950">
        
        <!-- Quick POS Button -->
        <a href="{{ route('billing.index') }}" class="w-full flex items-center justify-center bg-emerald-600 hover:bg-emerald-500 text-white font-semibold py-2.5 px-4 rounded-lg text-micro-label tracking-wide uppercase shadow-sm transition-all">
            New Billing Invoice
        </a>

        <!-- User Profile Card -->
        <div class="flex items-center justify-between pt-2 border-t border-slate-800">
            <div class="flex items-center gap-2.5 overflow-hidden">
                <div class="w-7 h-7 rounded bg-slate-800 text-emerald-400 font-bold flex items-center justify-center text-micro-label shrink-0 border border-slate-700">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="truncate">
                    <div class="text-micro-label font-semibold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <div class="text-[9pt] text-slate-400 truncate">{{ Auth::user()->email ?? 'admin@autospares.com' }}</div>
                </div>
            </div>

            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-micro-label text-slate-400 hover:text-rose-400 font-semibold px-1 py-0.5 rounded transition-colors uppercase">
                    Logout
                </button>
            </form>
        </div>

    </div>
</aside>
