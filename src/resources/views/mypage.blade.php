@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
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
<div class="profile">
    <div class="image-circle">
        <img class="user-image" src="{{ asset('storage/' . $user->image) }}" alt="画像">
    </div>
    <div class="user-name">
        {{ $user->name }}
    </div>
    <div class="profile-edit">
        <a href="/mypage/profile" class="profile-edit__button">
            プロフィールを編集
        </a>
    </div>
</div>
<div class="page-button__group">
    <div class="sell-item">
        <a href="/mypage?page=sell" class="page-button {{ $page === 'sell' ? 'active' : 'passive' }}">出品した商品</a>
    </div>
    <div class="buy-item">
        <a href="/mypage?page=buy" class="page-button {{ $page === 'buy' ? 'active' : 'passive' }}">購入した商品</a>
    </div>
</div>
<div class="items-wrap">
    @if ($page === 'sell')
    @foreach ($sellItems as $item)
    <div class="item-contents">
        <a href="/item/{{ $item->id }}">
            <div class="item-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
            </div>
            <div class="content-item">
                <p class="item-name">{{ $item->name }}</p>
                @if ($item->order)
                <p class="sold">Sold</p>
                @endif
            </div>
        </a>
    </div>
    @endforeach
    @else
    @foreach ($buyItems as $item)
    <div class="item-contents">
        <a href="/item/{{ $item->id }}">
            <div class="item-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
            </div>
            <div class="content-item">
                <p class="item-name">{{ $item->name }}</p>
                @if ($item->order)
                <p class="sold">Sold</p>
                @endif
            </div>
        </a>
    </div>
    @endforeach
    @endif
</div>
@endsection