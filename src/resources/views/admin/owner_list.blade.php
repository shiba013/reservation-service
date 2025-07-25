@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/owner_list.css') }}">
@endsection

@section('header')
<div class="search">
    <form action="/admin/search" method="get" class="search-form" id="searchForm">
        <div class="input__search">
            <img src="{{ asset('icon/search.png') }}" alt="search" class="search-icon">
            <input type="text" name="keyword" value="{{ request('keyword') }}" class="search-form__input" placeholder="Search ..." id="keywordInput">
            <a href="/admin" class="reset">
                <img src="{{ asset('icon/close.png') }}" alt="reset" class="reset-icon">
            </a>
        </div>
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
<div class="owner-list">
    <h2 class="owner-list__title">店舗代表者一覧</h2>
    <div class="owner-list__group">
        <form action="/admin/export/owner/list" method="post" class="export-form">
            @csrf
            <input type="submit" value="CSV出力" class="export-form__btn">
        </form>
        <div class="paginate">
            {{ $users->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="owner-list__group">
        <table class="owner-table">
            <tr class="owner-table__row">
                <th class="table-title">店舗代表者名</th>
                <th class="table-title">メールアドレス</th>
                <th class="table-title">詳細</th>
            </tr>
            @foreach ($users as $user)
            <tr class="shop-table__row">
                <td class="table-data">{{ $user->name }}</td>
                <td class="table-data">{{ $user->email }}</td>
                <td class="table-data">
                    <button type="button" class="table-btn__update"
                    onclick="openOwnerUpdate({{ $user->id }})">
                        詳細
                    </button>
                    <button type="button" class="table-btn__delete"
                    onclick="openOwnerDelete({{ $user->id }})">
                        削除
                    </button>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
<!--店舗代表者更新フォーム-->
@foreach ($users as $user)
<div class="overlay" id="overlay-update{{ $user->id }}">
    <div class="update" id="update-form{{ $user->id }}">
        <form action="/admin/update" method="post" class="update-form">
            @csrf
            @method('PATCH')
            <div class="update-form__group">
                <p class="update-message">店舗代表者詳細</p>
            </div>
            <div class="update-form__group">
                <table class="update-form__table">
                    <tr class="update-form__row">
                        <th class="update-form__title">店舗代表者名</th>
                        <td class="update-form__data" id="update-name-{{ $user->id }}">
                            <input type="text" name="name" id="update-name-{{ $user->id }}" class="update-form__input" value="{{ old('name', $user->name) }}">
                            @error('name')
                            <p class="alert" id="error-name-{{ $user->id }}">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="update-form__row">
                        <th class="update-form__title">メールアドレス</th>
                        <td class="update-form__data" id="update-email-{{ $user->id }}">
                            <input type="text" name="email" id="update-email-{{ $user->id }}" class="update-form__input" value="{{ old('email', $user->email) }}">
                            @error('email')
                            <p class="alert" id="error-email-{{ $user->id }}">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="update-form__row">
                        <th class="update-form__title">所有店舗数</th>
                        <td class="update-form__data">
                            <p class="update-form__p">{{ $user->shops->count() }}店舗</p>
                        </td>
                    </tr>
                    <tr class="update-form__row">
                        <th class="update-form__title">所有店舗名</th>
                        <td class="update-form__data">
                            @foreach ($user->shops as $shop)
                            <p class="update-form__p">{{ $shop->name }}</p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
            <div class="update-form__group">
                <input type="button" value="キャンセル" class="cancel-btn"
                onclick="closeOwnerUpdate({{ $user->id }})">
                <input type="submit" value="変更内容を保存" class="update-btn" id="update-btn">
                <input type="hidden" name="id" value="{{ $user->id }}">
            </div>
        </form>
    </div>
</div>
<!--店舗代表者削除フォーム-->
<div class="overlay" id="overlay-delete{{ $user->id }}">
    <div class="delete" id="delete-form{{ $user->id }}">
        <form action="/admin/delete" method="post" class="delete-form">
            @csrf
            @method('DELETE')
            <div class="delete-form__group">
                <p class="delete-message">本当にこの店舗代表者を削除しますか？</p>
            </div>
            <div class="delete-form__group">
                <table class="delete-form__table">
                    <tr class="delete-form__row">
                        <th class="delete-form__title">店舗代表者名</th>
                        <td class="delete-form__data">
                            <p class="delete-form__p">{{ $user->name }}</p>
                        </td>
                    </tr>
                    <tr class="delete-form__row">
                        <th class="delete-form__title">メールアドレス</th>
                        <td class="delete-form__data">
                            <p class="delete-form__p">{{ $user->email }}</p>
                        </td>
                    </tr>
                    <tr class="delete-form__row">
                        <th class="delete-form__title">所有店舗数</th>
                        <td class="delete-form__data">{{ $user->shops->count() }}店舗</td>
                    </tr>
                    <tr class="delete-form__row">
                        <th class="delete-form__title">所有店舗名</th>
                        <td class="delete-form__data">
                            @foreach ($user->shops as $shop)
                            <p class="delete-form__p">{{ $shop->name }}</p>
                            @endforeach
                        </td>
                    </tr>
                </table>
            </div>
            <div class="delete-form__group">
                <input type="button" value="キャンセル" class="cancel-btn"
                onclick="closeOwnerDelete({{ $user->id }})">
                <input type="submit" value="削除" class="delete-btn">
                <input type="hidden" name="id" value="{{ $user->id }}">
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('js/admin/owner_list.js') }}"></script>
<script src="{{ asset('js/admin/search_owner.js') }}"></script>
@if (session('owner_error_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openOwnerUpdate({{ session('owner_error_id') }});
        });
    </script>
@endif
@endsection