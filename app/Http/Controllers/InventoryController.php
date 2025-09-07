<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Inventory::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%");
        }

        if ($request->has('clothing_type') && !empty($request->clothing_type)) {
            $query->where('clothing_type', $request->clothing_type);
        }

        if ($request->has('size') && !empty($request->size)) {
            $query->where('size', $request->size);
        }

        if ($request->has('gender') && !empty($request->gender)) {
            $query->where('gender', $request->gender);
        }

        if ($request->has('low_stock')) {
            $query->where('quantity', '<', 10);
        }

        $inventories = $query->orderBy('clothing_type')->orderBy('size')->get();

        return view('inventory.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'clothing_type' => 'required|in:' . implode(',', array_keys(Inventory::CLOTHING_TYPES)),
            'size' => 'nullable|in:' . implode(',', Inventory::SIZES),
            'gender' => 'required|in:' . implode(',', array_keys(Inventory::GENDERS)),
            'color' => 'nullable|string|max:50',
            'school_house' => 'nullable|string|max:100',
        ]);

        Inventory::create($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Clothing item added successfully.');
    }

    public function show(Inventory $inventory)
    {
        return view('inventory.show', compact('inventory'));
    }

    public function edit(Inventory $inventory)
    {
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'price' => 'required|integer|min:0',
            'clothing_type' => 'required|in:' . implode(',', array_keys(Inventory::CLOTHING_TYPES)),
            'size' => 'nullable|in:' . implode(',', Inventory::SIZES),
            'gender' => 'required|in:' . implode(',', array_keys(Inventory::GENDERS)),
            'color' => 'nullable|string|max:50',
            'school_house' => 'nullable|string|max:100',
        ]);

        $inventory->update($request->all());

        return redirect()->route('inventory.index')
            ->with('success', 'Clothing item updated successfully.');
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('inventory.index')
            ->with('success', 'Clothing item deleted successfully.');
    }

    // ✅ Excel export
    public function export()
    {
        return Excel::download(new InventoryExport, 'inventory-' . date('Y-m-d') . '.xlsx');
    }
}
