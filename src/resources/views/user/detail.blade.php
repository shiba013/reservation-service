@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/detail.css') }}">
@endsection

@section('content')
<div class="detail">
    <section class="shop-info">
        <div class="shop-info__group">
            <a href="javascript:history.back()" class="back__link">
                <div class="back__btn">＜</div>
            </a>
            <h2 class="shop__name">{{ $shop->name }}</h2>
        </div>
        <div class="shop-info__group">
            <img src="{{ asset($shop->image) }}" alt="店舗画像" class="shop__img">
        </div>
        <div class="shop-info__group">
            <p class="shop-info__p">#{{ $shop->area->area }}</p>
            <p class="shop-info__p">#{{ $shop->genre->genre }}</p>
        </div>
        <div class="shop-info__group">
            <p class="shop-info__p">{{ $shop->overview }}</p>
        </div>
    </section>
    <section class="reserve">
        <form action="/detail/{{ $shop->id }}" method="post" class="reserve-form">
            @csrf
            <div class="reserve-form__group">
                <h2 class="reserve-form__title">予約</h2>
            </div>
            <div class="reserve-form__group">
                <input type="date" name="date" class="reserve-form__input" value="{{ old('date') }}" id="selectDate">
                @error('date')
                <p class="alert">{{ $message }}</p>
                @enderror
            </div>
            <div class="reserve-form__group">
                <select name="time" id="selectTime" class="reserve-form__select">
                    <option value="" hidden>時間を選択してください</option>
                    @foreach ($unique as $slot)
                    <option value="{{ $slot->reserve_start->format('H:i') }}"
                    data-remaining="{{ $slot->remaining_number }}">
                        {{ $slot->reserve_start->format('H:i') }}
                    </option>
                    @endforeach
                </select>
                @error('time')
                <p class="alert">{{ $message }}</p>
                @enderror
            </div>
            <div class="reserve-form__group">
                <select name="number" id="selectNumber" class="reserve-form__select">
                    <option value="" hidden>人数を選択してください</option>
                </select>
                @error('number')
                <p class="alert">{{ $message }}</p>
                @enderror
            </div>
            <div class="reserve-form__group">
                <table class="reserve-table">
                    <tr class="reserve-table__row">
                        <th class="table__title">Shop</th>
                        <td class="table__data">{{ $shop->name }}</td>
                    </tr>
                    <tr class="reserve-table__row">
                        <th class="table__title">Date</th>
                        <td class="table__data" id="selectedDate"></td>
                    </tr>
                    <tr class="reserve-table__row">
                        <th class="table__title">Time</th>
                        <td class="table__data" id="selectedTime"></td>
                    </tr>
                    <tr class="reserve-table__row">
                        <th class="table__title">Number</th>
                        <td class="table__data" id="selectedNumber"></td>
                    </tr>
                </table>
            </div>
            <input type="submit" value="予約する" class="submit__btn">
        </form>
    </section>
</div>
@endsection
@section('scripts')
<script src="{{ asset('js/user/reflection.js') }}"></script>
@endsection