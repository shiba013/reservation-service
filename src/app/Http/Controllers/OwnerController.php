<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Shop;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
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

    public function reserveList()
    {
        return view('owner.reserve_list');
    }

    public function detail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();
        return view('owner.detail', compact('shop'));
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
            $directory = 'public/images';
            $fileName = $originalName . '.' . $extension;
            $i = 1;
            while (Storage::exists($directory . '/' . $fileName)) {
                $fileName = $originalName . '_' . $i . '.' . $extension;
                $i++;
            }
            $path = $image->storeAs($directory, $fileName);

            $shop = Shop::create([
                'user_id' => $userId,
                'area_id' => $area->id,
                'genre_id' => $genre->id,
                'name' => $request->name,
                'image' => $fileName,
                'overview' => $request->overview,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);
            return $shop;
        });
        return redirect('/owner')->with('success', '新規店舗が登録されました');
    }
}
