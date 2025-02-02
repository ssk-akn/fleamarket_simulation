@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h2 class="register-form__heading">会員登録</h2>
    <div class="register-form__inner">
        <form class="register-form__form" action="\register" method="post">
            @csrf
            <div class="register-form__group">
                <label for="name" class="register-form__label">ユーザー名</label>
                <input type="text" name="name" id="name" class="register-form__input">
                <p class="register-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label for="email" class="register-form__label">メールアドレス</label>
                <input type="email" name="email" id="email" class="register-form__input">
                <p class="register-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label for="password" class="register-form__label">パスワード</label>
                <input type="password" name="password" id="password" class="register-form__input">
                <p class="register-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label for="password_confirmation" class="register-form__label">確認用パスワード</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="register-form__input">
                <p class="register-form__error-message">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__button">
                <button class="register-form__button-submit">登録する</button>
            </div>
        </form>
        <div class="login-link">
            <a href="/login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection