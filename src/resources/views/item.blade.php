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
<div class="item-page">
    <div class="item-image">
        <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
    </div>
    <div class="item-contents">
        <div class="item-name">
            {{ $item->name }}
        </div>
        <div class="item-brand">
            {{ $item->brand }}
        </div>
        <div class="item-price">
            &yen;<span class="price-span">{{ number_format($item->price) }}</span>（税込）
        </div>
        <div class="item-reaction">
            <div class="item-reaction__likes">
                <form action="{{ $isLiked ? route('item.destroy', ['item_id' => $item->id]) : route('item.store', ['item_id' => $item->id]) }}" method="post">
                    @csrf
                    <button class="item-reaction__likes-button">
                        @if ($isLiked)
                        <img src="{{ asset('image/y_star.png') }}" alt="いいね解除">
                        @else
                        <img src="{{ asset('image/star.png') }}" alt="いいね">
                        @endif
                    </button>
                </form>
                <div class="item-reaction__likes-count">
                    {{ $likeCount }}
                </div>
            </div>
            <div class="item-reaction__comments">
                <img src="{{ asset('image/comment.png') }}" alt="コメント">
                <div class="item-reaction__comments-count">
                    {{ $comments->count() }}
                </div>
            </div>
        </div>
        <div class="item-purchase__button">
            <a class="item-purchase__button-submit" href="/purchase/{{ $item->id }}">
                購入手続きへ
            </a>
        </div>
        <div class="item-description">
            <div class="item-description__title">
                商品説明
            </div>
            <p class="item-description__content">
                {{ $item->description }}
            </p>
        </div>
        <div class="item-detail">
            <div class="item-detail__title">
                商品の情報
            </div>
            <div class="item-categories">
                <div class="item-categories__title">
                    カテゴリー
                </div>
                <div class="item-categories__wrap">
                    @foreach ($item->categories as $category)
                    <p>{{ $category->category }}</p>
                    @endforeach
                </div>
            </div>
            <div class="item-condition">
                <div class="item-condition__title">
                    商品の状態
                </div>
                <p>{{ $item->condition->condition }}</p>
            </div>
        </div>
        <div class="item-comments">
            <div class="item-comments__title">
                コメント({{ $comments->count() }})
            </div>
            @foreach ($comments as $comment)
            <div class="item-comments__table">
                <div class="item-comments__user">
                    <div class="image-circle">
                        <img class="user-image" src="{{ asset('storage/' . $comment->user->image ) }}">
                    </div>
                    <div class="item-comments__user-name">
                        {{ $comment->user->name }}
                    </div>
                </div>
                <div class="item-comments__content">
                    {{ $comment->comment }}
                </div>
            </div>
            @endforeach
            <form action="/item/{{$item->id}}/comment" method="post" class="item-comments__form">
                @csrf
                <div class="item-comments__post">
                    <div class="item-comments__post-title">
                        商品へのコメント
                    </div>
                    <textarea name="comment" class="item-comments__text"></textarea>
                    @error('comment')
                    <p class="error">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
                <div class="item-comments__button">
                    <button class="item-comments__button-submit" type="submit">
                        コメントを送信する
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
