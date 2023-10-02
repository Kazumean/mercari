<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
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
        $parentCategoryId = $request->input('parent_category_id');
        $childCategoryId = $request->input('child_category_id');
        $grandchildCategoryId = $request->input('grandchild_category_id');

        // $items = DB::table('items')
        //             ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
        //             ->leftJoin('category','items.category_id', '=', 'category.id')
        //             ->where(function ($query) use ($itemName) {
        //                 $query->where('items.name', 'like', "%$itemName%");
        //             })
        //             ->where(function ($query) use ($brand) {
        //                 $query->where('items.brand', 'like', "%$brand%");
        //             })
        //             ->where(function ($query) use ($parentCategoryId) {
        //                 $query->where('category.id', '=', $parentCategoryId);
        //             })
        //             ->where(function ($query) use ($childCategoryId) {
        //                 $query->where('category.id', '=', $childCategoryId);
        //             })
        //             ->where(function ($query) use ($grandchildCategoryId) {
        //                 $query->where('category.id', '=', $grandchildCategoryId);
        //             })
        //             ->orderBy('items.id')
        //             ->paginate(30);


        $items = DB::table('items')
    ->select(
        'items.id as item_id',
        'items.name as item_name',
        'items.price',
        'items.brand',
        'items.condition_id',
        'items.category_id',
        'category.id as category_id',
        'category.parent',
        'category.name as category_name',
        'category.name_all'
    )
    ->leftJoin('category', 'items.category_id', '=', 'category.id')
    ->where(function ($query) use ($itemName, $brand, $parentCategoryId, $childCategoryId, $grandchildCategoryId) {
        $query->where(function ($subquery) use ($parentCategoryId, $childCategoryId, $grandchildCategoryId) {
            // 中カテゴリと小カテゴリを同時に指定した場合に対応
            if ($grandchildCategoryId != 0) {
                $subquery->where('category.id', '=', $grandchildCategoryId);
            } elseif ($childCategoryId != 0) {
                $subquery->where('category.id', '=', $childCategoryId);
            } elseif ($parentCategoryId != 0) {
                $subquery->where('category.id', '=', $parentCategoryId);
            }
        });

        if (!empty($itemName)) {
            $query->where('items.name', 'like', "%$itemName%");
        }

        if (!empty($brand)) {
            $query->where('items.brand', 'like', "%$brand%");
        }
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

        return view('items.add', compact('parentCategories', 'childCategories', 'grandChildCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ItemRequest $request)
    {
        $item = new Item();

        $item->name = $request->itemName;
        $item->price = $request->price;
        $item->category_id = $request->grandchild_category_id;
        $item->brand = $request->brand;
        $item->condition_id = $request->condition;
        $item->description = $request->description;
        $item->save();

        return redirect()->route('item.create')->with('success', '商品を登録しました。');
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
