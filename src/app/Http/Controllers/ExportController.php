<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\User;


class ExportController extends Controller
{
    public function exportShop(Request $request)
    {
        $user = Auth::user();
        $shops = Shop::with('area', 'genre')
        ->withCount('favorites')
        ->where('user_id', $user->id)
        ->get();

        $columns = ['店名', 'エリア', 'ジャンル', '概要', '営業開始時間', '営業終了時間', 'お気に入り登録者数'];

        $response = new StreamedResponse(function () use ($shops, $columns) {
            $file = fopen('php://output', 'w');
            mb_convert_variables('SJIS-win', 'UTF-8', $columns);
            fputcsv($file, $columns);

            foreach ($shops as $shop) {
                $row = [
                    $shop->name,
                    $shop->area->area,
                    $shop->genre->genre,
                    $shop->overview,
                    $shop->start_time->format('H:i'),
                    $shop->end_time->format('H:i'),
                    $shop->favorites_count,
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $row);
                fputcsv($file, $row);
            }
            fclose($file);
        });
        $fileName = rawurlencode("{$user->name}様_shop-list.csv");
        $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");
        return $response;
    }

    public function exportReserve(Request $request, $shopId)
    {
        $date = $request->input('date');
        $target = $date ? Carbon::parse($date) : Carbon::now();
        $reservations = Reservation::with('shop', 'user')
        ->where('shop_id', $shopId)
        ->where('date', $target)
        ->get();

        $shop = Shop::where('id', $shopId)->first();

        $columns = ['日付', '予約時間', '予約名', '人数'];

        $response = new StreamedResponse(function () use ($reservations, $columns) {
            $file = fopen('php://output', 'w');
            mb_convert_variables('SJIS-win', 'UTF-8', $columns);
            fputcsv($file, $columns);

            foreach ($reservations as $reservation) {
                $row = [
                    $reservation->date->format('Y-m-d'),
                    $reservation->time->format('H:i'),
                    $reservation->user->name,
                    $reservation->number,
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $row);
                fputcsv($file, $row);
            }
            fclose($file);
        });
        $fileName = rawurlencode("店名：{$shop->name}_reserve-list.csv");
        $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");
        return $response;
    }

    public function exportOwnerList(Request $request)
    {
        $users = User::with('shops')->get();
        $columns = ['名前', 'メールアドレス', '所有店舗数', '店舗名'];

        $response = new StreamedResponse(function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            mb_convert_variables('SJIS-win', 'UTF-8', $columns);
            fputcsv($file, $columns);

            foreach ($users as $user) {
                $shopNames = $user->shops->pluck('name')->implode(', ');
                $row = [
                    $user->name,
                    $user->email,
                    $user->shops->count(),
                    $shopNames,
                ];
                mb_convert_variables('SJIS-win', 'UTF-8', $row);
                fputcsv($file, $row);
            }
            fclose($file);
        });
        $fileName = rawurlencode('owner-list.csv');
        $response->headers->set('Content-Type', 'text/csv; charset=Shift_JIS');
        $response->headers->set('Content-Disposition', "attachment; filename={$fileName}");
        return $response;
    }

    public function exportShopList()
    {
        //
    }
}
