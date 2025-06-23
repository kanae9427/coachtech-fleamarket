<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header-logo">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.svg') }}" alt="サイトロゴ">
                </a>
            </div>

            <form method="get" action="{{ route('home') }}" class="search-form">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="なにをお探しですか？">
            </form>

            <div class="header-actions">
                @guest
                <a class="header__login-link" href="/login">ログイン</a>
                @endguest

                @auth
                <form class="form" action="/logout" method="post">
                    @csrf
                    <input class="header__logout-link" type="submit" value="ログアウト">
                </form>
                @endauth
                <a class="header__mypage-link" href="/mypage">マイページ</a>
                <a class="header__sell-button" href="/sell">出品</a>
            </div>
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>