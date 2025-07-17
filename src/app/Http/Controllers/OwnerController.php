<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\ReservationSlot;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Services\ReservationSlotService;

class OwnerController extends Controller
{
    protected $slotService;

    public function __construct(ReservationSlotService $slotService)
    {
        $this->slotService = $slotService;
    }

    public function shopList()
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')
        ->where('user_id', $user->id)
        ->paginate(5);
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.shop_list', compact('shops', 'areas', 'genres'));
    }

    public function search(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')
        ->AreaSearch($request->area)
        ->GenreSearch($request->genre)
        ->KeywordSearch($request->keyword)
        ->paginate(5)
        ->appends($request->query());
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.shop_list', compact('shops', 'areas', 'genres'));
    }

    public function reserveList(Request $request, $shopId)
    {
        $date = $request->input('date');
        $target = $date ? Carbon::parse($date) : Carbon::today();
        $todayFormat = $target->isoFormat('Y年M月D日(dd)');

        $previousDay = $target->copy()->subDay()->format('Y-m-d');
        $nextDay = $target->copy()->addDay()->format('Y-m-d');

        $user = Auth::user();
        $shop = Shop::where('id', $shopId)
        ->where('user_id', $user->id)
        ->first();
        $reservations = Reservation::with('shop', 'user')
        ->where('shop_id', $shopId)
        ->where('date', $target)
        ->paginate(5);
        return view('owner.reserve_list', compact('todayFormat','previousDay', 'nextDay', 'shop', 'reservations'));
    }

    public function setting(Request $request, $shopId)
    {
        //
    }

    public function stop(Request $request, $shopId)
    {
        $date = $request->input('date');
        $target = $date ? Carbon::parse($date) : Carbon::today();
        $user = Auth::user();
        $shop = Shop::where('id', $shopId)
        ->where('user_id', $user->id)
        ->first();

        $stops = [];
        DB::transaction(function () use ($shopId, $target, &$stops) {
            $reservations = Reservation::where('shop_id', $shopId)
            ->where('date', $target)
            ->get();

            $slots = ReservationSlot::where('shop_id', $shopId)
            ->where('date', $target)
            ->get();

            ReservationSlot::where('shop_id', $shopId)
            ->where('date', $target->format('Y-m-d'))
            ->update(['is_active' => false]);

            if ($reservations->isEmpty()) {
                return;
            }

            foreach ($slots as $slot) {
                $slotReservations = $reservations->filter(function ($reservation) use ($slot) {
                    return $reservation->time == $slot->reserve_start;
                });
                $totalNumber = $slotReservations->sum('number');
                $totalGroup = $slotReservations->count();

                if ($totalNumber >= $slot->max_number) {
                    $stops[] = $slot->reserve_start . 'の予約が人数上限に達しています';
                }

                if ($totalGroup >= $slot->max_group) {
                    $stops[] = $slot->reserve_start . 'の予約が組数上限に達しています';
                }
            }
        });
        return redirect()->back()->with('success', '本日の予約を停止しました');
    }

    public function update(ReservationRequest $request, $reservationId)
    {
        $reservation = Reservation::with('shop')->find($reservationId);
        $data = $request->only(['date', 'time', 'number']);
        $update = $reservation->update($data);
        if ($update) {
            return redirect()->back()->with('success', '変更が完了しました');

        } else {
            return redirect()->back()->with('fail', '変更できませんでした');
        }
    }

    public function destroy(Request $request, $reservationId)
    {
        $reservation = Reservation::with('shop')->find($reservationId);
        $delete = $reservation->delete();
        if ($delete) {
            return redirect()->back()->with('success', '予約を削除しました');

        } else {
            return redirect()->back()->with('fail', '削除できませんでした');
        }
    }

    public function detail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();
        return view('owner.detail', compact('shop'));
    }

    public function edit(Request $request, $shopId)
    {
        $shop = Shop::find($shopId);
        $area = Area::firstOrCreate(['area' => $request->area]);
        $genre = Genre::firstOrCreate(['genre' => $request->genre]);

        $existingImage = str_replace('storage/', '', $shop->image);

        if ($request->hasFile('image')) {
            if ($shop->image && Storage::disk('public')->exists($existingImage)) {
                Storage::disk('public')->delete($existingImage);
            }
            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $fileName = $originalName . '.' . $extension;
            $i = 1;
            while (Storage::exists('images/' . $fileName)) {
                $fileName = $originalName . '_' . $i . '.' . $extension;
                $i++;
            }
            $path = $image->storeAs('images', $fileName, 'public');
        }
        $shop->update([
            'area_id' => $area->id,
            'genre_id' => $genre->id,
            'name' => $request->name,
            'image' => 'storage/images/' . $fileName,
            'overview' => $request->overview,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);
        return redirect('/owner')->with('success', '店舗情報の更新が完了しました');
    }

    public function newShop()
    {
        $user = Auth::user();
        return view('owner.create_shop');
    }

    public function create(Request $request)
    {
        $shop = DB::transaction(function () use ($request)
        {
            $userId = Auth::user()->id;
            $area = Area::firstOrCreate(['area' => $request->area]);
            $genre = Genre::firstOrCreate(['genre' => $request->genre]);

            $image = $request->file('image');
            $originalName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $fileName = $originalName . '.' . $extension;
            $i = 1;
            while (Storage::exists('images/' . $fileName)) {
                $fileName = $originalName . '_' . $i . '.' . $extension;
                $i++;
            }
            $path = $image->storeAs('images', $fileName, 'public');

            $shop = Shop::create([
                'user_id' => $userId,
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'name' => $request->name,
                'image' => 'storage/images/' . $fileName,
                'overview' => $request->overview,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);
            return $shop;
        });

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addYears(2)->endOfMonth();
        $this->slotService->generateSlots($shop, $startDate, $endDate);

        return redirect('/owner')->with('success', '新規店舗が登録されました');
    }
}
