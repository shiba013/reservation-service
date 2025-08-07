<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }
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

            if (!$user->hasVerifiedEmail()) {
                $user->sendEmailVerificationNotification();
            }
            if ($user->role === 3) {
                return redirect('/admin');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => '管理者としての権限が必要です',
                ])->withInput();
            }
        }
        return back()->withErrors([
            'email' => 'ログイン情報が登録されていません',
        ])->withInput();
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

    public function email(Request $request)
    {
        $user = Auth::user();
        return view('auth.verify-email', compact('user'));
    }

    public function verification(EmailVerificationRequest $request)
    {
        $request->fulfill();
        $loginType = session('login_type');

        if ($loginType === 'user') {
            return redirect('/thanks');

        } elseif ($loginType === 'owner') {
            return redirect('/owner');

        } elseif ($loginType === 'admin') {
            return redirect('/admin');
        }
    }

    public function resend(Request $request)
    {
        $loginType = session('login_type');

        if ($request->user()->hasVerifiedEmail()) {
            if ($loginType === 'user') {
                return redirect('/thanks');

            } elseif ($loginType === 'owner') {
                return redirect('/owner');

            } elseif ($loginType === 'admin') {
                return redirect('/admin');
            }
        }
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
