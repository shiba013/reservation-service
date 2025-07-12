@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
@if(session('message'))
<div class="session">
    <p class="session__message">
        {{ session('message') }}
    </p>
</div>
@endif
<div class="login">
    <div class="login__inner">
        @if (session('login_type') === 'user')
        <form action="/login" method="post" class="login-form">
            @csrf
            <div class="login__title">
                <h2 class="title__logo">Login</h2>
            </div>
            <div class="login-form__group">
                <label for="email" class="login-form__label">
                    <img src="{{ asset('icon/envelope-fill.png') }}" alt="email" class="login-icon">
                </label>
                <input type="text" name="email" id="email" class="login-form__input"
                value="{{ old('email') }}" placeholder="Email">
                <p class="alert">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label for="password" class="login-form__label">
                    <img src="{{ asset('icon/key.png') }}" alt="key" class="login-icon">
                </label>
                <input type="password" name="password" id="password" class="login-form__input"
                value="{{ old('password') }}" placeholder="Password">
                <p class="alert">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <input type="submit" value="ログイン" class="login-form__button">
            </div>
        </form>
        @elseif (session('login_type') === 'owner')
        <form action="/owner/login" method="post" class="login-form">
            @csrf
            <div class="login__title">
                <h2 class="title__logo">Owners Login</h2>
            </div>
            <div class="login-form__group">
                <label for="email" class="login-form__label">
                    <img src="{{ asset('icon/envelope-fill.png') }}" alt="email" class="login-icon">
                </label>
                <input type="text" name="email" id="email" class="login-form__input"
                value="{{ old('email') }}" placeholder="Email">
                <p class="alert">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label for="password" class="login-form__label">
                    <img src="{{ asset('icon/key.png') }}" alt="key" class="login-icon">
                </label>
                <input type="password" name="password" id="password" class="login-form__input"
                value="{{ old('password') }}" placeholder="Password">
                <p class="alert">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <input type="submit" value="ログイン" class="login-form__button">
            </div>
        </form>
        @elseif (session('login_type') === 'admin')
        <form action="/admin/login" method="post" class="login-form">
            @csrf
            <div class="login__title">
                <h2 class="title__logo">Admins Login</h2>
            </div>
            <div class="login-form__group">
                <label for="email" class="login-form__label">
                    <img src="{{ asset('icon/envelope-fill.png') }}" alt="email" class="login-icon">
                </label>
                <input type="text" name="email" id="email" class="login-form__input"
                value="{{ old('email') }}" placeholder="Email">
                <p class="alert">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label for="password" class="login-form__label">
                    <img src="{{ asset('icon/key.png') }}" alt="key" class="login-icon">
                </label>
                <input type="password" name="password" id="password" class="login-form__input"
                value="{{ old('password') }}" placeholder="Password">
                <p class="alert">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <input type="submit" value="ログイン" class="login-form__button">
            </div>
        </form>
        @endif
    </div>
</div>
@endsection