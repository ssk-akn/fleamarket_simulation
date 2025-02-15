@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
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
<div class="purchase">
    <form action="" method="POST" class="purchase-form">
        @csrf
        <div class="purchase-detail">
            <div class="item-confirm">
                <div class="item-confirm__img">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
                </div>
                <div class="item-confirm__detail">
                    <div class="item-confirm__name">
                        {{ $item->name }}
                    </div>
                    <div class="item-confirm__price">
                        {{ number_format($item->price) }}
                    </div>
                </div>
            </div>
            <div class="item-payment">
                <div class="item-payment__title">
                    支払い方法
                </div>
                <div class="item-payment__select">
                    <form action="/purchase/update-payment" method="post" class="update-payment">
                        <select name="payment" onchange="this.form.submit()">
                            <option value="">選択してください</option>
                            <option value="コンビニ払い" {{ $payment === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                            <option value="カード支払い" {{ $payment === 'クレジット支払い' ? 'selected' : '' }}>カード支払い</option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="item-destination">
                <div class="item-destination__detail">
                    <div class="item-destination__title">
                        配送先
                    </div>
                    <div class="item-destination__address">
                        <input type="text" name="postcode" value="{{ session('new_postcode', $user->postcode) }}" readonly>
                        <input type="text" name="address" value="{{ session('new_address', $user->address) }}" readonly>
                        <input type="text" name="building" value="{{ session('new_building, $user->building) ?? '' }}" readonly>
                    </div>
                </div>
                <div class="item-destination__change">
                    <a href="/purchase/address/{{ $item->id }}">変更する</a>
                </div>
            </div>
        </div>
        <div class="purchase-confirm">
            <table confirm__table>
                <tr class="confirm__row">
                    <th class="confirm__label">
                        商品代金
                    </th>
                    <td class="confirm__date">
                    &yen;{{ number_format($item->price) }}
                    </td>
                </tr>
                <tr class="confirm__row">
                    <th class="confirm__label">
                        支払い方法
                    </th>
                    <td class="confirm__date">
                        {{ $payment }}
                    </td>
                </tr>
            </table>
            <form action="/purchase" method="POST" class="purchase-form">
                <input type="hidden" name="item_id" value="{{ $item->id }}">
                <input type="hidden" name="payment" value="{{ $payment }}">
                <input type="hidden" name="postcode" value="{{ session('new_postcode', $user->postcode) }}">
                <input type="hidden" name="address" value="{{ session('new_address', $user->address) }}">
                <input type="hidden" name="building" value="{{ session('new_building, $user->building) ?? '' }}">
                <div class="purchase-button">
                    <button class="purchase-button__submit" type="submit">
                        購入する
                    </button>
                </div>
            </form>
        </div>
    </form>
</div>