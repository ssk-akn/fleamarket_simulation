@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
@endsection

@section('content')
<div class="verify-page">
    <div class="verify-message">
        メールアドレスの確認が必要です。</br>確認メールの確認用リンクをクリックしてください。
    </div>
    @if (session('status') == 'verification-link-sent')
    <div class="completion-message">
        新しいリンクが送信されました。
    </div>
    @endif
    <div class="verification-mail">
        <div class="verification-mail__send">
            確認メールの再送はこちら
        </div>
        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button class="verification-mail__button" type="submit">
                再送する
            </button>
        </form>
    </div>
</div>
@endsection