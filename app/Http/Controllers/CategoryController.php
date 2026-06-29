<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CategoryController extends Controller
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
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
        ]);

        $lastPosition = Category::max('position') ?? 0;

        Category::create([
            'category' => ucwords(strtolower($validated['category'])),
            'icon' => $validated['icon'],
            'position' => $lastPosition + 1,
        ]);
        return redirect()->back()->with('success', 'Category Saved');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function categoryReorder(Request $request) {
        $category = $request->category;
        $order = $request->order;

        DB::transaction(function () use ($order) {
            foreach ($order as $position => $id) {
                Category::where('id', $id)
                    ->update([
                        'position' => $position,
                ]);
            }
        });

        return response()->json(['status' => 'ok']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        Category::destroy($id);

        return response()->json(['message' => 'ok']);
    }
}
