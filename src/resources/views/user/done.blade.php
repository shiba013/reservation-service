@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/done.css') }}">
@endsection

@section('content')
<div class="done">
    <div class="done__inner">
        <div class="done__title">
            <h2 class="title__logo">ご予約ありがとうございます</h2>
        </div>
        <div class="link">
            <a href="/" class="done__link">戻る</a>
        </div>
    </div>
</div>
@endsection