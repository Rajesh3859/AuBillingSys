<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-section-header font-bold text-slate-900 tracking-tight">
                    Automobile Spares Inventory
                </h2>
                <p class="text-micro-label text-slate-600 mt-1">Manage parts catalog, rack locations, pricing, and stock levels.</p>
            </div>
            <a href="{{ route('spares.create') }}" class="inline-flex items-center bg-slate-900 hover:bg-slate-800 text-white px-4 py-2 rounded-lg text-table-body font-bold transition-all shadow-sm">
                Add New Spare Part
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-900 text-white p-4 rounded-lg text-table-body font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Search & Filters -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-300">
                <form action="{{ route('spares.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                    <div class="sm:col-span-2">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by part #, name, brand, compatible models..." class="w-full text-table-body border-slate-300 rounded-lg px-3 py-2 focus:ring-slate-900 focus:border-slate-900">
                    </div>
                    <div>
                        <select name="category_id" class="w-full text-table-body border-slate-300 rounded-lg px-3 py-2 focus:ring-slate-900 focus:border-slate-900 font-medium">
                            <option value="">All Categories</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <select name="stock_status" class="w-full text-table-body border-slate-300 rounded-lg px-3 py-2 focus:ring-slate-900 focus:border-slate-900 font-medium">
                            <option value="">All Stock Levels</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        <button type="submit" class="bg-slate-900 text-white font-bold px-4 py-2 rounded-lg text-table-body hover:bg-slate-800">Filter</button>
                    </div>
                </form>
            </div>

            <!-- Spares Table -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-300 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-table-body">
                        <thead class="bg-slate-900 text-white text-micro-label uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3">Part # / Rack</th>
                                <th class="px-4 py-3">Part Name</th>
                                <th class="px-4 py-3">Category & Brand</th>
                                <th class="px-4 py-3">Vehicle Compatibility</th>
                                <th class="px-4 py-3 text-right">Selling Price</th>
                                <th class="px-4 py-3 text-center">Stock</th>
                                <th class="px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($spareParts as $spare)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="font-mono font-bold text-slate-900 text-table-body">{{ $spare->part_number }}</div>
                                        <div class="text-micro-label font-mono text-slate-600">{{ $spare->rack_location ?? 'Unassigned' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900 text-table-body">{{ $spare->name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-block bg-slate-100 text-slate-800 text-micro-label px-2 py-0.5 rounded font-semibold border border-slate-200">{{ $spare->category->name }}</span>
                                        <div class="text-micro-label text-slate-600 mt-0.5">{{ $spare->brand }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-table-body text-slate-700 max-w-xs truncate">
                                        {{ $spare->compatible_models ?? 'Universal' }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900 text-table-body">
                                        ₹{{ number_format($spare->unit_price, 2) }}
                                        <div class="text-micro-label text-slate-500 font-normal">Cost: ₹{{ number_format($spare->cost_price, 2) }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($spare->stock_quantity <= 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-micro-label font-bold uppercase bg-rose-100 text-rose-900 border border-rose-200">Out of Stock</span>
                                        @elseif($spare->isLowStock())
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-micro-label font-bold uppercase bg-amber-100 text-amber-900 border border-amber-200">{{ $spare->stock_quantity }} Left</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-micro-label font-semibold uppercase bg-emerald-100 text-emerald-900 border border-emerald-200">{{ $spare->stock_quantity }} units</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right space-x-1">
                                        <a href="{{ route('spares.edit', $spare->id) }}" class="text-micro-label bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold px-2.5 py-1 rounded border border-slate-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('spares.destroy', $spare->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this spare part?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-micro-label bg-rose-50 hover:bg-rose-100 text-rose-800 font-bold px-2.5 py-1 rounded border border-rose-200">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-micro-label text-slate-500">No spare parts found matching criteria.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-slate-200">
                    {{ $spareParts->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
