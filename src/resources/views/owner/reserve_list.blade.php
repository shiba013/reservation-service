@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/reserve_list.css') }}">
@endsection

@section('header')
<form action="/owner/reserve/{{ $shop->id }}" method="get" class="date__search-form">
    <label for="date" class="calendar__label">
        <img src="{{ asset('icon/calendar.svg') }}" alt="カレンダー" class="calendar__img">
        日付検索
    </label>
    <input type="date" name="date" id="date" class="calendar__input" value="{{ request('date') }}">
    <a href="/owner/reserve/{{ $shop->id }}?date={{ Carbon\Carbon::today()->format('Y-m-d') }}" class="today-link">
            <img src="{{ asset('icon/calendar.svg') }}" alt="カレンダー" class="calendar__img">
            本日の予約
    </a>
</form>
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

<div class="reserve-list">
    <h2 class="reserve-list__title">予約一覧</h2>
    <div class="reserve-list__group">
        <div class="back">
            <a href="javascript:history.back()" class="back__link">
                <p class="back__btn">＜</p>
            </a>
            <h2 class="shop-name">{{ $shop->name }}</h2>
        </div>
        <div class="setting-btn">
            <button class="set-btn" onclick="openSettingForm()">受付時間設定</button>
            <button class="stop-btn" onclick="openStopForm({{ $shop->id }})">予約停止</button>
        </div>
    </div>
    <div class="reserve-list__group">
        <form action="/owner/export/reserve/list" method="post" class="export-form">
            @csrf
            <input type="hidden" name="reserve-data" value="">
            <input type="submit" value="CSV出力" class="export-btn">
        </form>
        <div class="paginate">
            {{ $reservations->links('pagination::bootstrap-4')}}
        </div>
    </div>
    <div class="reserve-list__group">
        <div class="select-date">
            <a href="/owner/reserve/{{ $shop->id }}?date={{ $previousDay }}" class="previous-day">
                <img src="{{ asset('icon/arrow.svg') }}" alt="左矢印" class="left-arrow__img">前日
            </a>
            <h2 class="today">{{ $todayFormat }}</h2>
            <a href="/owner/reserve/{{ $shop->id }}?date={{ $nextDay }}" class="next-day">翌日
                <img src="{{ asset('icon/arrow.svg') }}" alt="右矢印" class="right-arrow__img">
            </a>
        </div>
    </div>
    <div class="reserve-list__group">
        <table class="reserve-table">
            <tr class="reserve-table__row">
                <th class="table-title">時間</th>
                <th class="table-title">予約名</th>
                <th class="table-title">人数</th>
                <th class="table-title">修正</th>
            </tr>
            @foreach ($reservations as $reservation)
            <tr class="reserve-table__row">
                <td class="table-data">{{ $reservation->time->format('H:i') }}</td>
                <td class="table-data">{{ $reservation->user->name }}</td>
                <td class="table-data">{{ $reservation->number }}人</td>
                <td class="table-data">
                    <button type="button" class="reserve-update" onclick="openOwnerUpdateForm({{ $reservation->id }})">変更</button>
                    <button type="button" class="reserve-delete" onclick="openOwnerDeleteForm({{ $reservation->id }})">削除</button>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

<!--予約受付時間設定フォーム-->
<div class="overlay" id="overlay-setting">
    <div class="setting" id="setting-form">
        <form action="/owner/reserve/setting/{{ $shop->id }}" method="post" class="setting-form">
            @csrf
            <div class="setting-form__group"></div>
            <div class="setting-form__group">
                <input type="button" value="キャンセル" class="form-btn__cancel" onclick="closeSettingForm()">
                <input type="submit" value="設定する" class="form-btn__action">
            </div>
        </form>
    </div>
</div>

<!--予約停止フォーム-->
<div class="overlay" id="overlay-stop{{ $shop->id }}">
    <div class="stop"id="stop-form{{ $shop->id }}">
        <form action="/owner/reserve/stop/{{ $shop->id }}" method="post" class="stop-form">
            @csrf
            <input type="hidden" name="date" value="{{ request('date') }}">
            <div class="stop-form__group">
                <p class="stop-message">{{ $todayFormat }}の予約を停止しますか？</p>
            </div>
            <div class="stop-form__group">
                <input type="button" value="キャンセル" class="form-btn__cancel"
                onclick="closeStopForm({{ $shop->id }})">
                <input type="submit" value="予約停止する" class="form-btn__action">
            </div>
        </form>
    </div>
