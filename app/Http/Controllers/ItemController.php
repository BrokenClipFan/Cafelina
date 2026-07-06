<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        ]);

        $lastPosition = Item::max('position') ?? 0;
        Item::create([
            'name' => strtoupper($validated['name']),
            'category' => ucwords(strtolower($validated['category'])),
            'price' => $validated['price'],
            'position' => $lastPosition + 1
        ]);

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
        Item::where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price
        ]);

        return response()->json(['message' => 'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Item::destroy($id);
        return response()->json(['message' => 'Delete successfully']);
    }
}
