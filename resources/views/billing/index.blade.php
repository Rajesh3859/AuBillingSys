<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-section-header font-bold text-slate-900 tracking-tight">
                    POS Billing Terminal
                </h2>
                <p class="text-micro-label text-slate-600 mt-0.5">Select spare parts, auto-calculate 18% GST, apply discounts, and issue customer invoices.</p>
            </div>
            <div class="bg-slate-900 text-white text-micro-label font-mono font-bold px-3 py-1.5 rounded-lg border border-slate-800">
                Invoice #: {{ $nextInvoiceNumber }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen" x-data="posBilling()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="mb-4 bg-rose-800 text-white p-4 rounded-lg font-semibold shadow-sm text-table-body">
                    Error: {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('billing.store') }}" method="POST" @submit="validateSubmit($event)">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    <!-- Left 7 Cols: Catalog & Search -->
                    <div class="lg:col-span-7 space-y-4">

                        <!-- Search & Filter Bar -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-300 flex flex-col sm:flex-row gap-3">
                            <div class="relative flex-1">
                                <input type="text" x-model="searchQuery" placeholder="Search by part name, part #, brand, or vehicle model..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-300 rounded-lg text-table-body focus:outline-none focus:ring-2 focus:ring-slate-900 focus:bg-white text-slate-900 placeholder-slate-400">
                            </div>
                            <select x-model="selectedCategory" class="bg-slate-50 border border-slate-300 rounded-lg text-table-body px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-900 font-medium text-slate-800">
                                <option value="">All Categories</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Spares Catalog Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-[620px] overflow-y-auto pr-1">
                            @foreach($spareParts as $part)
                                <div x-show="filterPart({{ json_encode($part) }})"
                                     class="bg-white p-4 rounded-xl border border-slate-300 shadow-sm hover:border-slate-900 transition-all flex flex-col justify-between group cursor-pointer"
                                     @click="addToCart({{ json_encode($part) }})">
                                    
                                    <div>
                                        <div class="flex items-start justify-between">
                                            <span class="font-mono text-micro-label font-bold text-slate-700 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded">{{ $part->part_number }}</span>
                                            <span class="text-micro-label font-semibold px-2 py-0.5 rounded {{ $part->stock_quantity <= $part->reorder_level ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }}">
                                                Stock: {{ $part->stock_quantity }}
                                            </span>
                                        </div>
                                        <h4 class="text-table-body font-bold text-slate-900 mt-2.5 group-hover:text-emerald-700 transition-colors">{{ $part->name }}</h4>
                                        <p class="text-micro-label text-slate-600 mt-0.5">{{ $part->brand }} • <span class="italic text-slate-500">{{ $part->compatible_models ?? 'Universal' }}</span></p>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-between">
                                        <div>
                                            <span class="text-micro-label text-slate-500">Unit Price</span>
                                            <div class="font-bold text-slate-900 text-section-header">₹{{ number_format($part->unit_price, 2) }}</div>
                                        </div>
                                        <button type="button" class="bg-slate-900 text-white font-semibold px-3 py-1.5 rounded text-micro-label group-hover:bg-emerald-700 transition-colors">
                                            Add Item
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                    </div>

                    <!-- Right 5 Cols: Customer Details & Billing Cart -->
                    <div class="lg:col-span-5 space-y-4">

                        <!-- Customer Info Card -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-300 space-y-3">
                            <h3 class="text-section-header font-bold text-slate-900 border-b border-slate-200 pb-2">
                                Customer Details
                            </h3>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-micro-label font-semibold text-slate-700 uppercase mb-1">Customer Name *</label>
                                    <input type="text" name="customer_name" required x-model="customerName" placeholder="e.g. Rajesh Sharma" class="w-full text-table-body border-slate-300 rounded-lg py-1.5 focus:ring-slate-900 focus:border-slate-900">
                                </div>
                                <div>
                                    <label class="block text-micro-label font-semibold text-slate-700 uppercase mb-1">Phone Number</label>
                                    <input type="text" name="customer_phone" x-model="customerPhone" placeholder="e.g. 9876543210" class="w-full text-table-body border-slate-300 rounded-lg py-1.5 focus:ring-slate-900 focus:border-slate-900">
                                </div>
                            </div>

                            <div>
                                <label class="block text-micro-label font-semibold text-slate-700 uppercase mb-1">Vehicle Number</label>
                                <input type="text" name="vehicle_number" x-model="vehicleNumber" placeholder="KA-01-MJ-4589" class="w-full text-table-body border-slate-300 rounded-lg py-1.5 font-mono uppercase focus:ring-slate-900 focus:border-slate-900">
                            </div>
                        </div>

                        <!-- Active Cart Card -->
                        <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-300 flex flex-col justify-between min-h-[420px]">
                            
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-3">
                                    <h3 class="text-section-header font-bold text-slate-900">
                                        Billing Items (<span x-text="cart.length"></span>)
                                    </h3>
                                    <button type="button" @click="clearCart()" x-show="cart.length > 0" class="text-micro-label font-semibold text-rose-700 hover:underline">Clear All</button>
                                </div>

                                <!-- Empty State -->
                                <template x-if="cart.length === 0">
                                    <div class="text-center py-12 text-slate-500 space-y-1">
                                        <div class="text-table-body font-semibold text-slate-700">Billing Cart is Empty</div>
                                        <p class="text-micro-label text-slate-500">Select spare parts from the left catalog to build invoice.</p>
                                    </div>
                                </template>

                                <!-- Cart Items List -->
                                <div class="space-y-2 max-h-[260px] overflow-y-auto pr-1">
                                    <template x-for="(item, index) in cart" :key="item.id">
                                        <div class="flex items-center justify-between p-2.5 bg-slate-50 rounded-lg border border-slate-200 text-table-body">
                                            <div class="flex-1 pr-2">
                                                <div class="font-bold text-slate-900" x-text="item.name"></div>
                                                <div class="text-micro-label font-mono text-slate-600" x-text="item.part_number"></div>
                                                <input type="hidden" :name="'items[' + index + '][spare_part_id]'" :value="item.id">
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <div class="flex items-center border border-slate-300 rounded bg-white overflow-hidden">
                                                    <button type="button" @click="decreaseQty(index)" class="px-2 py-0.5 text-slate-700 hover:bg-slate-100 font-bold">-</button>
                                                    <input type="number" :name="'items[' + index + '][quantity]'" x-model.number="item.qty" @input="updateQty(index, item.qty)" min="1" :max="item.stock_quantity" class="w-10 text-center text-table-body font-bold border-none p-0 focus:ring-0">
                                                    <button type="button" @click="increaseQty(index)" class="px-2 py-0.5 text-slate-700 hover:bg-slate-100 font-bold">+</button>
                                                </div>

                                                <div class="text-right w-20">
                                                    <div class="font-bold text-slate-900" x-text="'₹' + (item.unit_price * item.qty).toFixed(2)"></div>
                                                </div>

                                                <button type="button" @click="removeItem(index)" class="text-rose-700 font-bold px-1 text-micro-label">Remove</button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Totals Breakdown -->
                            <div class="pt-4 border-t border-slate-200 mt-4 space-y-3">
                                
                                <div class="space-y-1.5 text-table-body text-slate-700">
                                    <div class="flex justify-between">
                                        <span>Subtotal</span>
                                        <span class="font-bold text-slate-900" x-text="'₹' + calculateSubtotal().toFixed(2)">₹0.00</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>GST (18% Flat Rate)</span>
                                        <span class="font-bold text-slate-900" x-text="'₹' + calculateTax().toFixed(2)">₹0.00</span>
                                    </div>

                                    <!-- Discount Row with Type Dropdown & Input -->
                                    <div class="flex justify-between items-center pt-1 border-t border-slate-100">
                                        <span class="font-medium text-slate-800">Discount Type</span>
                                        <div class="flex items-center gap-1.5">
                                            <select name="discount_type" x-model="discountType" class="text-micro-label py-1 px-2 border-slate-300 rounded bg-slate-50 font-bold text-slate-800 focus:ring-slate-900">
                                                <option value="fixed">Fixed (₹)</option>
                                                <option value="percentage">Percent (%)</option>
                                            </select>

                                            <input type="number" name="discount_value" x-model.number="discountValue" min="0" step="any" placeholder="0" class="w-20 text-table-body text-right py-0.5 px-2 border-slate-300 rounded font-semibold text-slate-900 focus:ring-slate-900">
                                        </div>
                                    </div>

                                    <div class="flex justify-between text-micro-label text-slate-500 pt-0.5">
                                        <span>Calculated Discount:</span>
                                        <span class="font-bold text-rose-700" x-text="'-₹' + calculateDiscountAmount().toFixed(2)">-₹0.00</span>
                                    </div>

                                    <div class="flex justify-between text-section-header font-bold text-slate-900 pt-2 border-t border-slate-200">
                                        <span>Grand Total</span>
                                        <span class="text-emerald-700 font-bold" x-text="'₹' + calculateGrandTotal().toFixed(2)">₹0.00</span>
                                    </div>
                                </div>

                                <!-- Payment Settings -->
                                <div class="grid grid-cols-2 gap-2 pt-2">
                                    <div>
                                        <label class="block text-micro-label uppercase font-bold text-slate-600">Payment Status</label>
                                        <select name="payment_status" class="w-full text-table-body py-1 border-slate-300 rounded bg-slate-50 font-semibold text-slate-800">
                                            <option value="paid">Paid</option>
                                            <option value="pending">Pending</option>
                                            <option value="partial">Partial</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-micro-label uppercase font-bold text-slate-600">Payment Method</label>
                                        <select name="payment_method" class="w-full text-table-body py-1 border-slate-300 rounded bg-slate-50 font-semibold text-slate-800">
                                            <option value="cash">Cash</option>
                                            <option value="upi">UPI / GPay</option>
                                            <option value="card">Credit/Debit Card</option>
                                            <option value="bank">Bank Transfer</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" :disabled="cart.length === 0" class="w-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold py-3 rounded-lg shadow-sm transition-all text-table-body uppercase tracking-wider disabled:opacity-50 disabled:cursor-not-allowed">
                                    Complete Sale & Issue Bill
                                </button>
                            </div>

                        </div>

                    </div>

                </div>
            </form>

        </div>
    </div>

    <script>
        function posBilling() {
            return {
                searchQuery: '',
                selectedCategory: '',
                customerName: '',
                customerPhone: '',
                vehicleNumber: '',
                discountType: 'fixed',
                discountValue: 0,
                cart: [],

                filterPart(part) {
                    const query = this.searchQuery.toLowerCase();
                    const matchesSearch = part.name.toLowerCase().includes(query) || 
                                          part.part_number.toLowerCase().includes(query) ||
                                          (part.compatible_models && part.compatible_models.toLowerCase().includes(query)) ||
                                          part.brand.toLowerCase().includes(query);
                    const matchesCategory = !this.selectedCategory || part.category_id == this.selectedCategory;
                    return matchesSearch && matchesCategory;
                },

                addToCart(part) {
                    const existing = this.cart.find(item => item.id === part.id);
                    if (existing) {
                        if (existing.qty < part.stock_quantity) {
                            existing.qty++;
                        } else {
                            alert('Cannot add more than available stock (' + part.stock_quantity + ')');
                        }
                    } else {
                        this.cart.push({
                            id: part.id,
                            name: part.name,
                            part_number: part.part_number,
                            unit_price: parseFloat(part.unit_price),
                            stock_quantity: part.stock_quantity,
                            qty: 1
                        });
                    }
                },

                increaseQty(index) {
                    if (this.cart[index].qty < this.cart[index].stock_quantity) {
                        this.cart[index].qty++;
                    }
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.removeItem(index);
                    }
                },

                updateQty(index, newQty) {
                    if (newQty > this.cart[index].stock_quantity) {
                        this.cart[index].qty = this.cart[index].stock_quantity;
                    }
                },

                removeItem(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    this.cart = [];
                },

                calculateSubtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.unit_price * item.qty), 0);
                },

                calculateTax() {
                    return this.calculateSubtotal() * 0.18;
                },

                calculateDiscountAmount() {
                    const totalBeforeDiscount = this.calculateSubtotal() + this.calculateTax();
                    const val = parseFloat(this.discountValue) || 0;
                    if (this.discountType === 'percentage') {
                        return totalBeforeDiscount * (val / 100);
                    }
                    return val;
                },

                calculateGrandTotal() {
                    const total = this.calculateSubtotal() + this.calculateTax() - this.calculateDiscountAmount();
                    return total > 0 ? total : 0;
                },

                validateSubmit(event) {
                    if (this.cart.length === 0) {
                        event.preventDefault();
                        alert('Please add at least one spare part item to the bill.');
                    }
                }
            };
        }
    </script>
</x-app-layout>
