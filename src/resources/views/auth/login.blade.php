@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading">ログイン</h2>
    <div class="login-form__inner">
        <form class="login-form__form" action="\login" method="post">
            @csrf
            @if ($errors->has('login'))
            <p class="login-form__error-message">
                {{ $errors->first('login') }}
            </p>
            @endif
            <div class="login-form__group">
                <label for="login" class="login-form__label">ユーザー名 / メールアドレス</label>
                <input type="text" name="login" id="login" class="login-form__input">
                <p class="login-form__error-message">
                    @error('login')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label for="password" class="login-form__label">パスワード</label>
                <input type="password" name="password" id="password" class="login-form__input">
                <p class="login-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__button">
                <button class="login-form__button-submit">ログインする</button>
            </div>
        </form>
        <div class="register-link">
            <a href="/register">会員登録はこちら</a>
        </div>
    </div>
</div>
@endsection