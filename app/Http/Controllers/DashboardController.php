<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\SparePart;

class DashboardController extends Controller
{
    public function index()
    {
        $todaySales = Invoice::whereDate('invoice_date', today())->sum('total_amount');
        $monthlySales = Invoice::whereMonth('invoice_date', now()->month)
            ->whereYear('invoice_date', now()->year)
            ->sum('total_amount');

        $totalInvoicesCount = Invoice::count();
        $totalSparesCount = SparePart::count();
        $totalCustomersCount = Customer::count();

        // Low stock parts
        $lowStockParts = SparePart::with('category')
            ->whereColumn('stock_quantity', '<=', 'reorder_level')
            ->get();

        // Recent Invoices
        $recentInvoices = Invoice::with('customer')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        // Top Selling Spares (by count in invoice_items)
        $topSpares = SparePart::with('category')
            ->withCount('category')
            ->orderBy('stock_quantity', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todaySales',
            'monthlySales',
            'totalInvoicesCount',
            'totalSparesCount',
            'totalCustomersCount',
            'lowStockParts',
            'recentInvoices',
            'topSpares'
        ));
    }
}
