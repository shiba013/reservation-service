<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;

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
//新規ユーザ登録時のサンクスページ
Route::view('/thanks', 'auth.thanks');

Route::controller(AuthController::class)->group(function () {
    Route::get('/owner/login', 'owner');
    Route::post('/owner/login', 'loginOwner');
    Route::get('/admin/login', 'admin');
    Route::post('/admin/login', 'loginAdmin');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/search', 'search');
    Route::get('/detail/{shop_id}', 'detail');
});

Route::middleware('auth')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::post('/detail/{shop_id}', 'reserve');
        Route::view('/done', 'user.done');
        Route::post('/favorite/{shop_id}', 'favorite');
        Route::get('/mypage', 'mypage');
        Route::patch('/reserve/update/{reservation_id}', 'update');
        Route::delete('/reserve/delete/{reservation_id}', 'destroy');
    });
});

Route::middleware('auth', 'role:2')->group(function () {
    Route::controller(OwnerController::class)->group(function () {
        Route::get('/owner', 'shopList');
        Route::get('/owner/search', 'search');
        Route::get('/owner/reserve', 'reserveList');
        Route::get('/owner/detail/{shop_id}', 'detail');
        Route::patch('/owner/edit/{shop_id}', 'edit');
        Route::get('/owner/create', 'newShop');
        Route::post('/owner/create', 'create');
    });
});
