<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
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

Route::get('/thanks', function () {
    return view('user.thanks');
});

Route::get('/', [ShopController::class, 'index']);
Route::get('/detail/{shop_id}', [ShopController::class, 'detail']);

Route::middleware('auth')->group(function () {
    Route::post('/detail/{shop_id}', [ShopController::class, 'reserve']);
    Route::get('/done', [ShopController::class, 'done']);
    Route::post('/favorite/{shop_id}', [ShopController::class, 'favorite']);
    Route::get('/mypage', [ShopController::class, 'mypage']);
});
