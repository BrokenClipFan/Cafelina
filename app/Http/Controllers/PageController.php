<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Item;
use App\Models\Setting;
use App\Models\InventoryItem;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function editOrder()
    {
        $categories = Category::orderBy('position', 'asc')->get();
        
        if ($categories->isEmpty()) {
            return view('admin.editOrder', [
                'categories' => collect(),
                'items' => collect(),
                'firstCategory' => null
            ]);
        }

        $firstCategory = $categories->shift();

        $items = Item::with('recipes.inventoryItem')->orderBy('position', 'asc')->get();
        $inventoryItems = InventoryItem::orderBy('name', 'asc')->get();

        return view('admin.editOrder', compact('categories', 'items', 'firstCategory', 'inventoryItems'));
    }

    public function index()
    {
        $categories = Category::orderBy('position', 'asc')->get();
        $items = Item::orderBy('position', 'asc')->get();
        $taxString = Setting::where('name', 'tax')->first();
        $taxDecimal = (float) $taxString->value;
        return view('pos', compact('categories', 'items', 'taxDecimal'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PageController $pageController)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PageController $pageController)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PageController $pageController)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PageController $pageController)
    {
        //
    }
}
