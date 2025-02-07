@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
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