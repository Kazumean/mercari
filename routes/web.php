<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// 商品を検索する
Route::get('/search', [ItemController::class, 'search'])->name('items.search');

// itemに関するリソースコントローラー
Route::resource('item', ItemController::class);

// categoryに関するルート
Route::get('/get-childcategories', [CategoryController::class, 'getChildCategories']);
Route::get('/get-grandchildcategories', [CategoryController::class, 'getGrandchildCategories']);
