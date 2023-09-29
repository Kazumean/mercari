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
        // itemsテーブルとcategoryテーブルを結合し、itemsのidの昇順で並び替える.
        $items = DB::table('items')
                    ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
                    ->leftJoin('category','items.category_id', '=', 'category.id')
                    ->orderBy('item_id')
                    ->paginate(30);
        
        // 大カテゴリを取得する.
        $parentCategories = DB::table('category')
                            ->whereNull('parent')
                            ->whereNull('name_all')
                            ->orderBy('id')
                            ->get();
        
        // 中カテゴリを取得する.
        $childCategories = DB::table('category')
                            ->whereNotNull('parent')
                            ->whereNull('name_all')
                            ->orderBy('id')
                            ->get();
        
        // 小カテゴリを取得する.
        $grandChildCategories = DB::table('category')
                                ->whereNotNull('parent')
                                ->whereNotNull('name_all')
                                ->orderBy('id')
                                ->get();

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }
    
    // 商品を検索する
    public function search(Request $request)
    {
        // 入力値を取得する.
        $itemName = $request->input('itemName');
        $brand = $request->input('brand');
        $parentCategory = $request->input('parent_category_id');
        $childCategory = $request->input('child_category_id');
        $grandchildCategory = $request->input('grandchild_category_id');

        $items = DB::table('items')
                    ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
                    ->leftJoin('category','items.category_id', '=', 'category.id')
                    ->where(function ($query) use ($itemName) {
                        $query->where('items.name', 'like', "%$itemName%");
                    })
                    ->where(function ($query) use ($brand) {
                        $query->where('items.brand', 'like', "%$brand%");
                    })
                    ->where(function ($query) use ($grandchildCategory) {
                        $query->where('category.id', '=', $grandchildCategory);
                    })
                    ->orderBy('items.id')
                    ->paginate(30);


        // 大カテゴリを取得する.
        $parentCategories = DB::table('category')
                ->whereNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();

        // 中カテゴリを取得する.
        $childCategories = DB::table('category')
                ->whereNotNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();

        // 小カテゴリを取得する.
        $grandChildCategories = DB::table('category')
                    ->whereNotNull('parent')
                    ->whereNotNull('name_all')
                    ->orderBy('id')
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
