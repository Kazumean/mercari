<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ItemService
{
    // itemsテーブルとcategoryテーブルを結合。すべてのitemsの情報を取得する.
    public function getAllItemsWithCategories()
    {
        return DB::table('items')
            ->select(
                'items.id as item_id',
                'items.name as item_name',
                'items.price',
                'items.brand',
                'items.condition_id',
                'items.category_id',
                'category.id',
                'category.parent',
                'category.name as category_name',
                'category.name_all'
            )
            ->leftJoin('category', 'items.category_id', '=', 'category.id')
            ->orderBy('item_id')
            ->paginate(30);
    }

    // itemsテーブルとcategoryテーブルを結合。idで指定したitemの情報を取得する.
    public function getItemWithCategories($id)
    {
        return DB::table('items')
            ->select(
                'items.id as item_id',
                'items.name as item_name',
                'items.price',
                'items.brand',
                'items.condition_id',
                'items.category_id',
                'category.id as category_id',
                'items.description',
                'category.parent',
                'category.name as category_name',
                'category.name_all'
            )
            ->leftJoin('category', 'items.category_id', '=', 'category.id')
            ->where('items.id', $id)
            ->first();
    }

    // 小カテゴリで絞り込み検索をする
    public function getItemByGrandchildCategory($category_id)
    {
        return DB::table('items')
            ->select(
                'items.id as item_id',
                'items.name as item_name',
                'items.price',
                'items.brand',
                'items.condition_id',
                'items.category_id',
                'category.id as category_id',
                'items.description',
                'category.parent',
                'category.name as category_name',
                'category.name_all'
            )
            ->leftJoin('category', 'items.category_id', '=', 'category.id')
            ->where('category_id', $category_id)
            ->orderBy('item_id')
            ->paginate(30);
    }

    // 中カテゴリで絞り込み検索をする
    public function getItemByChildCategory($category_id)
    {
        return DB::table('items')
            ->select(
                'items.id as item_id',
                'items.name as item_name',
                'items.price',
                'items.brand',
                'items.condition_id',
                'items.category_id',
                'category.id as category_id',
                'items.description',
                'category.parent',
                'category.name as category_name',
                'category.name_all'
            )
            ->leftJoin('category', 'items.category_id', '=', 'category.id')
            ->where('category_id', $category_id)
            ->orderBy('item_id')
            ->paginate(30);
    }

    // 大カテゴリ群を取得する.
    public function getParentCategories()
    {
        return DB::table('category')
                ->whereNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();
    }

    // 中カテゴリ群を取得する.
    public function getChildCategories()
    {
        return DB::table('category')
                ->whereNotNull('parent')
                ->whereNull('name_all')
                ->orderBy('id')
                ->get();
    }

    // 小カテゴリ群を取得する.
    public function getGrandChildCategories()
    {
        return DB::table('category')
                ->whereNotNull('parent')
                ->whereNotNull('name_all')
                ->orderBy('id')
                ->get();
    }

    // 小カテゴリから中カテゴリを取得する.
    public function getChildCategoryFromGrandchild()
    {
        return DB::table('category')
                ->select('id', 'parent', 'name', 'name_all')
                ->where('parent', '=', 'id')
                ->first();
    }
}
