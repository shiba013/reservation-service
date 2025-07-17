@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/detail.css') }}">
@endsection

@section('content')
<div class="owner-detail">
    <div class="back">
        <a href="javascript:history.back()" class="back__link">
            <p class="back__btn">＜</p>
        </a>
        <h2 class="title">店舗詳細情報</h2>
    </div>
    <form action="/owner/edit/{{ $shop->id }}" method="post" class="edit-form" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <div class="edit-form__group">
            <label for="name" class="edit-form__label">店名
                <span class="edit-form__span">必須</span>
            </label>
            <input type="text" name="name" id="name" class="edit-form__input"
            value="{{ old('name', $shop->name) }}">
            @error('name')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="image" class="edit-form__label">
                <span class="edit-form__label-text">店舗画像</span>
                <span class="edit-form__span">必須</span>
            </label>
            <div class="file-wrapper">
                <label for="image" class="file__label">画像を選択</label>
                <span class="file__name" id="fileName">{{ basename($shop->image) }}</span>
            </div>
            <input type="file" name="image" id="image" class="edit-form__file">
            <img src="{{ asset($shop->image) }}" alt="画像プレビュー" class="edit-form__preview" id="imagePreview">
            @error('image')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="area" class="edit-form__label">エリア
                <span class="edit-form__span">必須</span>
            </label>
            <input type="text" name="area" id="area" class="edit-form__input"
            value="{{ old('area', $shop->area->area) }}">
            @error('area')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="genre" class="edit-form__label">ジャンル
                <span class="edit-form__span">必須</span>
            </label>
            <input type="text" name="genre" id="genre" class="edit-form__input"
            value="{{ old('genre', $shop->genre->genre) }}">
            @error('genre')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="overview" class="edit-form__label">概要
                <span class="edit-form__span">必須</span>
            </label>
            <textarea name="overview" id="overview" class="edit-form__textarea">{{ old('overview', $shop->overview) }}</textarea>
            @error('overview')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="start_time" class="edit-form__label">営業開始時間
                <span class="edit-form__span">必須</span>
            </label>
            <input type="time" name="start_time" id="start_time" class="edit-form__input"
            value="{{ old('start_time', $shop->start_time->format('H:i')) }}">
            @error('start_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <label for="end_time" class="edit-form__label">営業終了時間
                <span class="edit-form__span">必須</span>
            </label>
            <input type="time" name="end_time" id="end_time" class="edit-form__input"
            value="{{ old('end_time', $shop->end_time->format('H:i')) }}">
            @error('end_time')
            <p class="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="edit-form__group">
            <input type="submit" value="変更を保存" class="edit-form__btn">
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/owner/image_preview.js') }}"></script>
@endsection
