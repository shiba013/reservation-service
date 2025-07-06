@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css') }}">
@endsection

@section('content')
@if(session('success'))
<div class="success-session">
    <p class="session__message">
        {{ session('success') }}
    </p>
</div>
@elseif(session('fail'))
<div class="fail-session">
    <p class="session__message">
        {{ session('fail') }}
    </p>
</div>
@endif
<div class="mypage">
    <h1 class="mypage__title">{{ Auth::user()->name }}さん</h1>
    <section class="my-reserve">
        <h2 class="title__logo">予約状況</h2>
        @foreach($reservations as $index => $reservation)
        <div class="my-reserve__group">
            <div class="reserve__title">
                <img src="{{ asset('icon/clock.svg') }}" alt="時計画像" class="clock__icon"
                onclick="openEditForm({{ $reservation->id }})">
                <h3 class="sub-title">予約{{ $index + 1 }}</h3>
                <img src="{{ asset('icon/close.png') }}" alt="バツ" class="close__icon"
                onclick="openDeleteForm({{ $reservation->id }})">
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
        <!--予約変更フォーム-->
        <div class="overlay" id="overlay-edit{{ $reservation->id }}">
            <div class="edit" id="edit-form{{ $reservation->id }}">
                <div class="edit-form">
                    <div class="edit-form__group">
                        <p class="edit-message">変更内容を選択してください</p>
                    </div>
                    <div class="edit-form__group">
                        <table class="edit-table">
                            <tr class="edit-table__row">
                                <th class="table__title">Shop</th>
                                <td class="table__data">{{ $reservation->shop->name }}</td>
                            </tr>
                            <tr class="edit-table__row">
                                <th class="table__title">Date</th>
                                <td class="table__data">
                                    <input type="date" name="date" class="edit-form__input"
                                    value="{{ old('date', $reservation->date->format('Y-m-d')) }}">
                                </td>
                            </tr>
                            <tr class="edit-table__row">
                                <th class="table__title">Time</th>
                                <td class="table__data">
                                    <select name="time" class="edit-form__select">
                                        <option value="{{ $reservation->time }}">
                                            {{ $reservation->time->format('H:i') }}
                                        </option>
                                        <option value="17:00">17:00</option>
                                        <option value="18:00">18:00</option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="edit-table__row">
                                <th class="table__title">Number</th>
                                <td class="table__data">
                                    <select name="number" class="edit-form__select">
                                        <option value="{{ $reservation->number }}">
                                            {{ $reservation->number }}人
                                        </option>
                                        <option value="1">1人</option>
                                        <option value="2">2人</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="edit-form__group">
                        <input type="button" value="キャンセル" class="form-btn__cancel"
                        onclick="closeEditForm({{ $reservation->id }})">
                        <input type="button" value="変更" class="form-btn__edit"
                        onclick="openUpdateForm({{ $reservation->id }})">
                    </div>
                </div>
            </div>
        </div>
        <!--予約更新フォーム-->
        <div class="overlay" id="overlay-update{{ $reservation->id }}">
            <div class="update" id="update-form{{ $reservation->id }}">
                <form action="/reserve/update/{{ $reservation->id }}" method="post" class="update-form">
                    @csrf
                    @method('PATCH')
                    <div class="update-form__group">
                        <p class="update-message">この予約内容に変更してよろしいですか？</p>
                    </div>
                    <div class="update-form__group">
                        <table class="update-table">
                            <tr class="update-table__row">
                                <th class="table__title">Shop</th>
                                <td class="table__data">{{ $reservation->shop->name }}</td>
                            </tr>
                            <tr class="update-table__row">
                                <th class="table__title">Data</th>
                                <td class="table__data" id="confirm-date{{ $reservation->id }}"></td>
                            </tr>
                            <tr class="update-table__row">
                                <th class="table__title">Time</th>
                                <td class="table__data" id="confirm-time{{ $reservation->id }}"></td>
                            </tr>
                            <tr class="update-table__row">
                                <th class="table__title">Number</th>
                                <td class="table__data" id="confirm-number{{ $reservation->id }}">
                                    <span class="table__data__span">人</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <input type="hidden" name="date" id="update-date{{ $reservation->id }}">
                    <input type="hidden" name="time" id ="update-time{{ $reservation->id }}">
                    <input type="hidden" name="number" id="update-number{{ $reservation->id }}">
                    <div class="update-form__group">
                        <input type="button" value="キャンセル" class="form-btn__cancel"
                        onclick="closeUpdateForm({{ $reservation->id }})">
                        <input type="submit" value="確定" class="form-btn__update">
                    </div>
                </form>
            </div>
        </div>
        <!--予約削除フォーム-->
        <div class="overlay" id="overlay-delete{{ $reservation->id }}">
            <div class="delete" id="delete-form{{ $reservation->id }}">
                <form action="/reserve/delete/{{ $reservation->id }}" method="post" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="delete-form__group">
                        <p class="delete-message">この予約を本当に削除しますか？</p>
                    </div>
                    <div class="delete-form__group">
                        <table class="delete-table">
                            <tr class="delete-table__row">
                                <th class="table__title">Shop</th>
                                <td class="table__data">{{ $reservation->shop->name }}</td>
                            </tr>
                            <tr class="delete-table__row">
                                <th class="table__title">Data</th>
                                <td class="table__data">{{ $reservation->date->format('Y-m-d') }}</td>
                            </tr>
                            <tr class="delete-table__row">
                                <th class="table__title">Time</th>
                                <td class="table__data">{{ $reservation->time->format('H:i') }}</td>
                            </tr>
                            <tr class="delete-table__row">
                                <th class="table__title">Number</th>
                                <td class="table__data">
                                    {{ $reservation->number }}
                                    <span class="table__data__span">人</span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="delete-form__group">
                        <input type="button" value="キャンセル" class="form-btn__cancel"
                        onclick="closeDeleteForm({{ $reservation->id }})">
                        <input type="submit" value="削除" class="form-btn__delete">
                    </div>
                </form>
            </div>
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
<script src="{{ asset('js/reservation.js') }}"></script>
<script src="{{ asset('js/favorite.js') }}"></script>
@endsection