@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/shop_list.css') }}">
@endsection

@section('header')
<div class="search">
    <form action="/owner/search" method="get" class="search-form" id="searchForm">
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
        <form action="" method="post" class="export-form">
            @csrf
            <input type="hidden" name="data" value="">
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
                <th class="table-title">お気に入り<br>登録数</th>
                <th class="table-title">予約一覧</th>
                <th class="table-title">詳細</th>
            </tr>
            @foreach ($shops as $shop)
            <tr class="shop-table__row">
                <td class="table-data">{{ $shop->name }}</td>
                <td class="table-data">{{ $shop->area->area }}</td>
                <td class="table-data">{{ $shop->genre->genre }}</td>
                <td class="table-data"></td>
                <td class="table-data">
                    <a href="/owner/reserve" class="table__link">予約一覧</a>
                </td>
                <td class="table-data">
                    <a href="/owner/detail/{{ $shop->id }}" class="table__link">詳細</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/search_shops.js') }}"></script>
@endsection