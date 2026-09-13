<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice_{{ $invoice->invoice_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, .invoice-font {
            font-family: Arial, Helvetica, Calibri, sans-serif !important;
        }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; padding: 0 !important; }
            .print-card { 
                border: none !important; 
                box-shadow: none !important; 
                padding: 0 !important; 
                max-width: 100% !important; 
                width: 100% !important; 
            }
            .table-container { 
                overflow: visible !important; 
                border: none !important; 
            }
            table { 
                min-width: 100% !important; 
                width: 100% !important; 
            }
            @page {
                size: A4 portrait;
                margin: 12mm 10mm;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-2 sm:p-6 antialiased text-slate-900 invoice-font min-h-screen">

    <div class="no-print max-w-3xl mx-auto mb-4 flex justify-between items-center px-1">
        <a href="{{ route('invoices.show', $invoice->id) }}" class="text-xs bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold px-3 py-1.5 rounded-lg transition-colors">Back</a>
        <button onclick="window.print()" class="text-xs bg-emerald-700 hover:bg-emerald-800 text-white font-bold px-4 py-2 rounded-lg shadow-sm transition-colors">
            Print Invoice / Save as PDF
        </button>
    </div>

    <div class="max-w-3xl mx-auto bg-white p-4 sm:p-8 rounded-xl shadow-md border border-slate-300 print-card space-y-6 invoice-font overflow-hidden">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-slate-300 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 uppercase">RAJESH PRO</h1>
                <p class="text-xs text-slate-600 mt-1 leading-relaxed">Authorized Genuine Automobile Spares & Components<br>GSTIN: 29AAAAA0000A1Z5 | Phone: +91 98765 43210</p>
            </div>
            <div class="sm:text-right w-full sm:w-auto flex sm:flex-col justify-between items-end sm:items-end">
                <div class="inline-block bg-slate-900 text-white font-mono text-xs font-bold px-3 py-1 rounded">TAX INVOICE</div>
                <div>
                    <div class="font-mono text-base font-bold text-slate-900 mt-1 sm:mt-2">{{ $invoice->invoice_number }}</div>
                    <div class="text-xs text-slate-600">Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d/m/Y') }}</div>
                </div>
            </div>
        </div>

        <!-- Billed Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 text-xs bg-slate-50 p-3.5 sm:p-4 rounded-lg border border-slate-200">
            <div>
                <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Customer Details</span>
                <div class="font-bold text-sm text-slate-900 mt-1">{{ $invoice->customer_name }}</div>
                <div class="text-slate-700 mt-0.5">Phone: {{ $invoice->customer_phone ?? 'N/A' }}</div>
            </div>
            <div class="sm:text-right">
                <span class="font-bold text-slate-500 uppercase tracking-wider text-[10px]">Vehicle & Payment Info</span>
                <div class="font-mono font-bold text-sm text-slate-900 mt-1">{{ $invoice->vehicle_number ?? 'N/A' }}</div>
                <div class="text-slate-700 mt-0.5">Payment: <span class="font-semibold">{{ strtoupper($invoice->payment_method) }} ({{ strtoupper($invoice->payment_status) }})</span></div>
            </div>
        </div>

        <!-- Line Items Table (Contained in responsive scrollable wrapper to prevent overflow) -->
        <div class="table-container w-full overflow-x-auto rounded-lg border border-slate-200">
            <table class="w-full text-left text-xs min-w-[540px]">
                <thead class="bg-slate-100 text-slate-800 font-bold uppercase border-b border-slate-300">
                    <tr>
                        <th class="py-2.5 px-3 w-10 text-center">#</th>
                        <th class="py-2.5 px-3">Part Number</th>
                        <th class="py-2.5 px-3">Item Description</th>
                        <th class="py-2.5 px-3 text-center w-14">Qty</th>
                        <th class="py-2.5 px-3 text-right w-24">Rate (₹)</th>
                        <th class="py-2.5 px-3 text-right w-16">GST %</th>
                        <th class="py-2.5 px-3 text-right w-24">Total (₹)</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @foreach($invoice->items as $idx => $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="py-2.5 px-3 text-slate-500 font-mono text-center">{{ $idx + 1 }}</td>
                            <td class="py-2.5 px-3 font-mono font-bold text-slate-800 whitespace-nowrap">{{ $item->part_number }}</td>
                            <td class="py-2.5 px-3 font-bold text-slate-900">{{ $item->part_name }}</td>
                            <td class="py-2.5 px-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                            <td class="py-2.5 px-3 text-right whitespace-nowrap">₹{{ number_format($item->unit_price, 2) }}</td>
                            <td class="py-2.5 px-3 text-right text-slate-600 whitespace-nowrap">{{ number_format($item->tax_rate, 0) }}%</td>
                            <td class="py-2.5 px-3 text-right font-bold text-slate-900 whitespace-nowrap">₹{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end pt-4 border-t border-slate-300">
            <div class="w-full sm:w-72 space-y-1.5 text-xs">
                <div class="flex justify-between text-slate-700">
                    <span>Subtotal Amount:</span>
                    <span class="font-bold text-slate-900 font-mono">₹{{ number_format($invoice->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-slate-700">
                    <span>CGST + SGST (18%):</span>
                    <span class="font-bold text-slate-900 font-mono">₹{{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
                @if($invoice->discount > 0)
                    <div class="flex justify-between text-rose-700">
                        <span>Discount:</span>
                        <span class="font-bold font-mono">-₹{{ number_format($invoice->discount, 2) }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-sm font-bold text-slate-900 border-t border-slate-300 pt-2">
                    <span>Net Payable Amount:</span>
                    <span class="text-emerald-800 text-base font-mono">₹{{ number_format($invoice->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer terms -->
        <div class="pt-6 border-t border-slate-300 text-center text-[10px] text-slate-500 space-y-1">
            <p>Thank you for your business! Goods once sold can only be exchanged within 7 days with valid receipt.</p>
            <p class="font-mono text-slate-400">Computer Generated Tax Invoice • Rajesh Pro Systems</p>
        </div>

    </div>

</body>
</html>
