<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateOwnerRequest;
use App\Http\Requests\ShopRequest;
use App\Models\User;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function ownerList()
    {
        $users = User::with('shops')
        ->where('role', 2)
        ->paginate(5);
        return view('admin.owner_list', compact('users'));
    }

    public function search(Request $request)
    {
        $users = User::with('shops')
        ->where('role', 2)
        ->keywordSearch($request->keyword)
        ->paginate(5);
        return view('admin.owner_list', compact('users'));
    }

    public function create(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->input('password')),
            'role' => 2,
        ]);
        return redirect('/admin')->with('success', '店舗代表者を登録しました');
    }

    public function ownerUpdate(UpdateOwnerRequest $request)
    {
        $user = User::find($request->id);
        $update = $user->update($request->only(
            'name', 'email',
        ));
        if ($update) {
            return redirect()->back()->with('success', '店舗代表者を変更しました');
        } else {
            return redirect()->back()->with('fail', '店舗代表者を更新できませんでした');
        }
    }

    public function ownerDestroy(Request $request)
    {
        $delete = User::find($request->id)->delete();
        if ($delete) {
            return redirect()->back()->with('success', '店舗代表者を削除しました');
        } else {
            return redirect()->back()->with('fail', '店舗代表者を削除できませんでした');
        }
    }

    public function shopList()
    {
        $shops = Shop::with('user', 'area', 'genre')
        ->withCount('favorites')
        ->paginate(5);
        $areas = Area::all();
        $genres = Genre::all();
        return view('admin.shop_list', compact('shops', 'areas', 'genres'));
    }

    public function shopSearch(Request $request)
    {
        $shops = Shop::with('user', 'area', 'genre')
        ->AreaSearch($request->area)
        ->GenreSearch($request->genre)
        ->KeywordSearch($request->keyword)
        ->paginate(5);
        $areas = Area::all();
        $genres = Genre::all();
        return view('admin.shop_list', compact('shops', 'areas', 'genres'));
    }

    public function shopDestroy(Request $request)
    {
        $delete = Shop::find($request->id)->delete();
        if ($delete) {
            return redirect()->back()->with('success', '店舗を削除しました');
        } else {
            return redirect()->back()->with('fail', '店舗を削除できませんでした');
        }
    }

    public function shopDetail($shopId)
    {
        $shop = Shop::with('area', 'genre')
        ->where('id', $shopId)
        ->first();
        return view('admin.shop_detail', compact('shop'));
    }

    public function shopUpdate(ShopRequest $request, $shopId)
    {
        $shop = shop::find($shopId);
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
        return redirect('/admin/shop')->with('success', '店舗情報を変更しました');
    }
}
