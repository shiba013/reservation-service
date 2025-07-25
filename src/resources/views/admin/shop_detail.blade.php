@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/shop_detail.css') }}">
@endsection

@section('content')
<div class="detail">
    <div class="back">
        <a href="javascript:history.back()" class="back__link">
            <p class="back__btn">＜</p>
        </a>
        <h2 class="title">店舗詳細情報</h2>
    </div>
    <form action="/admin/shop/update/{{ $shop->id }}" method="post" class="update-form" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $shop->id }}">
        <div class="update-form__group">
            <label for="name" class="update-form__label">店名
                <span class="update-form__span">必須</span>
            </label>
            <input type="text" name="name" id="name" class="update-form__input"
            value="{{ old('name', $shop->name) }}">
            @error('name')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="image" class="update-form__label">
                <span class="update-form__label-text">店舗画像</span>
                <span class="update-form__span">必須</span>
            </label>
            <div class="file-wrapper">
                <label for="image" class="file__label">画像を選択</label>
                <span class="file__name" id="fileName">{{ basename($shop->image) }}</span>
            </div>
            <input type="file" name="image" id="image" class="update-form__file">
            <img src="{{ asset($shop->image) }}" alt="画像プレビュー" class="update-form__preview" id="imagePreview">
            @error('image')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="area" class="update-form__label">エリア
                <span class="update-form__span">必須</span>
            </label>
            <input type="text" name="area" id="area" class="update-form__input"
            value="{{ old('area', $shop->area->area) }}">
            @error('area')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="genre" class="update-form__label">ジャンル
                <span class="update-form__span">必須</span>
            </label>
            <input type="text" name="genre" id="genre" class="update-form__input"
            value="{{ old('genre', $shop->genre->genre) }}">
            @error('genre')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="overview" class="update-form__label">概要
                <span class="update-form__span">必須</span>
            </label>
            <textarea name="overview" id="overview" class="update-form__textarea">{{ old('overview', $shop->overview) }}</textarea>
            @error('overview')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="start_time" class="update-form__label">営業開始時間
                <span class="update-form__span">必須</span>
            </label>
            <input type="time" name="start_time" id="start_time" class="update-form__input"
            value="{{ old('start_time', $shop->start_time->format('H:i')) }}">
            @error('start_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <label for="end_time" class="update-form__label">営業終了時間
                <span class="update-form__span">必須</span>
            </label>
            <input type="time" name="end_time" id="end_time" class="update-form__input"
            value="{{ old('end_time', $shop->end_time->format('H:i')) }}">
            @error('end_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="update-form__group">
            <input type="submit" value="変更を保存" class="update-form__btn">
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/owner/image_preview.js') }}"></script>
@endsection
