@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mails/send_mail.css') }}">
@endsection

@section('content')
<div class="mail">
    <div class="back">
        <a href="javascript:history.back()" class="back__link">
            <p class="back__btn">＜</p>
        </a>
        <h2 class="title">配信メール作成</h2>
    </div>
    <form action="/owner/mail" method="post" class="mail-form">
        @csrf
        <div class="mail-form__group">
            <label for="send-to" class="mail-form__label">宛先
                <span class="mail-form__span">必須</span>
            </label>
            <select name="send-to" id="send-to" class="mail-form__select">
                @foreach ($sendTargets as $target)
                <option value="{{ $target }}">{{ $target }}</option>
                @endforeach
            </select>
            @error('send-to')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="mail-form__group">
            <label for="subject" class="mail-form__label">件名
                <span class="mail-form__span">必須</span>
            </label>
            <input type="text" name="subject" id="subject" value="{{ old('subject') }}" class="mail-form__input">
            @error('subject')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="mail-form__group">
            <label for="body" class="mail-form__label">本文
                <span class="mail-form__span">必須</span>
            </label>
            <textarea name="body" id="body" class="mail-form__textarea">{{ old('body') }}</textarea>
        </div>
        <div class="mail-form__group">
            <input type="submit" value="送信する" class="submit-btn">
        </div>
    </form>
</div>
@endsection