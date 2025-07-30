<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function ownerMail()
    {
        $sendTargets = [
            'favorites' => 'お気に入り登録者',
            'reviews' => '口コミ投稿者',
            'admin' => '管理者',
        ];

        return view('mails.owner_send', compact('sendTargets'));
    }

    public function ownerSend(Request $request)
    {
        $user = Auth::user();
        $sendTarget = collect();
    }

    public function adminMail()
    {
        $sendTargets = [
            'owner' => '店舗代表者',
            'user' => 'ユーザ',
        ];

        return view('mails.admin_send', compact('sendTargets'));
    }

    public function adminSend(Request $request)
    {
        //
    }
}
