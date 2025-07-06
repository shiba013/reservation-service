<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

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
Route::view('/thanks', 'user.thanks');

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
