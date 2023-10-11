<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 商品を検索する
Route::get('/search', [ItemController::class, 'search'])->name('items.search');

// 商品を中カテゴリで絞り込む
Route::get('/search/childCategory/{category_id}', [ItemController::class, 'searchItemsByChildCategory'])->name('items.searchItemsByChildCategory');
// 商品を小カテゴリで絞り込む
Route::get('/search/grandchildCategory/{category_id}', [ItemController::class, 'searchItemsByGrandchildCategory'])->name('items.searchItemsByGrandchildCategory');

// itemに関するルート
Route::get('/item', [ItemController::class, 'index'])->name('item.index');
Route::get('/item/{item}', [ItemController::class, 'show'])->name('item.show');

Route::middleware('auth')->group(function () {
    Route::get('/item/create', [ItemController::class, 'create'])->name('item.create');
    Route::post('/item', [ItemController::class, 'store'])->name('item.store');
    Route::get('/item/{item}/edit', [ItemController::class, 'edit'])->name('item.edit');
    Route::patch('/item/{item}', [ItemController::class, 'update'])->name('item.update');
    Route::delete('/item/{item}', [ItemController::class, 'destroy'])->name('item.destroy');
    });

    

// categoryに関するルート
Route::get('/get-childcategories', [CategoryController::class, 'getChildCategories']);
Route::get('/get-grandchildcategories', [CategoryController::class, 'getGrandchildCategories']);

require __DIR__.'/auth.php';
