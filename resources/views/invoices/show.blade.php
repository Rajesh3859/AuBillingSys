<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-section-header font-bold text-slate-900 tracking-tight">
                    Invoice Details: {{ $invoice->invoice_number }}
                </h2>
                <p class="text-micro-label text-slate-600 mt-0.5">Issued on {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M, Y') }}</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('invoices.index') }}" class="text-micro-label bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-3 py-2 rounded-lg">
                    Invoices List
                </a>
                <a href="{{ route('invoices.print', $invoice->id) }}" target="_blank" class="text-micro-label bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2 rounded-lg shadow-sm">
                    Print Invoice
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-slate-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-900 text-white p-4 rounded-lg text-table-body font-semibold shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Invoice Card container with Arial / Helvetica / Calibri font stack -->
            <div class="bg-white p-8 rounded-xl shadow-sm border border-slate-300 space-y-6" style="font-family: Arial, Helvetica, Calibri, sans-serif;">

                <!-- Header Info -->
                <div class="flex flex-col sm:flex-row justify-between border-b border-slate-200 pb-6 gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-section-header font-bold text-slate-900 uppercase">
                            Rajesh Pro
                        </div>
                        <p class="text-micro-label text-slate-600 mt-1">Automobile Spares & Billing Center<br>GSTIN: 29AAAAA0000A1Z5</p>
                    </div>
                    <div class="sm:text-right">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-900 font-bold text-micro-label uppercase tracking-wider rounded border border-emerald-200">
                            {{ $invoice->payment_status }} ({{ strtoupper($invoice->payment_method) }})
                        </span>
                        <div class="font-mono text-section-header font-bold text-slate-900 mt-2">{{ $invoice->invoice_number }}</div>
                        <div class="text-micro-label text-slate-600">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
                    </div>
                </div>

                <!-- Customer Details -->
                <div class="bg-slate-50 p-4 rounded-lg border border-slate-200 grid grid-cols-1 sm:grid-cols-3 gap-4 text-micro-label">
                    <div>
                        <span class="text-slate-500 uppercase font-bold block mb-1">Billed To</span>
                        <div class="font-bold text-slate-900 text-table-body">{{ $invoice->customer_name }}</div>
                        <div class="text-slate-600 mt-0.5">{{ $invoice->customer_phone ?? 'No Phone' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase font-bold block mb-1">Vehicle Identification</span>
                        <div class="font-mono font-bold text-slate-900 text-table-body uppercase">{{ $invoice->vehicle_number ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-500 uppercase font-bold block mb-1">GST Number</span>
                        <div class="font-mono font-medium text-slate-800 text-table-body">{{ $invoice->customer->gst_number ?? 'Unregistered' }}</div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-table-body">
                        <thead class="bg-slate-900 text-white text-micro-label uppercase">
                            <tr>
                                <th class="px-4 py-2.5">Part #</th>
                                <th class="px-4 py-2.5">Part Name</th>
                                <th class="px-4 py-2.5 text-center">Qty</th>
                                <th class="px-4 py-2.5 text-right">Unit Price</th>
                                <th class="px-4 py-2.5 text-right">GST %</th>
                                <th class="px-4 py-2.5 text-right">Total Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @foreach($invoice->items as $item)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-bold text-slate-800 text-table-body">{{ $item->part_number }}</td>
                                    <td class="px-4 py-3 font-semibold text-slate-900 text-table-body">{{ $item->part_name }}</td>
                                    <td class="px-4 py-3 text-center font-bold text-slate-900 text-table-body">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3 text-right text-table-body text-slate-800">₹{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="px-4 py-3 text-right text-micro-label text-slate-600">{{ number_format($item->tax_rate, 0) }}%</td>
                                    <td class="px-4 py-3 text-right font-bold text-slate-900 text-table-body">₹{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Totals Breakdown -->
                <div class="flex justify-end border-t border-slate-200 pt-4">
                    <div class="w-full sm:w-72 space-y-2 text-table-body">
                        <div class="flex justify-between text-slate-700">
                            <span>Subtotal:</span>
                            <span class="font-semibold text-slate-900">₹{{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-700">
                            <span>GST Tax (18%):</span>
                            <span class="font-semibold text-slate-900">₹{{ number_format($invoice->tax_amount, 2) }}</span>
                        </div>
                        @if($invoice->discount > 0)
                            <div class="flex justify-between text-rose-700">
                                <span>Discount:</span>
                                <span class="font-semibold">-₹{{ number_format($invoice->discount, 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-section-header font-bold text-slate-900 border-t border-slate-300 pt-2">
                            <span>Grand Total:</span>
                            <span class="text-emerald-700 font-bold text-kpi-banner">₹{{ number_format($invoice->total_amount, 2) }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
