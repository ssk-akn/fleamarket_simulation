@extends('layouts.transaction')

@section('link')
<link rel="stylesheet" href="{{ asset('css/transaction.css') }}">
@endsection

@section('content')
<div class="transaction">
    <div class="other-transactions">
        <h2>その他の取引</h2>
        @foreach ($allTransactions as $transaction)
        <div class="other-transactions__link">
            <a href="/transaction/{{ $transaction->id }}">
                {{ $transaction->name }}
            </a>
        </div>
        @endforeach
    </div>
    <div class="trading-screen">
        <div class="trading-screen__top">
            <div class="trading-screen__top-item">
                <div class="image-circle">
                    <img class="partner-image" src="{{ asset('storage/' . $partner->image) }}" alt="アイコン">
                </div>
                <h2>{{ $partner->name }}さんとの取引画面</h2>
            </div>
            @if ($user->id !== $item->user_id && $order->status === 'pending')
            <form action="/transaction/complete/{{ $order->id }}" method="POST" class="transaction-completed__form">
                @csrf
                <button type="submit" class="transaction-completed__button">
                    取引を完了する
                </button>
            </form>
            @endif
        </div>
        <div class="item-detail">
            <div class="item-image">
                <img src="{{ asset('storage/' . $item->image) }}" alt="商品画像">
            </div>
            <div class="item-detail__item">
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-price">&yen;{{ number_format($item->price) }}</div>
            </div>
        </div>
        <div class="transaction-detail">
            @foreach ($messages as $message)
                @php
                    $isOwnMessage = $user->id === $message->user_id;
                @endphp
                <div class="transaction-item {{ $isOwnMessage ? 'own' : 'other' }}">
                    <div class="transaction-user">
                        <div class="transaction-user__image-circle">
                            <img class="transaction-user__image" src="{{ asset('storage/' . $partner->image) }}" alt="アイコン">
                        </div>
                        <div class="transaction-user__name">{{ $message->user->name }}</div>
                    </div>
                    @if($isOwnMessage)
                    <form action="/transaction/update/{{ $message->id }}" method="POST" class="transaction-item__form">
                        @csrf
                        @method('PATCH')
                        <p class="transaction-message">
                            <textarea name="message">{{ $message->message }}</textarea>
                        </p>
                        <div class="transaction-message__button">
                            <button type="submit" class="button-submit">編集</button>
                            <button type="button" onclick="document.getElementById('delete-form-{{ $message->id }}').submit()" class="button-submit">削除</button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $message->id }}" action="/transaction/delete/{{ $message->id }}" method="POST" class="delete-form">
                        @csrf
                    </form>
                    @else
                    <p class="partner-message">
                        {{ $message->message }}
                    </p>
                    @endif
                </div>
            @endforeach
            <form action="/transaction/{{ $item->id }}" method="post" enctype="multipart/form-data" class="transaction-form">
                @csrf
                <textarea name="message" id="message-input" class="message-input" placeholder="取引メッセージを記入してください">{{ old('message')}}</textarea>
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
@if (!$reviewExists && $completed)
<div class="model-overlay">
    <div class="model-content">
        <div class="model-message">取引が完了しました。</div>
        <form action="/review/{{ $order->id }}" method="post">
            @csrf
            <p class="rating-message">今回の取引相手はどうでしたか？</p>
            <div class="star">
                @for ($i = 5; $i >= 1; $i--)
                <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}">
                <label for="star{{ $i }}">★</label>
                @endfor
            </div>
            <input type="hidden" name="partner_id" value="{{ $partner->id }}">
            <div class="rating-button">
                <button type="submit" class="rating-button__submit">送信する</button>
            </div>
        </form>
    </div>
</div>
@endif
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