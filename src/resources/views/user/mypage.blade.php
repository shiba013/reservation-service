@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <h1 class="mypage__title">{{ Auth::user()->name }}さん</h1>
    <section class="my-reserve">
        <h2 class="title__logo">予約状況</h2>
        @foreach($reservations as $reservation)
        <div class="my-reserve__group">
            <div class="reserve__title">
                <img src="{{ asset('icon/clock.svg') }}" alt="時計画像" class="clock__icon">
                <h3 class="sub-title">予約</h3>
                <img src="{{ asset('icon/close.png') }}" alt="閉じるボタン" class="close__icon">
            </div>
            <table class="reserve-table">
                <tr class="reserve-table__row">
                    <th class="table__title">Shop</th>
                    <td class="table__data">{{ $reservation->shop->name }}</td>
                </tr>
                <tr class="reserve-table__row">
                    <th class="table__title">Data</th>
                    <td class="table__data">{{ $reservation->date->format('Y-m-d') }}</td>
                </tr>
                <tr class="reserve-table__row">
                    <th class="table__title">Time</th>
                    <td class="table__data">{{ $reservation->time->format('H:i') }}</td>
                </tr>
                <tr class="reserve-table__row">
                    <th class="table__title">Number</th>
                    <td class="table__data">
                        {{ $reservation->number }}
                        <span class="table__data__span">人</span>
                    </td>
                </tr>
            </table>
        </div>
        @endforeach
    </section>
    <section class="my-favorite">
        <h2 class="title__logo">お気に入り店舗</h2>
        <div class="my-favorite__card-group">
            @foreach($favoriteShops as $shop)
            <div class="favorite-card">
                <img src="{{ asset($shop->image) }}" alt="店舗画像" class="favorite-card__img">
                <div class="favorite-card__info">
                    <h3 class="favorite-card__name">{{ $shop->name }}</h3>
                    <p class="favorite-card__area">
                        <span class="favorite-card__span">#</span>
                        {{ $shop->area->area }}
                    </p>
                    <p class="favorite-card__genre">
                        <span class="favorite-card__span">#</span>
                        {{ $shop->genre->genre }}
                    </p>
                    <a href="/detail/{{ $shop->id }}" class="shop__link">詳しく見る</a>
                </div>
                <button class="favorite-button" data-shop-id="{{ $shop->id }}"
                data-wasFavorite="1">
                    <img src="{{ asset('icon/heart.png') }}" alt="heart"
                    class="favorite-icon on">
                </button>
            </div>
            @endforeach
        </div>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/favorite.js') }}"></script>
@endsection