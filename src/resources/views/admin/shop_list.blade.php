@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_list.css') }}">
@endsection

@section('header')
<div class="search">
    <form action="/admin/shop/search" method="get" class="search-form" id="searchForm">
        <nav class="header__nav">
            <div class="select-wrapper">
                <select name="area" id="areaSelect" class="search-form__select">
                    @if (!request('area'))
                    <option value="" disabled selected hidden>All area</option>
                    @else
                    <option value="">リセット</option>
                    @endif
                    @foreach ($areas as $area)
                    <option value="{{ $area->id }}"
                    {{ request('area') == $area->id ? 'selected' : '' }}>
                        {{ $area->area }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="select-wrapper">
                <select name="genre" id="genreSelect" class="search-form__select">
                    @if (!request('genre'))
                    <option value="" disabled selected hidden>All genre</option>
                    @else
                    <option value="">リセット</option>
                    @endif
                    @foreach ($genres as $genre)
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
                <a href="/admin/shop" class="reset">
                    <img src="{{ asset('icon/close.png') }}" alt="reset" class="reset-icon">
                </a>
            </div>
        </nav>
    </form>
</div>
@endsection

@section('content')
@if (session('success'))
<div class="success-session">
    <p class="session__message">
        {{ session('success') }}
    </p>
</div>
@elseif (session('fail'))
<div class="fail-session">
    <p class="session__message">
        {{ session('fail') }}
    </p>
</div>
@endif
<div class="shop-list">
    <h2 class="shop-list__title">店舗一覧</h2>
    <div class="shop-list__group">
        <form action="/admin/export/shop/list" method="post" class="export-form">
            @csrf
            <input type="submit" value="CSV出力" class="export-form__btn">
        </form>
        <div class="paginate">
            {{ $shops->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="shop-list__group">
        <table class="shop-table">
            <tr class="shop-table__row">
                <th class="table-title">店名</th>
                <th class="table-title">エリア</th>
                <th class="table-title">ジャンル</th>
                <th class="table-title">お気に入り<br>登録者数</th>
                <th class="table-title">評価平均</th>
                <th class="table-title">詳細</th>
            </tr>
            @foreach ($shops as $shop)
            <tr class="shop-table__row">
                <td class="table-data">{{ $shop->name }}</td>
                <td class="table-data">{{ $shop->area->area }}</td>
                <td class="table-data">{{ $shop->genre->genre }}</td>
                <td class="table-data">{{ $shop->favorites->count() }}</td>
                <td class="table-data">
                    {{ number_format($shop->reviews_avg_evaluation, 1) }}点<br>
                    <span class="table-data__span">（{{ $shop->reviews->count() }}）</span>
                </td>
                <td class="table-data">
                    <a href="/admin/shop/detail/{{ $shop->id }}" class="table-btn__detail">詳細</a>
                    <button type="button" class="table-btn__delete" onclick="openShopDelete({{ $shop->id }})">削除</button>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@foreach ($shops as $shop)
<div class="overlay" id="overlay-delete{{ $shop->id }}">
    <div class="delete" id="delete-form{{ $shop->id }}">
        <form action="/admin/shop/delete" method="post" class="delete-form">
            @csrf
            @method('DELETE')
            <div class="delete-form__group">
                <p class="delete-message">本当に削除しますか？</p>
            </div>
            <div class="delete-form__group">
                <p class="delete-message">店名：{{ $shop->name }}</p>
            </div>
            <div class="delete-form_group">
                <div class="button">
                    <input type="button" value="キャンセル" class="cancel-btn" onclick="closeShopDelete({{ $shop->id }})">
                    <input type="submit" value="削除" class="delete-btn">
                    <input type="hidden" name="id" value="{{ $shop->id }}">
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('js/search_shops.js') }}"></script>
<script src="{{ asset('js/admin/shop_list.js') }}"></script>
@endsection