<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\NotificationRequest;
use Illuminate\Support\Facades\Auth;
use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Review;
use App\Models\Shop;
use App\Models\Reservation;

class NotificationController extends Controller
{
    public function ownerMail()
    {
        $ownerId = Auth::id();
        $shops = Shop::where('user_id', $ownerId)->get();

        $sendTargets = [
            'favorites' => 'お気に入り登録者',
            'reviewer' => '口コミ投稿者',
            'admin' => '管理者',
        ];
        return view('mails.owner_send', compact('shops', 'sendTargets'));
    }

    public function ownerSend(NotificationRequest $request)
    {
        $ownerId = Auth::id();
        $shopId = $request->input('shop_id');
        $sendTo = $request->input('send-to');
        $subject = $request->input('subject');
        $body = $request->input('body');

        $shop = Shop::find($shopId);
        $shopName = $shop->name;
        $fullBody = "【{$shopName}からのお知らせ】\n\n{$body}";

        switch ($sendTo) {
            case 'favorites':
                $favoriteIds = Favorite::where('shop_id', $shopId)->get();
                $users = User::whereIn('id', $favoriteIds)->get();
                break;
            case 'reviewer':
                $reviewerIds = Review::where('shop_id', $shopId)->get();
                $users = User::whereIn('id', $reviewerIds)->get();
                break;
            case 'admin':
                $users = User::where('role', 3)->get();
                break;
            default:
                $users = collect();
                break;
        }
        foreach ($users as $user) {
            Mail::to($user->email)
            ->send(new NotificationMail($subject, $fullBody));
        }
        return redirect('/owner')->with('success', 'メール配信が完了しました');
    }


    public function adminMail()
    {
        $sendTargets = [
            'owner' => '店舗代表者',
            'user' => 'ユーザ',
            'both' => '店舗代表者とユーザ',
        ];
        return view('mails.admin_send', compact('sendTargets'));
    }

    public function adminSend(NotificationRequest $request)
    {
        $sendTo = $request->input('send-to');
        $subject = $request->input('subject');
        $body = $request->input('body');

        switch ($sendTo) {
            case 'owner':
                $users = User::where('role', 2)->get();
                break;
            case 'user':
                $users = User::where('role', 1)->get();
                break;
            case 'both':
                $users = User::whereIn('role', [1, 2])->get();
                break;
            default:
                $users = collect();
                break;
        }
        foreach ($users as $user) {
            Mail::to($user->email)
            ->send(new NotificationMail($subject, $body));
        }
        return redirect('/admin')->with('success', 'メール配信が完了しました');
    }
}
