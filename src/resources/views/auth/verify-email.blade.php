<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/verify-email.css') }}">
</head>
<body>
    <main>
        <div class="content">
            <h2 class="verify-email">
                登録していただいたメールアドレスに認証メールを送付しました。<br>
                メール認証を完了してください。
            </h2>
            <a href="https://mailtrap.io/home" class="link">認証はこちらから</a>
            <div class="verify-email__group">
                <form action="{{ route('verification.send') }}" method="post" class= "resend-form">
                    @csrf
                    <input type="submit" value="認証メールを再送する" class="resend__button">
                </form>
            </div>
        </div>
    </main>
</body>
</html>