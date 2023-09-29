<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function getChildCategories(Request $request)
    {
        $parentCategory = $request->input('parent_category_id');

        $childCategories = DB::table('category')
                            ->where('parent', $parentCategory)
                            ->get();
        
        return response()->json($childCategories);
    }
}
