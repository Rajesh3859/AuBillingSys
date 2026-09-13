<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SparePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $spareParts = SparePart::with('category')->where('stock_quantity', '>', 0)->get();
        $customers = Customer::orderBy('name')->get();
        $nextInvoiceNumber = 'INV-'.date('Y').'-'.str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

        return view('billing.index', compact('categories', 'spareParts', 'customers', 'nextInvoiceNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'vehicle_number' => 'nullable|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
            'payment_status' => 'required|string|in:paid,pending,partial',
            'payment_method' => 'required|string|in:cash,upi,card,bank',
            'discount_type' => 'nullable|string|in:fixed,percentage',
            'discount_value' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.spare_part_id' => 'required|exists:spare_parts,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $invoice = DB::transaction(function () use ($validated) {
                $subtotal = 0;
                $taxAmount = 0;
                $itemsData = [];

                foreach ($validated['items'] as $itemInput) {
                    $part = SparePart::findOrFail($itemInput['spare_part_id']);

                    if ($part->stock_quantity < $itemInput['quantity']) {
                        throw new \Exception("Insufficient stock for {$part->name}. Available: {$part->stock_quantity}");
                    }

                    $lineSubtotal = $part->unit_price * $itemInput['quantity'];
                    $lineTax = $lineSubtotal * 0.18; // GST 18%
                    $lineTotal = $lineSubtotal + $lineTax;

                    $subtotal += $lineSubtotal;
                    $taxAmount += $lineTax;

                    // Deduct stock
                    $part->decrement('stock_quantity', $itemInput['quantity']);

                    $itemsData[] = [
                        'spare_part_id' => $part->id,
                        'part_number' => $part->part_number,
                        'part_name' => $part->name,
                        'quantity' => $itemInput['quantity'],
                        'unit_price' => $part->unit_price,
                        'tax_rate' => 18.00,
                        'total_price' => $lineTotal,
                    ];
                }

                $discountType = $validated['discount_type'] ?? 'fixed';
                $discountVal = floatval($validated['discount_value'] ?? $validated['discount'] ?? 0);

                if ($discountType === 'percentage') {
                    $discount = ($subtotal + $taxAmount) * ($discountVal / 100);
                } else {
                    $discount = $discountVal;
                }

                $totalAmount = max(0, $subtotal + $taxAmount - $discount);

                $invoiceNumber = 'INV-'.date('Y').'-'.str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

                // Auto-create or link customer if new
                $customerId = $validated['customer_id'] ?? null;
                if (! $customerId && ! empty($validated['customer_name']) && ! empty($validated['customer_phone'])) {
                    $customer = Customer::firstOrCreate(
                        ['phone' => $validated['customer_phone']],
                        [
                            'name' => $validated['customer_name'],
                            'vehicle_number' => $validated['vehicle_number'],
                        ]
                    );
                    $customerId = $customer->id;
                }

                $invoice = Invoice::create([
                    'invoice_number' => $invoiceNumber,
                    'customer_id' => $customerId,
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'vehicle_number' => $validated['vehicle_number'] ?? null,
                    'invoice_date' => now(),
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'discount' => $discount,
                    'total_amount' => $totalAmount,
                    'payment_status' => $validated['payment_status'],
                    'payment_method' => $validated['payment_method'],
                    'notes' => $validated['notes'] ?? null,
                ]);

                foreach ($itemsData as $item) {
                    $invoice->items()->create($item);
                }

                return $invoice;
            });

            return redirect()->route('invoices.show', $invoice->id)->with('success', 'Invoice #'.$invoice->invoice_number.' generated successfully!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
