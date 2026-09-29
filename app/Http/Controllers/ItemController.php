<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // fallback first
        if (!$request->category) {
            $request->merge([
                'category' => Category::orderBy('position', 'asc')->value('category')
            ]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,avif|max:2048',
            'recipes' => 'nullable|array',
            'recipes.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'recipes.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $lastPosition = Item::max('position') ?? 0;
        $item = Item::create([
            'name' => strtoupper($validated['name']),
            'category' => ucwords(strtolower($validated['category'])),
            'price' => $validated['price'],
            'image_path' => $imagePath,
            'position' => $lastPosition + 1,
        ]);

        if (!empty($validated['recipes'])) {
            foreach ($validated['recipes'] as $recipe) {
                $item->recipes()->create([
                    'inventory_item_id' => $recipe['inventory_item_id'],
                    'quantity_used' => $recipe['quantity'],
                ]);
            }
        }

        return back()->with('success', 'Item saved');
    }

    public function itemReorder(Request $request) {
        $category = $request->category;
        $order = $request->order;

        DB::transaction(function () use ($order, $category) {
            foreach ($order as $position => $id) {
                Item::where('id', $id)
                    ->where('category', $category)
                    ->update([
                        'position' => $position,
                    ]);
            }
        });

        return response()->json([
            'message' => 'Order updated successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'recipes' => 'nullable|array',
            'recipes.*.inventory_item_id' => 'required|exists:inventory_items,id',
            'recipes.*.quantity' => 'required|numeric|min:0.01',
        ]);

        $item = Item::findOrFail($id);

        if($request->hasFile('image'))
            $path = $request->file('image')->store('items', 'public');
        else 
            $path = $item->image_path;

        $item->update([
            'name' => Str::upper($request->name),
            'price' => $request->price,
            'image_path' => $path
        ]);

        // Sync recipes
        $item->recipes()->delete();
        if ($request->has('recipes')) {
            foreach ($request->recipes as $recipe) {
                $item->recipes()->create([
                    'inventory_item_id' => $recipe['inventory_item_id'],
                    'quantity_used' => $recipe['quantity'],
                ]);
            }
        }

        return back()->with('success', 'Item updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Item::destroy($id);
        return back()->with('success', 'Item deleted');
    }
}
