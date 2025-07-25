@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_owner.css') }}">
@endsection

@section('content')
<div class="register">
    <div class="register__inner">
        <form action="/admin/register" method="post" class="register-form">
            @csrf
            <div class="register__title">
                <h2 class="title__logo">Registration</h2>
            </div>
            <div class="register-form__group">
                <label for="name" class="register-form__label">
                    <img src="{{ asset('icon/person-fill.png') }}" alt="person" class="register-icon">
                </label>
                <input type="text" name="name" id="name" class="register-form__input"
                value="{{ old('name') }}" placeholder="Username">
                <p class="alert">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label for="email" class="register-form__label">
                    <img src="{{ asset('icon/envelope-fill.png') }}" alt="email" class="register-icon">
                </label>
                <input type="text" name="email" id="email" class="register-form__input"
                value="{{ old('email') }}" placeholder="Email">
                <p class="alert">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label for="password" class="register-form__label">
                    <img src="{{ asset('icon/key.png') }}" alt="key" class="register-icon">
                </label>
                <input type="password" name="password" id="password" class="register-form__input"
                value="{{ old('password') }}" placeholder="Password">
                <p class="alert">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <input type="submit" value="登録" class="register-form__button">
            </div>
        </form>
    </div>
</div>
@endsection