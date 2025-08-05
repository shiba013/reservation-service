<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;

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
Route::controller(AuthController::class)->group(function () {
    Route::get('/owner/login', 'owner');
    Route::post('/owner/login', 'loginOwner');
    Route::get('/admin/login', 'admin');
    Route::post('/admin/login', 'loginAdmin');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/email/verify', 'email')
    ->middleware('auth')
    ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', 'verification')
    ->middleware('auth', 'signed')
    ->name('verification.verify');
    Route::post('/email/verify/resend', 'resend')
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');
});

Route::controller(ShopController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/search', 'search');
    Route::get('/detail/{shop_id}', 'detail');
    route::get('/review/{shop_id}', 'review');
    Route::get('/review/sort/{shop_id}', 'sort');
});

Route::middleware('auth','verified')->group(function () {
    Route::controller(UserController::class)->group(function () {
        Route::view('/thanks', 'auth.thanks');
        Route::post('/detail/{shop_id}', 'reserve');
        Route::view('/done', 'user.done');
        Route::post('/favorite/{shop_id}', 'favorite');
        Route::get('/mypage', 'mypage');
        Route::patch('/reserve/update/{reservation_id}', 'reserveUpdate');
        Route::delete('/reserve/delete/{reservation_id}', 'reserveDestroy');
        Route::post('/review/{shop_id}', 'reviewCreate');
        Route::patch('/review/update', 'reviewUpdate');
        Route::delete('/review/delete', 'reviewDestroy');
    });
});

Route::middleware('auth', 'verified', 'role:2,3')->group(function () {
    Route::controller(OwnerController::class)->group(function () {
        Route::get('/owner', 'shopList');
        Route::get('/owner/search', 'search');
        Route::get('/owner/reserve/{shop_id}', 'reserveList');
        Route::post('/owner/reserve/setting/{shop_id}', 'setting');
        Route::patch('/owner/reserve/update/{reservation_id}', 'update');
        Route::delete('/owner/reserve/delete/{reservation_id}', 'destroy');
        Route::get('/owner/detail/{shop_id}', 'detail');
        Route::patch('/owner/edit/{shop_id}', 'edit');
        Route::get('/owner/create', 'newShop');
        Route::post('/owner/create', 'create');
    });
    Route::controller(ExportController::class)->group(function () {
        Route::post('/owner/export/shop/list', 'exportShop');
        Route::post('/owner/export/reserve/list/{shop_id}', 'exportReserve');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/owner/mail', 'ownerMail');
        Route::post('/owner/mail', 'ownerSend');
    });
});

Route::middleware('auth', 'verified', 'role:3')->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/admin', 'ownerList');
        Route::get('/admin/search', 'search');
        Route::view('/admin/register', 'admin.create_owner');
        Route::post('/admin/register', 'create');
        Route::patch('/admin/update', 'ownerUpdate');
        Route::delete('/admin/delete', 'ownerDestroy');
        Route::get('/admin/shop', 'shopList');
        Route::get('/admin/shop/search', 'shopSearch');
        Route::delete('/admin/shop/delete', 'shopDestroy');
        Route::get('/admin/shop/detail/{shop_id}', 'shopDetail');
        Route::patch('/admin/shop/update/{shop_id}', 'shopUpdate');
    });
    Route::controller(ExportController::class)->group(function () {
        Route::post('/admin/export/owner/list', 'exportOwnerList');
        Route::post('/admin/export/shop/list', 'exportShopList');
    });
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/admin/mail', 'adminMail');
        Route::post('/admin/mail', 'adminSend');
    });
});

//QRコード読み取り後遷移するページ
Route::view('/qr', 'user.qr');