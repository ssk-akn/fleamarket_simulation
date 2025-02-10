@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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
    @else
    <li class="header-nav__item">
        <a href="/login" class="login-button">ログイン</a>
    </li>
    @endif
</ul>
@endsection


@section('content')
<div class="page-button__item">
    <div class="recommend-button">
        <a href="{{ url('/') }}" class="{{ request('page', 'all') === 'all' ? 'active' : 'passive' }}">おすすめ</a>
    </div>
    <div class="mypage-button">
        <a href="{{ url('/?page=mylist') }}" class="{{ request('page') === 'mylist' ? 'active' : 'passive' }}">マイリスト</a>
    </div>
</div>
<div class="items-wrap">
    @foreach ($items as $item)
    <div class="item-contents">
        <a href="/item/{{ $item->id }}">
            <img src="{{ asset($item->image) }}" alt="商品画像">
            <div class="content-item">
                <p class="item-name">{{ $item->name }}</p>
                @if ($item->order)
                <p class="sold">sold</p>
                @endif
            </div>
        </a>
    </div>
    @endforeach

    <div class="item-contents">
        <a href="/item">
            <img class="item-image" src="{{ asset('image/pika.jpg') }}" alt="商品画像">
            <div class="content-item">
                <p class="item-name">ピカチュウ</p>
                <p class="sold">sold</p>
            </div>
        </a>
    </div>
    <div class="item-contents">
        <a href="/item">
            <img class="item-image" src="{{ asset('image/pika.jpg') }}" alt="商品画像">
            <p class="item-name">ピカチュウ</p>
        </a>
    </div>
    <div class="item-contents">
        <a href="/item">
            <img class="item-image" src="{{ asset('image/pika.jpg') }}" alt="商品画像">
            <p class="item-name">ピカチュウ</p>
        </a>
    </div>
    <div class="item-contents">
        <a href="/item">
            <img class="item-image" src="{{ asset('image/pika.jpg') }}" alt="商品画像">
            <p class="item-name">ピカチュウ</p>
        </a>
    </div>

</div>
@endsection