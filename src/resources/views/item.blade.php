@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/item.css') }}">
@endsection

@section('content')
<div class="item-container">
    <!-- 左側: 商品画像 -->
    <div class="item-image">
        <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
    </div>

    <!-- 右側: 商品詳細 -->
    <div class="item-details">
        <h1>{{ $item->name }}</h1>
        <p>{{ $item->brand_name }}</p>
        <p>¥{{ number_format($item->price) }}(税込)</p>

        <!-- お気に入りボタン -->
        <div class="favorite-form">
            @if(auth()->check())
            <form method="post" action="{{ route('item.favorite', $item->id) }}">
                @csrf
                <button type="submit" class="favorite-button {{ auth()->check() &&auth()->user()->favorites()->where('item_id', $item->id)->exists() ? 'favorited' : '' }}">
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" class="favorite-button">ログインしてお気に入りに追加</a>
            @endif
            <p>{{ $favoriteCount }}</p>
        </div>

        <!-- コメントボタン -->
        <a href="#comment-form" class="comment-button">💬</a>
        <p>{{ $commentCount }}</p>

        @if ($item->sold)
        <p class="sold-text">sold</p>
        @else
        <a href="{{ route('purchase.show', $item->id) }}" class="purchase-button">購入手続きへ</a>
        @endif

        <h2 class="item-content">商品説明</h2>
        <p class="description-content">{{ $item->description }}</p>
        <h2>商品の情報</h2>
        <p>カテゴリ</p>
        <ul class="category-content">
            @foreach ($item->categories as $category)
            <li class="category-name">{{ $category->name }}</li>
            @endforeach
        </ul>
        <p class="item-content">商品の状態</p>
        <p class="condition-content">{{ $item->condition }}</p>

        <h2 class="comments-title">コメント数: {{ $commentCount }}</h2>
        <!-- コメント一覧 -->
        <div class="comments-section">
            @foreach ($item->comments as $comment)
            <div class="comment">
                <img src="{{ asset($comment->user->icon) }}" alt="{{ $comment->user->name }}" class="avatar">
                <div class="comment-content">
                    <strong class="comment-author">{{ $comment->user->name }}</strong>
                    <p class="comment-text">{{ $comment->content }}</p>
                    <small>{{ $comment->created_at->format('Y-m-d H:i') }}</small>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- コメント入力欄 -->
    <div class="comment-form">
        <form method="post" action="{{ route('item.comment', $item->id) }}">
            @csrf
            <textarea name="content" id="comment-form"></textarea>
            <button type="submit">コメントを送信する</button>

            <p class="comment-form__error-message">
                @error('content')
                {{ $message }}
                @enderror
            </p>
        </form>
    </div>
</div>
</div>

@endsection