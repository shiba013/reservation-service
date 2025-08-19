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
use App\Models\Review;
use Illuminate\Support\Carbon;

use Illuminate\Support\Facades\Log;

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

    public function reserveSlots(Request $request, $reservationId)
    {
        $date = $request->query('date');

        $currentReservation = Reservation::find($reservationId);
        $shopId = $currentReservation->shop_id;
        $currentNumber = $currentReservation->number;

        $slots = ReservationSlot::where('shop_id', $shopId)
        ->whereDate('date', $date)
        ->orderBy('reserve_start')
        ->get();

        $slots = $slots->map(function ($slot) use ($currentReservation, $currentNumber, $date) {
            $reservedNumber = $slot->reservedNumber($date);
            $slot->reserved_number = $reservedNumber;
            $slot->remaining_number = max(0, $slot->max_number - $reservedNumber);

            return [
                'time' => $slot->reserve_start->format('H:i'),
                'remaining_number' => $slot->remaining_number,
            ];
        });
        return response()->json($slots);
    }

    public function reserveUpdate(ReservationRequest $request, $reservationId)
    {
        $action = $request->input('action');

        if ($action === '変更') {
            return redirect()->back()->with([
                'confirm_reservation_id' => $reservationId,
                'confirm_data' => $request->only(['date', 'time', 'number']),
            ]);
        }
        if ($action === '確定') {
            $reservation = Reservation::with('slot')
            ->find($reservationId);
            $shopId = $reservation->shop_id;
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $time = Carbon::parse($request->time)->format('H:i:s');

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
    }

    public function reserveDestroy(Request $request, $reservationId)
    {
        $reservation = Reservation::find($reservationId);
        $delete = $reservation->delete();
        if ($delete) {
            return redirect()->back()->with('success', 'ご予約を削除しました');

        } else {
            return redirect()->back()->with('fail', 'ご予約を削除できませんでした');
        }
    }

    public function reviewCreate(Request $request, $shopId)
    {
        Review::create([
            'user_id' => auth()->id(),
            'shop_id' => $shopId,
            'evaluation' => $request->evaluation,
            'comment' => $request->comment,
        ]);
        session()->flash('success', '口コミを投稿しました');
        return response()->json(['status' => 'success']);
    }

    public function reviewUpdate(Request $request)
    {
        $review = Review::find($request->id);
        $update = $review->update([
            'evaluation' => $request->evaluation,
            'comment' => $request->comment,
        ]);
        session()->flash('success', '口コミ内容を変更しました');
        return response()->json(['status' => 'success']);
    }

    public function reviewDestroy(Request $request)
    {
        $delete = Review::find($request->id)->delete();
        if ($delete) {
            return redirect()->back()->with('success', '口コミを削除しました');
        } else {
            return redirect()->back()->with('fail', '口コミを削除できませんでした');
        }
    }
}
