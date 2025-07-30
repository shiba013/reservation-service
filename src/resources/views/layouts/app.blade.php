<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}">
    @yield('css')
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <div class="menu" id="toggle">
                <p class="menu__bar">＿</p>
                <p class="menu__bar">＿＿</p>
                <p class="menu__bar">_</p>
                <div class="menu__list" id="link">
                    @if (Auth::check())
                        @if (session('login_type') === 'user')
                        <a href="/" class="menu__link">ホーム</a>
                        <a href="/mypage" class="menu__link">マイページ</a>
                        @elseif (session('login_type') === 'owner')
                        <a href="/owner" class="menu__link">ホーム</a>
                        <a href="/owner/create" class="menu__link">新規店舗登録</a>
                        <a href="/owner/mail" class="menu__link">メール配信</a>
                        @elseif (session('login_type') === 'admin')
                        <a href="/admin" class="menu__link">ホーム</a>
                        <a href="/admin/register" class="menu__link">新規店舗代表者登録</a>
                        <a href="/admin/shop" class="menu__link">店舗一覧</a>
                        <a href="/admin/mail" class="menu__link">メール配信</a>
                        @endif
                        <form action="/logout" method="post" class="logout">
                            @csrf
                            <input type="submit" value="ログアウト" class="logout__btn">
                        </form>
                    @elseif (session('login_type') === 'user')
                    <a href="/" class="menu__link">ホーム</a>
                    <a href="/register" class="menu__link">新規会員登録</a>
                    <a href="/login" class="menu__link">ログイン</a>
                    @elseif (session('login_type') === 'owner')
                    <a href="/" class="menu__link">ホーム</a>
                    <a href="/owner/create" class="menu__link">新規店舗登録</a>
                    <a href="/owner/mail" class="menu__link">メール配信</a>
                    <a href="/owner/login" class="menu__link">ログイン</a>
                    @elseif (session('login_type') === 'admin')
                    <a href="/" class="menu__link">ホーム</a>
                    <a href="/admin/register" class="menu__link">新規店舗代表者登録</a>
                    <a href="/admin/shop" class="menu__link">店舗一覧</a>
                    <a href="/admin/mail" class="menu__link">メール配信</a>
                    <a href="/admin/login" class="menu__link">ログイン</a>
                    @else
                    <a href="/" class="menu__link">ホーム</a>
                    <a href="/register" class="menu__link">新規会員登録</a>
                    <a href="/login" class="menu__link">ログイン</a>
                    @endif
                </div>
            </div>
            <h1 class="header__title">Rese</h1>
            @yield('header')
        </div>
    </header>
    <main>
        @yield('content')
    </main>
    <script>
        const toggle = document.getElementById('toggle');
        const link = document.getElementById('link');

        toggle.addEventListener('click', () => {
            link.style.display = link.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.menu')) {
                link.style.display = 'none';
            }
        });
    </script>
    @yield('scripts')
</body>
</html>