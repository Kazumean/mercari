<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ItemService
{
    public function getAllItemsWithCategories()
    {
        return DB::table('items')
            ->select('items.id as item_id', 'items.name as item_name', 'items.price', 'items.brand', 'items.condition_id', 'items.category_id', 'category.id', 'category.parent', 'category.name as category_name', 'category.name_all')
            ->leftJoin('category', 'items.category_id', '=', 'category.id')
            ->orderBy('item_id')
            ->paginate(30);
    }

    // 大カテゴリを取得する.
    public function getParentCategories()
    {
        return DB::table('category')
                ->whereNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();
    }

    // 中カテゴリを取得する.
    public function getChildCategories()
    {
        return DB::table('category')
                ->whereNotNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();
    }

    // 小カテゴリを取得する.
    public function getGrandChildCategories()
    {
        return DB::table('category')
                ->whereNotNull('parent')
                ->whereNotNull('name_all')
                ->orderBy('id')
                ->get();
    }
}
