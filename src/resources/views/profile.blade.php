@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
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
<div class="profile-title">
        プロフィール設定
</div>
<form action="/mypage/profile" method="post" enctype="multipart/form-data" class="profile-form">
    @csrf
    <div class="profile-image">
        <div class="profile-image__item">
            @if($user->image)
            <div class="image-circle">
                <img src="{{ asset('storage/' . $user->image) }}" alt="画像" class="user-image">
            </div>
            @else
            <div class="image-circle">
            <img src="" alt="" class="user-image">
            </div>
            @endif
        </div>
        <div class="profile-image__input">
            <label class="profile-image__input-custom">
                画像を選択する
                <input type="file" name="image" id="imageInput" accept="image/*" class="profile-image__input-none">
            </label>
        </div>
    </div>
    <div class="profile-contents">
        <div class="profile-group">
            <label for="name">ユーザー名</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}">
        </div>
        <div class="profile-group">
            <label for="postcode">郵便番号</label>
            <input type="text" name="postcode" id="postcode" value="{{ $user->postcode }}">
        </div>
        <div class="profile-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ $user->address }}">
        </div>
        <div class="profile-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ $user->building }}">
        </div>
        <div class="profile-form__button">
            <button class="profile-form__button-submit">
                更新する
            </button>
        </div>
    </div>
</form>

<script>
    document.getElementById('imageInput').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.user-image').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection