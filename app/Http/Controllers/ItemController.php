<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = DB::table('items')
                    ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
                    ->leftJoin('category','items.category_id', '=', 'category.id')
                    ->orderBy('item_id')
                    ->paginate(30);
        
        $parentCategories = DB::table('category')
                            ->whereNull('parent')
                            ->whereNull('name_all')
                            ->get();
        
        $childCategories = DB::table('category')
                            ->whereNotNull('parent')
                            ->whereNull('name_all')
                            ->get();
        
        $grandChildCategories = DB::table('category')
                                ->whereNotNull('parent')
                                ->whereNotNull('name_all')
                                ->get();

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }
    
    // 商品を検索する
    public function search(Request $request)
    {
        $itemName = $request->input('itemName');

        $items = DB::table('items')
                    ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
                    ->leftJoin('category','items.category_id', '=', 'category.id')
                    ->where(function ($query) use ($itemName) {
                        $query->where('items.name', 'like', "%$itemName%");
                    })
                    ->orderBy('items.id')
                    ->paginate(30);

        $parentCategories = DB::table('category')
                ->whereNull('parent')
                ->whereNull('name_all')
                ->get();

        $childCategories = DB::table('category')
                ->whereNotNull('parent')
                ->whereNull('name_all')
                ->get();

        $grandChildCategories = DB::table('category')
                    ->whereNotNull('parent')
                    ->whereNotNull('name_all')
                    ->get();
        
        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