</div>

<!--予約更新フォーム-->
@foreach ($reservations as $reservation)
<div class="overlay" id="overlay-update{{ $reservation->id }}">
    <div class="update" id="update-form{{ $reservation->id }}">
        <form action="/owner/reserve/update/{{ $reservation->id }}" method="post" class="update-form">
            @csrf
            @method('PATCH')
            <div class="update-form__group">
                <p class="update-message">予約内容変更</p>
            </div>
            <div class="update-form__group">
                <p class="update-message">{{ $reservation->user->name }}様</p>
                <p class="update-message">[{{ $reservation->user->email }}]</p>
            </div>
            <div class="update-form__group">
                <table class="update-table">
                    <tr class="update-table__row">
                        <th class="table__title">Shop</th>
                        <td class="table__data">{{ $reservation->shop->name }}</td>
                    </tr>
                    <tr class="update-table__row">
                        <th class="table__title">Data</th>
                        <td class="table__data" id="update-date{{ $reservation->id }}">
                            <input type="date" name="date" class="update-form__input"
                            id="update-date{{ $reservation->id }}"
                            value="{{ old('date', $reservation->date->format('Y-m-d')) }}">
                            @error('date')
                            <p class="alert" id="error-date-{{ $reservation->id }}">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="update-table__row">
                        <th class="table__title">Time</th>
                        <td class="table__data" id="update-time{{ $reservation->id }}">
                            <input type="time" name="time" class="update-form__input"
                            id ="update-time{{ $reservation->id }}"
                            value="{{ old('time', $reservation->time->format('H:i')) }}">
                            @error('time')
                            <p class="alert" id ="error-time-{{ $reservation->id }}">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="update-table__row">
                        <th class="table__title">Number</th>
                        <td class="table__data" id="update-number{{ $reservation->id }}">
                            <input type="number" name="number" class="update-form__input"
                            id="update-number{{ $reservation->id }}"
                            value="{{ old('number', $reservation->number) }}">
                            <span class="table__data__span">人</span>
                            @error('number')
                            <p class="alert" id="error-number-{{ $reservation->id }}">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                </table>
            </div>
            <div class="update-form__group">
                <input type="button" value="キャンセル" class="form-btn__cancel"
                onclick="closeOwnerUpdateForm({{ $reservation->id }})">
                <input type="submit" value="変更内容を保存" class="form-btn__action">
            </div>
        </form>
    </div>
</div>

<!--予約削除フォーム-->
<div class="overlay" id="overlay-delete{{ $reservation->id }}">
    <div class="delete" id="delete-form{{ $reservation->id }}">
        <form action="/owner/reserve/delete/{{ $reservation->id }}" method="post" class="delete-form">
            @csrf
            @method('DELETE')
            <div class="delete-form__group">
                <p class="delete-message">この予約を削除しますか？</p>
            </div>
            <div class="delete-form__group">
                <p class="delete-message">{{ $reservation->user->name }}様</p>
                <p class="delete-message">[{{ $reservation->user->email }}]</p>
            </div>
            <div class="delete-form__group">
                <table class="delete-table">
                    <tr class="delete-table__row">
                        <th class="table__title">Shop</th>
                        <td class="table__data">{{ $reservation->shop->name }}</td>
                    </tr>
                    <tr class="delete-table__row">
                        <th class="table__title">Data</th>
                        <td class="table__data">{{ $reservation->date->format('Y年m月d日') }}</td>
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
                onclick="closeOwnerDeleteForm({{ $reservation->id }})">
                <input type="submit" value="予約を削除" class="form-btn__action">
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('js/owner/search_date.js') }}"></script>
<script src="{{ asset('js//owner/reservation.js') }}"></script>
@if (session('reservation_error_id'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openOwnerUpdateForm({{ session('reservation_error_id') }});
        });
    </script>
@endif
@endsection