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
use App\Models\ReservationSlot;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function reserve(ReservationRequest $request, $shopId)
    {
        $userId = Auth::user()->id;
        $shop = Shop::find($shopId);
        $date = Carbon::parse($request->date)->format('Y-m-d');

        $slot = ReservationSlot::where('shop_id', $shopId)
        ->where('date', $date)
        ->where('reserve_start', $request->time)
        ->first();

        $reserve = Reservation::create([
            'user_id' => $userId,
            'shop_id' => $shopId,
            'reservation_slot_id' => $slot->id,
            'date' => $date,
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

        $favoriteIds = $user->favorites->pluck('shop_id');
        $favoriteShops = Shop::with('area', 'genre')
        ->whereIn('id', $favoriteIds)
        ->get();

        $reservations = Reservation::with('shop')
        ->where('user_id', $user->id)
        ->get();

        $slotsShopId = [];
        foreach ($reservations as $reservation) {
            $shopId = $reservation->shop_id;

            $slots = ReservationSlot::where('shop_id', $shopId)->get();
            $unique = $slots->unique(fn($slot) => $slot->reserve_start->format('H:i'))->values();

            foreach ($unique as $slot) {
                $reservedNumber = $slot->reservedNumber();
                $slot->reserved_number = $reservedNumber;
                $slot->remaining_number = max(0, $slot->max_number - $reservedNumber);
            }
            $slotsShopId[$shopId] = $unique;
        }

        return view('user.mypage', compact('reservations', 'favoriteShops', 'slotsShopId'));
    }

    public function update(ReservationRequest $request, $reservationId)
    {
        $reservation = Reservation::with('slot')
        ->find($reservationId);
        $shopId = $reservation->shop_id;
        $date = Carbon::parse($request->date)->format('Y-m-d');
        $time = $request->time;

        $slot = ReservationSlot::where('shop_id', $shopId)
        ->where('date', $date)
        ->where('reserve_start', $time)
        ->first();

        $update = $reservation->update([
            'date' => $date,
            'time' => $time,
            'number' => $request->number,
            'reservation_slot_id' => $slot->id,
        ]);

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
            return redirect()->back()->with('fail', 'ご予約を削除できませんでした');
        }
    }
}
