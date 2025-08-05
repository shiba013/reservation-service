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
    </div>
</div>
@endsection
