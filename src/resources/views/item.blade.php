@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-container">
    <div class="item-image">
        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
    </div>

    <div class="item-details">
        <h1>{{ $item->name }}</h1>
        <p class="brand_name">ブランド名&nbsp;&nbsp;{{ $item->brand_name }}</p>
        <p class="price">¥<span class="price-value">{{ number_format($item->price) }}</span>(税込)</p>

        <div class="post-actions">
            <div class="favorite-form">
                @if(auth()->check())
                <form method="post" action="{{ route('item.favorite', $item->id) }}">
                    @csrf
                    <button type="submit" class="favorite-button {{ auth()->check() &&auth()->user()->favorites()->where('item_id', $item->id)->exists() ? 'favorited' : '' }}">☆
                    </button>
                </form>
                @else
                <a href="{{ route('login') }}" class="favorite-button">☆</a>
                @endif
                <p class="favorite-count-number">{{ $favoriteCount }}</p>
            </div>

            <div class="comment-button-wrapper">
                <a href="#comment-form" class="comment-button">💬</a>
                <p class="comment-count-number">{{ $commentCount }}</p>
            </div>
        </div>

        @if ($item->sold)
        <p class="sold-text">sold</p>
        @else
        <a href="{{ route('purchase.show', $item->id) }}" class="purchase-button">購入手続きへ</a>
        @endif

        <div class="item-description">
            <h2 class="item-content__subheading--first">商品説明</h2>
            <p class="description-content">{{ $item->description }}</p>
        </div>

        <div class="item-info">
            <h2 class="item-content__subheading--second">商品の情報</h2>
            <div class="category-section">
                <h4 class="item-info__subheading">カテゴリー</h4>
                <ul class="category-content">
                    @foreach ($item->categories as $category)
                    <li class="category-name">{{ $category->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="condition-section">
                <h4 class="item-info__subheading">商品の状態</h4>
                <p class="condition-content">{{ $item->condition }}</p>
            </div>
        </div>

        <div class="comment-wrapper">
            <h2 class="comment-wrapper__title">コメント({{ $commentCount }})</h2>
            @foreach ($comments as $comment)
            <div class="comment-item">
                <div class="comment-item__header">
                    <img src="{{ $comment->user->icon ? asset($comment->user->icon) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P4//8/AwAI/AL+ne+6AAAAAElFTkSuQmCC' }}" class="comment-item__icon">
                    <p class="comment-item__author">{{ $comment->user->profile_name }}</p>
                </div>
                <div class="comment-item__body">
                    <p class="comment-item__text">{{ $comment->content }}</p>
                    <small class="comment-item__timestamp">{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                </div>
            </div>
            @endforeach

            <!-- コメント入力欄 -->
            <div class="comment-form" id="comment-form">
                <h3 class="comment-form__heading">商品へのコメント</h3>
                <form method="post" action="{{ route('item.comment', $item->id) }}">
                    @csrf
                    <textarea name="content" id="comment-form__textarea"></textarea>
                    <p class="comment-form__error-message">
                        @error('content')
                        {{ $message }}
                        @enderror
                    </p>
                    <button type="submit" class="comment-form__submit">コメントを送信する</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection