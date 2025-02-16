@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
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
<div class="sell-title">
    商品の出品
</div>
<form action="/sell" method="post" class="sell-form">
    <div class="form-group">
        <label class="form-group__title">商品画像</label>
        <output id="list" class="image-output"></output>
        <input type="file" name="image" id="item-image" class="image-input">
    </div>
    <div class="item-detail__title">
        商品の詳細
    </div>
    <div class="form-group">
        <label class="form-group__title">カテゴリー</label>
        <div class="categories-wrap">

        </div>
    </div>
    <div class="form-group">
        <label class="form-group__title">商品の状態</label>
        <select name="condition" id="">
            <option value="">選択してください</option>
            @foreach($conditions as $condition)
            <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
            @endforeach
        </select>
    </div>
    <div class="item-description__title">
        商品名と説明
    </div>
    <div class="form-group">
        <label class="form-group__title">商品名</label>
        <input type="text" name="name" class="input">
    </div>
    <div class="form-group">
        <label class="form-group__title">ブランド名</label>
        <input type="text" name="brand" class="input">
    </div>
    <div class="form-group">
        <label class="form-group__title">商品の説明</label>
        <textarea name="description" class="textarea"></textarea>
    </div>
    <div class="form-group">
        <label class="form-group__title">販売価格</label>
        <input type="text" value="&yen;" class="input">
    </div>
    <div class="form-button">
        <button class="form-button__submit">出品する</button>
    </div>
</form>