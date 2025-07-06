<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Favorite;
use App\Models\Reservation;

class UserController extends Controller
{
    public function reserve(ReservationRequest $request, $shopId)
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

    public function favorite($shopId)
    {
        $user = Auth::user();
        $favorite = Favorite::where('user_id', $user->id)->where('shop_id', $shopId)->first();
        if($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'shop_id' => $shopId,
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function mypage()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)->get();
        $favoriteIds = $user->favorites->pluck('shop_id');
        $favoriteShops = Shop::with('area', 'genre')
        ->whereIn('id', $favoriteIds)
        ->get();
        return view('user.mypage', compact('reservations', 'favoriteShops'));
    }

    public function edit()
    {
        //
    }

    public function update(ReservationRequest $request, $reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $data = $request->only(['date,', 'time', 'number']);
        $update = $reservation->update($data);
        if ($update) {
            return redirect()->back()->with('success', 'ご予約内容を更新しました');

        }else {
            return redirect()->back()->with('fail', 'ご予約内容を更新できませんでした');
        }
    }

    public function destroy(Request $request, $reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $delete = $reservation->delete();
        if ($delete) {
            return redirect()->back()->with('success', 'ご予約を削除しました');

        } else {
            return redirect()->back()->with('success', 'ご予約を削除できませんでした');
        }
    }
}
