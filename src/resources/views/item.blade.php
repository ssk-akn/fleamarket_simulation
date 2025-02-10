@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
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
<div class="item-detail">
    <div class="item-image">
        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
    </div>
    <div class="item-contents">
        <div class="item-name">
            <h1>{{ $item->name }}</h1>
        </div>
        <div class="item-brand">
            {{ $item->brand }}
        </div>
        <div class="item-price">
            <span class="price-span">&yen;</span>
            {{ $item->price }}
            <span class="price-span">（税込）</span>
        </div>
        <div class="item-reaction">
            <div class="item-reaction__likes">
                <form action="{{ $isLiked ? '/item/{item_id}/like' : '/item/{item_id}/unlike' }}" method="post">
                    @csrf
                    <button class="item-reaction__likes-button">
                        @if ($isLiked)
                        <img src="{{ asset('image/y_star.png') }}" alt="いいね解除">
                        @else
                        <img src="{{ asset('image/star.png') }}" alt="いいね">
                    </button>
                </form>
                <span>{{ $itemCount }}</span>
            </div>
            <div class="item-reaction__comments">
                <img src="{{ asset('image/comment.png')" alt="コメント">
                <span>{{ $comments->count() }}</span>
            </div>
        </div>
        <div class="item-purchase__button">
            <a class="item-purchase__button-submit" href="/purchase/{{ $item->id }}">
                購入手続きへ
            </a>
        </div>
        <div class="item-description">
            <h2>商品説明</h2>
            <p class="item-description__content">
                {{ $item->description }}
            </p>
        </div>
        <div class="item-detail">
            <h2>商品の情報</h2>
            <div class="item-categories">
                <h3>カテゴリー</h3>
                @foreach ($item->categories as $category)
                <p>{{ $category->category }}</p>
                @endforeach
            </div>
            <div class="item-condition">
                <h3>商品の状態</h3>
                <p>{{ $item->condition->condition }}</p>
            </div>
            <div class="item-comments">
                <h2>コメント<span>(</span>{{ $comments->count() }}<span>)</span></h2>
                @foreach ($comments as $comment)
                <div class="item-comments__user">
                    <img src="{{ asset('storage/' . $comment->user->image ) }}" alt="">
                    {{ $comment->user->name }}
                </div>
                <div class="item-comments__content">
                    {{ $comment->comment }}
                </div>
                @endforeach
                <div class="item-comments__input">
                    <div class="item-comments__input-title">
                        商品へのコメント
                    </div>
                    <input type="text">
                </div>
                <div class="item-comments__button">
                    <button class="item-comments__button-submit">
                        コメントを送信する
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>