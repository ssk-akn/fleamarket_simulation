@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address.css') }}">
@endsection

@section('link')
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
    @if (Auth::check())
    <li class="header-nav__item">
        <form action="/logout" method="post" class="logout-form">
            @csrf
            <button class="logout-button">ログアウト</button>
        </form>
    </li>
    <li class="header-nav__item">
        <a href="/mypage" class="mypage-button">マイページ</a>
    </li>
    <li class="header-nav__item">
        <a href="/sell" class="sell-button">出品</a>
    </li>
    @else
    <li class="header-nav__item">
        <a href="/login" class="login-button">ログイン</a>
    </li>
    @endif
</ul>
@endsection

@section('content')
<div class="change-page">
    <div class="change-page__header">
        <h1>住所の変更</h1>
    </div>
    <form action="/purchase/address/{{ $item->id }}" method="post">
        @csrf
        <div class="form-group">
            <label for="postcode" class="form-group__label">郵便番号</label>
            <input type="text" name="postcode" id="postcode" value="{{ old('postcode') }}" class="form-group__input">
        </div>
        <div class="form-group">
            <label for="address" class="form-group__label">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address') }}" class="form-group__input">
        </div>
        <div class="form-group">
            <label for="building" class="form-group__label">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building') }}" class="form-group__input">
        </div>
        <div class="update-button">
            <button class="update-button__submit" type="submit">更新する</button>
        </div>
    </form>
</div>
@endsection