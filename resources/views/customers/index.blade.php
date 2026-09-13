<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-section-header font-bold text-slate-900 tracking-tight">
                    Customer Directory
                </h2>
                <p class="text-micro-label text-slate-600 mt-1">Manage customer profiles, vehicles, and purchase history.</p>
            </div>
            <button onclick="document.getElementById('newCustomerModal').classList.remove('hidden')" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2 rounded-lg text-table-body shadow-sm">
                Add Customer
            </button>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-900 text-white p-4 rounded-lg text-table-body font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-300">
                <form action="{{ route('customers.index') }}" method="GET" class="flex gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search customer by name, phone, vehicle #, GSTIN..." class="flex-1 text-table-body border-slate-300 rounded-lg px-3 py-2 focus:ring-slate-900 focus:border-slate-900">
                    <button type="submit" class="bg-slate-900 text-white font-bold px-4 py-2 rounded-lg text-table-body hover:bg-slate-800">Search</button>
                </form>
            </div>

            <!-- Customers Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-table-body">
                        <thead class="bg-slate-900 text-white text-micro-label uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Customer Name</th>
                                <th class="px-4 py-3">Phone & Email</th>
                                <th class="px-4 py-3">Vehicle #</th>
                                <th class="px-4 py-3">GSTIN</th>
                                <th class="px-4 py-3 text-center">Total Invoices</th>
                                <th class="px-4 py-3 text-right">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($customers as $customer)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900 text-table-body">{{ $customer->name }}</div>
                                        <div class="text-micro-label text-slate-600 max-w-xs truncate">{{ $customer->address ?? 'No address' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900 text-table-body">{{ $customer->phone }}</div>
                                        <div class="text-micro-label text-slate-600">{{ $customer->email ?? '-' }}</div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-micro-label font-bold text-slate-900">
                                        {{ $customer->vehicle_number ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-micro-label text-slate-700">
                                        {{ $customer->gst_number ?? 'Unregistered' }}
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-micro-label font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                            {{ $customer->invoices_count }} bills
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-emerald-700 text-section-header">
                                        ₹{{ number_format($customer->invoices_sum_total_amount ?? 0, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-micro-label text-slate-500">No customers registered yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200">
                    {{ $customers->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>

    <!-- New Customer Modal -->
    <div id="newCustomerModal" class="fixed inset-0 bg-slate-950/70 z-50 flex items-center justify-center hidden p-4">
        <div class="bg-white rounded-xl p-6 max-w-md w-full shadow-2xl space-y-4 border border-slate-300">
            <div class="flex justify-between items-center border-b border-slate-200 pb-3">
                <h3 class="text-section-header font-bold text-slate-900">Add New Customer</h3>
                <button onclick="document.getElementById('newCustomerModal').classList.add('hidden')" class="text-slate-500 hover:text-slate-900 font-bold">Close</button>
            </div>
            
            <form action="{{ route('customers.store') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-micro-label font-bold uppercase text-slate-700 mb-1">Customer Name *</label>
                    <input type="text" name="name" required class="w-full text-table-body border-slate-300 rounded-lg py-2">
                </div>
                <div>
                    <label class="block text-micro-label font-bold uppercase text-slate-700 mb-1">Phone Number *</label>
                    <input type="text" name="phone" required class="w-full text-table-body border-slate-300 rounded-lg py-2">
                </div>
                <div>
                    <label class="block text-micro-label font-bold uppercase text-slate-700 mb-1">Vehicle Number</label>
                    <input type="text" name="vehicle_number" placeholder="KA-01-MJ-4589" class="w-full text-table-body font-mono uppercase border-slate-300 rounded-lg py-2">
                </div>
                <div>
                    <label class="block text-micro-label font-bold uppercase text-slate-700 mb-1">GSTIN</label>
                    <input type="text" name="gst_number" class="w-full text-table-body font-mono border-slate-300 rounded-lg py-2">
                </div>
                <div>
                    <label class="block text-micro-label font-bold uppercase text-slate-700 mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full text-table-body border-slate-300 rounded-lg py-2"></textarea>
                </div>

                <div class="flex justify-end gap-2 pt-3 border-t border-slate-200">
                    <button type="button" onclick="document.getElementById('newCustomerModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 text-slate-800 font-bold rounded-lg text-table-body">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-emerald-700 text-white font-bold rounded-lg text-table-body">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
