<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    // 大カテゴリから中カテゴリを抽出する
    public function getChildCategories(Request $request)
    {
        $parentCategoryId = $request->input('parent_category_id');

        $childCategories = DB::table('category')
                            ->where('parent', $parentCategoryId)
                            ->get();
        
        return response()->json($childCategories);
    }

    // 中カテゴリから小カテゴリを抽出する
    public function getGrandchildCategories(Request $request)
{
        $childCategoryId = $request->input('child_category_id');

        $grandChildCategories = DB::table('category')
                                ->where('parent', $childCategoryId)
                                ->get();
    
        return response()->json($grandChildCategories);
}
}
