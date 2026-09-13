<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                    Invoices & Sales History
                </h2>
                <p class="text-xs text-slate-500 mt-1">View, search, and reprint generated customer invoices.</p>
            </div>
            <a href="{{ route('billing.index') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-sm shadow-sm">
                New Invoice
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
                <form action="{{ route('invoices.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by invoice #, customer name, phone, vehicle #..." class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                    </div>
                    <div>
                        <select name="payment_status" class="w-full text-sm border-slate-200 rounded-xl px-3 py-2 focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">All Payment Statuses</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="partial" {{ request('payment_status') == 'partial' ? 'selected' : '' }}>Partial</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-slate-900 text-white font-bold px-4 py-2 rounded-xl text-sm hover:bg-slate-800">Search Invoices</button>
                </form>
            </div>

            <!-- Invoices Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-slate-900 text-white text-xs uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Invoice # / Date</th>
                                <th class="px-4 py-3">Customer & Phone</th>
                                <th class="px-4 py-3">Vehicle #</th>
                                <th class="px-4 py-3 text-right">Tax (GST 18%)</th>
                                <th class="px-4 py-3 text-right">Total Amount</th>
                                <th class="px-4 py-3 text-center">Status / Mode</th>
                                <th class="px-4 py-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($invoices as $inv)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="font-mono font-bold text-slate-900">{{ $inv->invoice_number }}</div>
                                        <div class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($inv->invoice_date)->format('d M Y') }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900">{{ $inv->customer_name }}</div>
                                        <div class="text-xs text-slate-500">{{ $inv->customer_phone ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-xs text-slate-700">
                                        {{ $inv->vehicle_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-medium text-slate-600">
                                        ₹{{ number_format($inv->tax_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-black text-slate-900 text-base">
                                        ₹{{ number_format($inv->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold capitalize bg-emerald-100 text-emerald-800">
                                            {{ $inv->payment_status }}
                                        </span>
                                        <div class="text-[10px] uppercase text-slate-400 font-bold mt-0.5">{{ $inv->payment_method }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-1">
                                        <a href="{{ route('invoices.show', $inv->id) }}" class="text-xs bg-slate-900 text-white font-bold px-2.5 py-1 rounded-lg hover:bg-slate-800">
                                            View
                                        </a>
                                        <a href="{{ route('invoices.print', $inv->id) }}" target="_blank" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold px-2.5 py-1 rounded-lg">
                                            Print
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-slate-400">No invoices recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-100">
                    {{ $invoices->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
