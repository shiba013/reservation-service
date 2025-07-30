@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/review.css') }}">
@endsection

@section('content')
@if (session('success'))
<div class="success-session">
    <p class="session__message">
        {{ session('success') }}
    </p>
</div>
@endif
<div class="review">
    <div class="back">
        <a href="javascript:history.back()" class="back__link">
            <p class="back__btn">＜</p>
        </a>
        <h2 class="title">{{ $shop->name }}の口コミ一覧</h2>
    </div>
    <div class="review__title">
        <h2 class="btn-title">口コミ投稿をお願いします</h2>
        <div class="review__btn">
            <button type="button" class="create-review__btn" onclick="openCreateReview()">投稿する</button>
        </div>
    </div>
    <div class="review-menu">
        <form action="/review/sort/{{ $shop->id }}" method="get" class="sort-form" id="sortForm">
            <select name="sort" id="sortSelect" class="sort-reviews">
                @if (!request('sort'))
                <option value="" disabled selected hidden>並び替え</option>
                @else
                <option value="">リセット</option>
                @endif
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>最新順</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>古い順</option>
                <option value="high" {{ request('sort') == 'high' ? 'selected' : '' }}>評価が高い順</option>
                <option value="low" {{ request('sort') == 'low' ? 'selected' : '' }}>評価が低い順</option>
            </select>
        </form>
        <div class="paginate">
            {{ $reviews->links('pagination::bootstrap-4') }}
        </div>
    </div>
    <div class="review-list">
        @foreach ($reviews as $review)
        <div class="review-list__group">
            @if (Auth::id() === $review->user_id)
            <img src="{{ asset('icon/edit.svg') }}" alt="編集ボタン" class="edit-icon" onclick="openEditReview({{ $review->id }})">
            @endif
            <p class="review-list__p">{{ $review->user->name }}</p>
            <p class="review-list__p">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review->evaluation)
                        <span class="star filled">&#9733;</span>
                    @else
                        <span class="star">&#9733;</span>
                    @endif
                @endfor
            </p>
            <p class="review-list__p">{{ $review->comment }}</p>
        </div>
        @endforeach
    </div>
</div>

<!--投稿フォーム-->
<div class="overlay" id="overlay-create">
    <div class="create" id="create" data-is-auth="{{ Auth::check() ? '1' : '0' }}">
        <div class="create-review-form" data-shop-id="{{ $shop->id }}">
            <p class="star-title">5段階で評価してください</p>
            <div class="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                <span class="star" data-value="{{ $i }}">&#9733;</span>
                @endfor
            </div>
            <p class="evaluation-alert"></p>
            <textarea class="comment" name="comment" placeholder="コメントを入力してください">{{ old('comment') }}</textarea>
            <p class="comment-alert"></p>
            <div class="btn__group">
                <button class="cancel-btn" onclick="closeCreateReview()">キャンセル</button>
                <button class="create-btn">投稿する</button>
            </div>
        </div>
    </div>
</div>

<!--編集フォーム-->
@foreach ($reviews as $review)
<div class="overlay" id="overlay-edit{{ $review->id }}">
    <div class="edit" id="edit{{ $review->id }}" data-is-auth="{{ Auth::check() ? '1' : '0' }}">
        <div class="edit-modal">
            <p class="edit-modal__p">口コミを編集する</p>
            <img src="{{ asset('icon/close.png') }}" alt="閉じるボタン" class="close-icon"
            onclick="closeEditReview({{ $review->id }})">
        </div>
<!--更新-->
        <div class="update-review-form" data-shop-id="{{ $shop->id }}">
            <input type="hidden" name="id" value="{{ $review->id }}">
            <input type="hidden" name="evaluation" class="evaluation-input"
            value="{{ $review->evaluation }}">
            <p class="update-form__p">5段階で評価してください</p>
            <div class="star-rating">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= $review->evaluation)
                        <span class="update-star filled" data-value="{{ $i }}">&#9733;</span>
                    @else
                        <span class="update-star" data-value="{{ $i }}">&#9733;</span>
                    @endif
                @endfor
            </div>
            <p class="evaluation-alert"></p>
            <textarea class="comment" name="comment" placeholder="コメントを入力してください">{{ old('comment', $review->comment) }}</textarea>
            <p class="comment-alert"></p>
            <div class="form__btn">
                <button class="update-btn">変更を保存</button>
            </div>
        </div>
<!--削除-->
        <form action="/review/delete" method="post" class="delete-form">
            @csrf
            @method('DELETE')
            <input type="hidden" name="id" value="{{ $review->id }}">
            <div class="delete-form__group">
                <button class="delete-btn">口コミ削除</button>
            </div>
        </form>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script src="{{ asset('js/user/review.js') }}"></script>
@endsection