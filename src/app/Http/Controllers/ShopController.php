<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')->get();
        $areas = Area::all();
        $genres = Genre::all();
        $favoriteIds = $user ? $user->favorites->pluck('shop_id')->toArray() : [];
        return view('top', compact('shops', 'areas', 'genres', 'favoriteIds'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')
        ->AreaSearch($request->area)
        ->GenreSearch($request->genre)
        ->KeywordSearch($request->keyword)
        ->get();
        $areas = Area::all();
        $genres = Genre::all();
        $favoriteIds = $user ? $user->favorites->pluck('shop_id')->toArray() : [];
        return view('top', compact('shops', 'areas', 'genres', 'favoriteIds'));
    }


    public function detail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();
        return view('user.detail', compact('shop'));
    }
}
