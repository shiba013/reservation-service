@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/create_shop.css') }}">
@endsection

@section('content')
<div class="create">
    <div class="back">
        <a href="javascript:history.back()" class="back__link">
            <p class="back__btn">＜</p>
        </a>
        <h2 class="title">新規店舗作成</h2>
    </div>
    <form action="/owner/create" method="post" class="create-form" enctype="multipart/form-data">
        @csrf
        <div class="create-form__group">
            <label for="name" class="create-form__label">店名
                <span class="create-form__span">必須</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" class="create-form__input">
            @error('name')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="image" class="create-form__label">
                <span class="create-form__label-text">店舗画像</span>
                <span class="create-form__span">必須</span>
            </label>
            <div class="file-wrapper">
                <label for="image" class="file__label">画像を選択</label>
                <span class="file__name" id="fileName"></span>
            </div>
            <input type="file" name="image" id="image" class="create-form__file">
            <img src="" alt="画像プレビュー" class="create-form__preview" id="imagePreview">
            @error('image')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="area" class="create-form__label">エリア
                <span class="create-form__span">必須</span>
            </label>
            <input type="text" name="area" id="area" value="{{ old('area') }}" class="create-form__input">
            @error('area')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="genre" class="create-form__label">ジャンル
                <span class="create-form__span">必須</span>
            </label>
            <input type="text" name="genre" id="genre" value="{{ old('genre') }}" class="create-form__input">
            @error('genre')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="overview" class="create-form__label">概要
                <span class="create-form__span">必須</span>
            </label>
            <textarea name="overview" id="overview" class="create-form__textarea">{{ old('overview') }}</textarea>
            @error('overview')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="start_time" class="create-form__label">営業開始時間</label>
            <input type="time" name="start_time" id="start_time" value="{{ old('start_time') }}" class="create-form__input">
            @error('start_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <label for="end_time" class="create-form__label">営業終了時間</label>
            <input type="time" name="end_time" id="end_time" value="{{ old('end_time') }}" class="create-form__input">
            @error('end_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="create-form__group">
            <input type="submit" value="登録する" class="create-form__btn">
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/owner/image_preview.js') }}"></script>
@endsection