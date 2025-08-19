@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/qr.css') }}">
@endsection

@section('content')
<div class="qr">
    <div class="qr__inner">
        <div class="qr__title">
            <h2 class="qr__logo">来店を確認しました</h2>
        </div>
        <div class="qr__message">
            <p class="message__p">本日はご来店いただきありがとうございます</p>
        </div>
        <div class="subtitle">
            <h3 class="subtitle-logo">ご予約内容</h3>
        </div>
        <div class="qr__items">
            <p class="items__p">お名前：{{ $reservation->user->name }}</p>
            <p class="items__p">予約日: {{ $reservation->date->format('Y年n月j日(D)') }}</p>
            <p class="items__p">時間: {{ $reservation->time->format('H:i') }}</p>
            <p class="items__p">人数: {{ $reservation->number }}人</p>
        </div>
    </div>
</div>
@endsection
