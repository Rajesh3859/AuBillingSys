<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-section-header font-bold text-slate-900 tracking-tight">
                    Automobile Spares Dashboard
                </h2>
                <p class="text-micro-label text-slate-600 mt-0.5">Real-time inventory metrics, daily revenue overview, and stock alerts.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('billing.index') }}" class="inline-flex items-center bg-emerald-700 hover:bg-emerald-800 text-white px-4 py-2 rounded-lg font-bold shadow-sm transition-all text-table-body">
                    New POS Bill
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- KPI Cards Banner (Scaled down for compact view across mobile, tablet & desktop) -->
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
                
                <!-- Today Sales -->
                <div class="bg-white p-3.5 sm:p-5 rounded-xl shadow-sm border border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-micro-label font-bold text-slate-700 uppercase tracking-wider">Today's Sales</span>
                        <span class="text-micro-label font-bold text-emerald-800 bg-emerald-50 px-1.5 sm:px-2 py-0.5 rounded border border-emerald-300">DAILY</span>
                    </div>
                    <div class="text-kpi-banner text-slate-900 mt-2 sm:mt-3 font-bold">
                        ₹{{ number_format($todaySales, 2) }}
                    </div>
                    <div class="text-micro-label text-slate-600 font-medium mt-1.5 sm:mt-2 truncate">
                        Updated live from terminal
                    </div>
                </div>

                <!-- Monthly Revenue -->
                <div class="bg-white p-3.5 sm:p-5 rounded-xl shadow-sm border border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-micro-label font-bold text-slate-700 uppercase tracking-wider">Monthly Sales</span>
                        <span class="text-micro-label font-bold text-blue-800 bg-blue-50 px-1.5 sm:px-2 py-0.5 rounded border border-blue-300">MONTHLY</span>
                    </div>
                    <div class="text-kpi-banner text-slate-900 mt-2 sm:mt-3 font-bold">
                        ₹{{ number_format($monthlySales, 2) }}
                    </div>
                    <div class="text-micro-label text-slate-600 font-medium mt-1.5 sm:mt-2 truncate">
                        Current month accumulation
                    </div>
                </div>

                <!-- Total Invoices -->
                <div class="bg-white p-3.5 sm:p-5 rounded-xl shadow-sm border border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-micro-label font-bold text-slate-700 uppercase tracking-wider">Total Bills</span>
                        <span class="text-micro-label font-bold text-slate-800 bg-slate-100 px-1.5 sm:px-2 py-0.5 rounded border border-slate-300">COUNT</span>
                    </div>
                    <div class="text-kpi-banner text-slate-900 mt-2 sm:mt-3 font-bold">
                        {{ $totalInvoicesCount }}
                    </div>
                    <div class="text-micro-label text-slate-600 font-medium mt-1.5 sm:mt-2 truncate">
                        Across {{ $totalCustomersCount }} registered customers
                    </div>
                </div>

                <!-- Total Inventory Spares -->
                <div class="bg-white p-3.5 sm:p-5 rounded-xl shadow-sm border border-slate-300">
                    <div class="flex items-center justify-between">
                        <span class="text-micro-label font-bold text-slate-700 uppercase tracking-wider">Stock Catalog</span>
                        <span class="text-micro-label font-bold text-slate-800 bg-slate-100 px-1.5 sm:px-2 py-0.5 rounded border border-slate-300">PARTS</span>
                    </div>
                    <div class="text-kpi-banner text-slate-900 mt-2 sm:mt-3 font-bold">
                        {{ $totalSparesCount }} <span class="text-table-body font-normal text-slate-600">parts</span>
                    </div>
                    <div class="text-micro-label mt-1.5 sm:mt-2 truncate">
                        @if($lowStockParts->count() > 0)
                            <span class="text-rose-700 font-bold">{{ $lowStockParts->count() }} Spares Low</span>
                        @else
                            <span class="text-emerald-700 font-bold">Stock Levels Healthy</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Main Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Left 2 Cols -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Low Stock Alert Banner -->
                    @if($lowStockParts->count() > 0)
                        <div class="bg-rose-50 p-5 rounded-xl shadow-sm border-2 border-rose-600">
                            <div class="flex items-center justify-between mb-4 border-b border-rose-200 pb-3">
                                <div>
                                    <h3 class="text-section-header font-bold text-rose-950">Low Stock Alert</h3>
                                    <p class="text-micro-label text-rose-800 font-semibold">Spare parts at or below reorder threshold</p>
                                </div>
                                <a href="{{ route('spares.index', ['stock_status' => 'low']) }}" class="text-micro-label bg-rose-700 text-white px-3.5 py-1.5 rounded-lg font-bold hover:bg-rose-800 transition-colors">
                                    Manage Inventory →
                                </a>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-table-body">
                                    <thead class="bg-rose-900 text-white text-micro-label uppercase font-bold tracking-wider">
                                        <tr>
                                            <th class="px-3 py-2.5">Part #</th>
                                            <th class="px-3 py-2.5">Part Name</th>
                                            <th class="px-3 py-2.5">Brand</th>
                                            <th class="px-3 py-2.5 text-center">Remaining</th>
                                            <th class="px-3 py-2.5 text-center">Reorder Level</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-rose-200 bg-white">
                                        @foreach($lowStockParts as $part)
                                            <tr class="hover:bg-rose-50/60 transition-colors">
                                                <td class="px-3 py-2.5 font-mono text-micro-label font-bold text-slate-900">{{ $part->part_number }}</td>
                                                <td class="px-3 py-2.5 text-table-body font-semibold text-slate-900">{{ $part->name }}</td>
                                                <td class="px-3 py-2.5 text-micro-label text-slate-800 font-medium">{{ $part->brand }}</td>
                                                <td class="px-3 py-2.5 text-center font-bold text-rose-700 text-table-body">{{ $part->stock_quantity }}</td>
                                                <td class="px-3 py-2.5 text-center text-micro-label text-slate-800 font-medium">{{ $part->reorder_level }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Transactions -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-300">
                        <div class="flex items-center justify-between mb-4 pb-3 border-b border-slate-200">
                            <div>
                                <h3 class="text-section-header font-bold text-slate-900">Recent Transactions</h3>
                                <p class="text-micro-label text-slate-600">Latest generated customer invoices</p>
                            </div>
                            <a href="{{ route('invoices.index') }}" class="text-micro-label font-bold text-emerald-800 hover:text-emerald-900">
                                View All Invoices →
                            </a>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-table-body">
                                <thead class="bg-slate-900 text-white text-micro-label uppercase font-bold tracking-wider">
                                    <tr>
                                        <th class="px-4 py-2.5">Invoice #</th>
                                        <th class="px-4 py-2.5">Customer</th>
                                        <th class="px-4 py-2.5">Vehicle #</th>
                                        <th class="px-4 py-2.5 text-right">Amount</th>
                                        <th class="px-4 py-2.5 text-center">Status</th>
                                        <th class="px-4 py-2.5 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200">
                                    @forelse($recentInvoices as $inv)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3 font-mono font-bold text-slate-900 text-table-body">{{ $inv->invoice_number }}</td>
                                            <td class="px-4 py-3 text-table-body font-semibold text-slate-900">
                                                {{ $inv->customer_name }}
                                            </td>
                                            <td class="px-4 py-3 text-micro-label font-mono font-semibold text-slate-800">
                                                {{ $inv->vehicle_number ?? 'N/A' }}
                                            </td>
                                            <td class="px-4 py-3 text-right font-bold text-slate-900 text-table-body">
                                                ₹{{ number_format($inv->total_amount, 2) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-micro-label font-bold uppercase bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                    {{ $inv->payment_status }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <a href="{{ route('invoices.show', $inv->id) }}" class="text-micro-label bg-slate-900 text-white font-bold px-3 py-1 rounded hover:bg-slate-800">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-4 py-8 text-center text-micro-label text-slate-600 font-medium">No transactions recorded yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Right 1 Col -->
                <div class="space-y-6">

                    <!-- Quick POS Banner -->
                    <div class="bg-slate-900 text-white p-6 rounded-xl shadow-md border border-slate-800">
                        <span class="text-micro-label uppercase font-bold text-emerald-400 bg-slate-800 px-2.5 py-1 rounded border border-slate-700">POS Billing Terminal</span>
                        <h3 class="text-section-header font-bold mt-3 text-white">Issue Spares Invoice</h3>
                        <p class="text-micro-label text-slate-200 mt-1">Scan or search spare parts, calculate GST, and issue instant bills.</p>
                        
                        <a href="{{ route('billing.index') }}" class="mt-5 w-full inline-flex justify-center items-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2.5 rounded-lg transition-all text-table-body uppercase tracking-wider">
                            Launch POS Terminal
                        </a>
                    </div>

                    <!-- Fast Moving Parts -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-300">
                        <h3 class="text-section-header font-bold text-slate-900 mb-4 pb-2 border-b border-slate-200">Fast Moving Parts</h3>
                        <div class="space-y-3">
                            @foreach($topSpares as $spare)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 hover:bg-slate-100 transition-colors border border-slate-200">
                                    <div>
                                        <div class="text-table-body font-bold text-slate-900">{{ $spare->name }}</div>
                                        <div class="text-micro-label font-mono text-slate-700 font-medium">{{ $spare->part_number }} • {{ $spare->brand }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-emerald-800 text-table-body">₹{{ number_format($spare->unit_price, 2) }}</div>
                                        <div class="text-micro-label text-slate-700 font-medium">Stock: <span class="font-bold text-slate-900">{{ $spare->stock_quantity }}</span></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
