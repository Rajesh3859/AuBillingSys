<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Edit Spare Part: {{ $spare->name }}
            </h2>
            <a href="{{ route('spares.index') }}" class="text-xs bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-3 py-1.5 rounded-lg">
                Back to Inventory
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                <form action="{{ route('spares.update', $spare->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Part Number *</label>
                            <input type="text" name="part_number" value="{{ old('part_number', $spare->part_number) }}" required class="w-full text-sm font-mono uppercase border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Part Name *</label>
                            <input type="text" name="name" value="{{ old('name', $spare->name) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Category *</label>
                            <select name="category_id" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $spare->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Brand *</label>
                            <input type="text" name="brand" value="{{ old('brand', $spare->brand) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Compatible Vehicle Models</label>
                            <input type="text" name="compatible_models" value="{{ old('compatible_models', $spare->compatible_models) }}" class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Selling Unit Price (₹) *</label>
                            <input type="number" step="0.01" name="unit_price" value="{{ old('unit_price', $spare->unit_price) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Cost Price (₹) *</label>
                            <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $spare->cost_price) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Stock Quantity *</label>
                            <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $spare->stock_quantity) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Reorder Level Threshold *</label>
                            <input type="number" name="reorder_level" value="{{ old('reorder_level', $spare->reorder_level) }}" required class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold uppercase text-slate-600 mb-1">Rack / Storage Location</label>
                            <input type="text" name="rack_location" value="{{ old('rack_location', $spare->rack_location) }}" class="w-full text-sm border-slate-200 rounded-xl py-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('spares.index') }}" class="px-4 py-2 bg-slate-100 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-200">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-slate-900 text-white font-bold rounded-xl text-sm hover:bg-slate-800 shadow-md">Update Spare Part</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
