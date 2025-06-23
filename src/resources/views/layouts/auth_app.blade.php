<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth/auth_app.css') }}">
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
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>