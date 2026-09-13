<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SparePart;
use Illuminate\Http\Request;

class SparePartController extends Controller
{
    public function index(Request $request)
    {
        $query = SparePart::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('part_number', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('compatible_models', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('stock_quantity', '<=', 'reorder_level');
            } elseif ($request->stock_status === 'out') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        $spareParts = $query->orderBy('name')->paginate(15);
        $categories = Category::all();

        return view('spares.index', compact('spareParts', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('spares.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|unique:spare_parts,part_number|max:100',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:100',
            'compatible_models' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'rack_location' => 'nullable|string|max:50',
        ]);

        SparePart::create($validated);

        return redirect()->route('spares.index')->with('success', 'Spare part added successfully!');
    }

    public function edit(SparePart $spare)
    {
        $categories = Category::all();

        return view('spares.edit', compact('spare', 'categories'));
    }

    public function update(Request $request, SparePart $spare)
    {
        $validated = $request->validate([
            'part_number' => 'required|string|max:100|unique:spare_parts,part_number,'.$spare->id,
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand' => 'required|string|max:100',
            'compatible_models' => 'nullable|string|max:255',
            'unit_price' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'rack_location' => 'nullable|string|max:50',
        ]);

        $spare->update($validated);

        return redirect()->route('spares.index')->with('success', 'Spare part updated successfully!');
    }

    public function destroy(SparePart $spare)
    {
        $spare->delete();

        return redirect()->route('spares.index')->with('success', 'Spare part deleted successfully!');
    }
}
