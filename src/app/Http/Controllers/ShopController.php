<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Reservation;
use App\Models\ReservationSlot;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')->get();
        $areas = Area::all();
        $genres = Genre::all();
        $favoriteIds = $user ? $user->favorites->pluck('shop_id')->toArray() : [];
        $isAuth = Auth::check();
        return view('top', compact('shops', 'areas', 'genres', 'favoriteIds', 'isAuth'));
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
        $isAuth = $user ? true : false;
        return view('top', compact('shops', 'areas', 'genres', 'favoriteIds', 'isAuth'));
    }

    public function detail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();

        $slots = ReservationSlot::where('shop_id', $shopId)->get();
        $unique = $slots->unique(fn($slot) => $slot->reserve_start->format('H:i'))->values();

        foreach ($unique as $slot) {
            $reservedNumber = $slot->reservedNumber();
            $slot->reserved_number = $reservedNumber;
            $slot->remaining_number = max(0, $slot->max_number - $reservedNumber);
        }

        return view('user.detail', compact('shop', 'unique'));
    }

    public function review($shopId)
    {
        $shop = Shop::where('id', $shopId)->first();
        $reviews = Review::with('user')
        ->where('shop_id', $shopId)
        ->paginate(5);

        return view('user.review', compact('shop', 'reviews'));
    }

    public function sort(Request $request, $shopId)
    {
        $shop = Shop::where('id', $shopId)->first();
        $query = Review::with('user')
        ->where('shop_id', $shopId);

        switch ($request->sort) {
            case 'latest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'high':
                $query->orderBy('evaluation', 'desc');
                break;
            case 'low':
                $query->orderBy('evaluation', 'asc');
                break;
        }
        $reviews = $query->paginate(5);
        return view('user.review', compact('shop', 'reviews'));
    }
}
