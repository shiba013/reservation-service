<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ShopRequest;
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
        ->withCount('favorites')
        ->withAvg('reviews', 'evaluation')
        ->withCount('reviews')
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
        ->withCount('favorites')
        ->withAvg('reviews', 'evaluation')
        ->withCount('reviews')
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

        $query = Reservation::with('shop', 'user')
        ->where('shop_id', $shopId)
        ->where('date', $target->format('Y-m-d'));

        $slots = ReservationSlot::where('shop_id', $shopId)
        ->where('date', $target->format('Y-m-d'))
        ->get();

        switch ($request->sort) {
            case 'asc':
                $query->orderBy('time', 'asc');
                break;
            case 'desc':
                $query->orderBy('time', 'desc');
                break;
        }
        $reservations = $query->paginate(5)->appends([
            'date' => $request->input('date'),
            'sort' => $request->input('sort'),
        ]);

        return view('owner.reserve_list', compact('todayFormat','previousDay', 'nextDay', 'shop', 'reservations', 'slots'));
    }

    public function setting(Request $request, $shopId)
    {
        $date = $request->input('date');
        $target = $date ? Carbon::parse($date) : Carbon::today();
        $user = Auth::user();
        $shop = Shop::where('id', $shopId)
        ->where('user_id', $user->id)
        ->first();

        $slots = $request->input('slots', []);
        DB::transaction(function () use ($slots, $shopId, $target) {
            foreach ($slots as $slotId => $isActive) {
                ReservationSlot::where('id', $slotId)
                ->where('shop_id', $shopId)
                ->where('date', $target->format('Y-m-d'))
                ->update(['is_active' => (bool)$isActive]);
            }
        });
        return redirect()->back()->with('success', '本日の受付設定を変更しました');
    }

    public function update(ReservationRequest $request, $reservationId)
    {
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

    public function edit(ShopRequest $request, $shopId)
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
        return redirect('/owner')->with('success', '店舗情報を変更しました');
    }

    public function newShop()
    {
        $user = Auth::user();
        return view('owner.create_shop');
    }

    public function create(ShopRequest $request)
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
                'end_time' => $request->end_time,
            ]);
            return $shop;
        });

        $startDate = Carbon::today();
        $endDate = $startDate->copy()->addYears(2)->endOfMonth();
        $this->slotService->generateSlots($shop, $startDate, $endDate);

        return redirect('/owner')->with('success', '新規店舗が登録されました');
    }
}
