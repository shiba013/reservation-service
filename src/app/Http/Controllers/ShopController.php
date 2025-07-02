<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('area', 'genre')->get();
        $areas = Area::all();
        $genres = Genre::all();
        return view('top', compact('shops', 'areas', 'genres'));
    }

    public function favorite($shopId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->first();
        if($favorite) {
            $favorite->delete();
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
        }
        return back();
    }

        public function detail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();
        return view('user.detail', compact('shop'));
    }

    public function search(Request $request)
    {
        $shops = Shop::with('area', 'genre')
        ->AreaSearch($request->area)
        ->GenreSearch($request->genre)
        ->KeywordSearch($request->keyword)
        ->get();

        $areas = Area::all();
        $genres = Genre::all();

        return view('top', compact('shops', 'areas', 'genres'));
    }

    public function reserve(Request $request, $shopId)
    {
        $userId = Auth::user()->id;
        $shop = shop::find($shopId);
        $reserve = Reservation::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'date' => $request->date,
            'time' => $request->time,
            'number' => $request->number,
        ]);
        return redirect('/done');
    }

    public function done()
    {
        return view('user.done');
    }

    public function mypage()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->get();
        $favorites = $user->wasFavorite()
        ->with('area', 'genre')
        ->get();
        return view('user.mypage', compact('reservations', 'favorites'));
    }
}
