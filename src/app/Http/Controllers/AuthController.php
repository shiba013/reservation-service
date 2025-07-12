<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function owner()
    {
        session(['login_type' => 'owner']);
        return view('auth.login');
    }

    public function loginOwner(LoginRequest $request)
    {
        $user = $request->only(['email', 'password']);
        if (Auth::attempt($user)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 2 || $user->role === 3) {
                return redirect('/owner');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => '店舗代表者としての権限が必要です',
                ])->withInput();
            }
        }
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
    }

    public function admin()
    {
        session(['login_type' => 'admin']);
        return view('auth.login');
    }

    public function loginAdmin(LoginRequest $request)
    {
        $user = $request->only(['email', 'password']);
        if (Auth::attempt($user)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->role === 3) {
                return redirect('/admin');
            } else {
                Auth::logout();
                return back()->withError([
                    'email' => '管理者としての権限が必要です',
                ])->withInput();
            }
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        $role = Auth::user()->role;
        Auth::guard('web')->logout();

        $loginType = session('login_type');

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($loginType === 'user') {
            return redirect('/login');

        } elseif ($loginType === 'owner') {
            return redirect('/owner/login');

        } elseif ($loginType === 'admin') {
            return redirect('/admin/login');
        }
    }
}
