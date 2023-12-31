<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Services\ItemService;
use App\Http\Requests\ItemRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class ItemController extends Controller
{

    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = $this->itemService->getAllItemsWithCategories();

        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();
        // dd($items);

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }
    
    public function search(Request $request)
    {
        $itemName = $request->input('itemName');
        $brand = $request->input('brand');
        $parentCategoryId = $request->input('parent_category_id');
        $childCategoryId = $request->input('child_category_id');
        $grandchildCategoryId = $request->input('grandchild_category_id');

        $query = DB::table('items')
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
            ->leftJoin('category', 'items.category_id', '=', 'category.id');

        if (!empty($itemName)) {
            $query->where('items.name', 'like', "%$itemName%");
        }

        if (!empty($brand)) {
            $query->where('items.brand', 'like', "%$brand%");
        }

        // カテゴリ条件の組み立て
        if ($grandchildCategoryId != 0) {
            $query->where('items.category_id', '=', $grandchildCategoryId);
        } elseif ($childCategoryId != 0) {
            $query->where('items.category_id', '=', $childCategoryId);
        } elseif ($parentCategoryId != 0) {
            $query->where('items.category_id', '=', $parentCategoryId);
        }

        $items = $query->orderBy('items.id')->paginate(30);

        // ページネーションのページ番号をクエリパラメータから取得
        $pageNumber = $request->input('page', 1);

        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories', 'pageNumber'));
    }

    // 小カテゴリで絞り込む
    public function searchItemsByGrandchildCategory($category_id)
    {
        $items = $this->itemService->getItemByGrandchildCategory($category_id);

        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }

    // 中カテゴリで絞り込む
    public function searchItemsByChildCategory($category_id)
    {
        $items = $this->itemService->getItemByChildCategory($category_id);

        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();

        return view('items.list', compact('items', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();

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
    public function show(Item $item)
    {
        $item = $this->itemService->getItemWithCategories($item->id);

        return view('items.detail', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        $item = $this->itemService->getItemWithCategories($item->id);
        $itemGrandChildCaterogyId = $item->category_id;
        $itemChildCategoryId = $item->parent;
        $itemParentCategoryId = DB::table('category')
                                    ->where('id', $itemChildCategoryId)
                                    ->value('parent');

        $parentCategories = $this->itemService->getParentCategories();
        $childCategories = $this->itemService->getChildCategories();
        $grandChildCategories = $this->itemService->getGrandChildCategories();

        return view('items.edit', compact('item', 'itemGrandChildCaterogyId', 'itemChildCategoryId', 'itemParentCategoryId', 'parentCategories', 'childCategories', 'grandChildCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ItemRequest $request, Item $item)
    {
        $item->name = $request->itemName;
        $item->price = $request->price;
        $item->category_id = $request->grandchild_category_id;
        $item->brand = $request->brand;
        $item->condition_id = $request->condition;
        $item->description = $request->description;
        $item->save();

        return redirect()->route('item.show', $item)->with('success', '商品の情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();

        return redirect()->route('item.index')->with('danger', '商品を削除しました。');    
    }
}
