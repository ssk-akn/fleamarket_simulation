@extends('layouts.transaction')

@section('content')
<div class="transaction">
    <div class="other-transactions">
        <h2 class="other-transaction__title">
            その他の取引
        </h2>
        <a href="">商品名</a>
    </div>
    <div class="trading-screen">
        <div class="trading-screen__top">
            <img src="{{ asset('storage/image/' . $partner->image) }}" alt="アイコン" class="trading-screen__top-img">
            <h2 class="trading-screen__title">{{ $partner->name }}さんとの取引画面</h2>
            <button type="submit" class="trading-screen__top-button">
                取引を完了する
            </button>
        </div>
        <div class="item-detail">
            <div class="item-image">
                <img src="" alt="商品画像">
            </div>
            <div class="item-name">商品名</div>
            <div class="item-price">商品価格</div>
        </div>
        <div class="transaction-detail">
            @foreach ($messages as $message)
            <div class="transaction-item">
                <div class="transaction-user">
                    <div class="transaction-user__image">
                        <img src="" alt="アイコン">
                    </div>
                    <div class="transaction-user__name">{{ $message->user->name }}</div>
                </div>
                <form action="/transaction/update/{{ $message->id }}" method="POST" class="form__edit">
                    @csrf
                    @method('PATCH')
                    <div class="transaction-message">
                        <input type="text" name="message" value="{{ $message->message }}">
                    </div>
                    @if ($user->id === $message->user_id)
                    <div class="transaction-message__update">
                        <button type="submit" class="button__update">編集</button>
                    </div>
                </form>
                <form action="/transaction/delete/{{ $message->id }}" method="POST" class="form__delete">
                    @csrf
                    <button type="submit" class="button__delete">削除</button>
                </form>
                @endif
            </div>
            @endforeach
            <form action="/transaction/{{ $item->id }}" method="post" enctype="multipart/form-data" class="transaction-form">
                @csrf
                <input type="text" name="message" id="message-input" value="{{ old('message') }}" placeholder="取引メッセージを記入してください">
                <label class="image-input__custom">
                    画像を追加
                    <input type="file" name="image" class="image-input">
                </label>
                <button type="submit" class="transaction-form__button">
                    <img src="{{ asset('image/inputbutton.jpg') }}" alt="送信" class="transaction-form__button-img">
                </button>
                @error('message')
                <p class="error">
                    {{ $message }}
                </p>
                @enderror
                @error('image')
                <p class="error">
                    {{ $message }}
                </p>
                @enderror
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('message-input');
    const storageKey = 'transaction_message';

    const savedMessage = localStorage.getItem(storageKey);
    if (savedMessage) {
        input.value = savedMessage;
    }

    input.addEventListener('input', () => {
        localStorage.setItem(storageKey, input.value);
    });
});
</script>

@if (session('message_sent'))
<script>
    localStorage.removeItem('transaction_message');
</script>
@endif
@endsection