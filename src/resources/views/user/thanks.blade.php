@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/thanks.css') }}">
@endsection

@section('content')
<div class="thanks">
    <div class="thanks__inner">
        <div class="thanks__title">
            <h2 class="title__logo">会員登録ありがとうございます</h2>
        </div>
        <div class="link">
            <a href="/login" class="thanks__link">ログインする</a>
        </div>
    </div>
</div>
@endsection