<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/transaction_header.css') }}">
    @yield('link')
</head>
<body>
    <div class="app">
        <header class="header">
            <a class="header-link" href="/">
                <img src="{{ asset('image/logo.svg') }}" alt="COACHTECH" class="header-img">
            </a>
        </header>
        @yield('content')
        @yield('js')
    </div>
</body>
</html>