<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryLog;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort_by') && $request->has('order')) {
            $sortBy = $request->sort_by;
            $order = $request->order == 'desc' ? 'desc' : 'asc';
            if (in_array($sortBy, ['name', 'unit', 'current_stock'])) {
                $query->orderBy($sortBy, $order);
            }
        } else {
            // Default sorting
            $query->orderBy('name', 'asc');
        }

        $items = $query->get();

        return view('admin.inventory.index', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.current_stock' => 'required|numeric|min:0',
            'items.*.unit' => 'required|string|max:50',
            'items.*.price_per_unit' => 'required|numeric|min:0',
            'items.*.max_stock' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['items'] as $itemData) {
            InventoryItem::create($itemData);
        }

        return redirect()->route('admin.inventory')->with('success', count($validated['items']) . ' items added successfully.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'current_stock' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'price_per_unit' => 'required|numeric|min:0',
            'max_stock' => 'nullable|numeric|min:0',
        ]);

        $item = InventoryItem::findOrFail($id);
        $item->update($validated);

        return redirect()->route('admin.inventory')->with('success', 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $item = InventoryItem::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.inventory')->with('success', 'Item deleted successfully.');
    }

    public function restock(Request $request, $id)
    {
        $validated = $request->validate([
            'added_amount' => 'required|numeric|min:0.01',
            'cost' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:255',
        ]);

        $item = InventoryItem::findOrFail($id);
        
        $item->current_stock += $validated['added_amount'];
        $item->save();

        InventoryLog::create([
            'inventory_item_id' => $item->id,
            'added_amount' => $validated['added_amount'],
            'cost' => $validated['cost'],
            'remarks' => $validated['remarks'],
        ]);

        return redirect()->route('admin.inventory')->with('success', 'Stock added successfully.');
    }

    public function history($id)
    {
        $item = InventoryItem::with('logs')->findOrFail($id);
        return response()->json([
            'item' => $item,
            'logs' => $item->logs()->orderBy('created_at', 'desc')->get()
        ]);
    }
}
