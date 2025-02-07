<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>
<body>
    <div class="app">
        <header class="header">
            <a class="header-link" href="/">
                <img src="{{ asset('image/logo.svg') }}" alt="COACHTECH" class="header-img">
            </a>
            @if (Auth::check())
            <div class="header__search-form">
                <form action="/" method="get">
                    @csrf
                    <input class="search-input" type="text" name="keyword" value="{{ old('keyword') }}" placeholder="なにをお探しですか？">
                    <input type="hidden" name="page" value="{{ request('page', 'all') }}">
                    <button class="search-button" type="submit">
                        <img src="{{ asset('image/search.png') }}" alt="検索">
                    </button>
                </form>
            </div>
            <ul class="header-nav">
                <li class="header-nav__item">
                    <form action="/logout" method="post" class="logout-form">
                        @csrf
                        <button class="logout-button">ログアウト</button>
                    </form>
                </li>
            </ul>
            @endif
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>
</html>