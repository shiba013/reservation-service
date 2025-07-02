@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/top.css') }}">
@endsection

@section('header')
<div class="search">
    <form action="/search" method="get" class="search-form" id="searchForm">
        <nav class="header__nav">
            <div class="select-wrapper">
                <select name="area" id="areaSelect" class="search-form__select">
                    @if(!request('area'))
                    <option value="" disabled selected hidden>All area</option>
                    @else
                    <option value="">リセット</option>
                    @endif
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}"
                    {{ request('area') == $area->id ? 'selected' : '' }}>
                        {{ $area->area }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="select-wrapper">
                <select name="genre" id="genreSelect" class="search-form__select">
                    @if(!request('genre'))
                    <option value="" disabled selected hidden>All genre</option>
                    @else
                    <option value="">リセット</option>
                    @endif
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}"
                    {{ request('genre') == $genre->id ? 'selected' : '' }}>
                        {{ $genre->genre }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="input__search">
                <img src="{{ asset('icon/search.png') }}" alt="search" class="search-icon">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="search-form__input" placeholder="Search ..." id="keywordInput">
            </div>
        </nav>
    </form>
</div>
@endsection

@section('content')
<div class="shops-list">
    @foreach($shops as $shop)
    <div class="shops-list__inner">
        <div class="shop-card">
            <img src="{{ asset($shop->image) }}" alt="店舗画像" class="shop-card__img">
            <div class="shop-card__info">
                <h3 class="shop-card__name">{{ $shop->name }}</h3>
                <p class="shop-card__area">
                    <span class="shop-card__span">#</span>
                    {{ $shop->area->area }}
                </p>
                <p class="shop-card__genre">
                    <span class="shop-card__span">#</span>
                    {{ $shop->genre->genre }}
                </p>
                <a href="/detail/{{ $shop->id }}" class="shop__link">詳しく見る</a>
            </div>
        </div>
        <form action="/favorite/{{ $shop->id }}" method="post" class="favorite-form">
            @csrf
            <input type="checkbox" name="favorite" id="favorite" class="favorite__btn">
            <label for="favorite">
                <img src="{{ asset('icon/heart.png') }}" alt="heart"
                class="favorite__icon
                {{ $shop->favorites->pluck('user_id')->contains(Auth()->id()) ? 'wasFavorite' : '' }}"
                onclick="this.closest('form').submit();">
            </label>
        </form>
    </div>
    @endforeach
</div>
@endsection
<script src="{{ asset('js/top.js') }}"></script>