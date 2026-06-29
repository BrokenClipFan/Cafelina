<?php

namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Item;
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
            dd('here');
            return view('editOrder', [
                'categories' => collect(),
                'items' => collect(),
                'firstCategory' => null
            ]);
        }

        $firstCategory = $categories->shift();

        $items = Item::orderBy('position', 'asc')->get();

        return view('editOrder', compact('categories', 'items', 'firstCategory'));
    }

    public function index()
    {
        $categories = Category::orderBy('position', 'asc')->get();
        $items = Item::orderBy('position', 'asc')->get();

        return view('welcome', compact('categories', 'items'));
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
