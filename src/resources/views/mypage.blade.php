@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-container">
    <!-- プロフィール -->
    <div class="profile-header">
        <img src="{{ auth()->user()->icon ? asset('storage/' . auth()->user()->icon) : asset('default-profile.png') }}" alt="プロフィールアイコン">
        <h2>{{ auth()->user()->profile_name }}</h2>
        <a href="{{ route('profile.show') }}" class="edit-button">プロフィールを編集</a>
    </div>

    <!-- 商品カテゴリの切り替え -->
    <div class="item-tabs">
        <a href="{{ route('mypage.show', ['view' => 'items']) }}" class="{{ $viewType === 'items' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.show', ['view' => 'purchases']) }}" class="{{ $viewType === 'purchases' ? 'active' : '' }}">購入した商品</a>
    </div>

    <!-- 商品リスト -->
    <div class="item-list">
        @if($items->isNotEmpty())

        @foreach($items as $item)
        <div class="item-card">
            <a href="{{ route('item.show', $item->id) }}">
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}">
            </a>

            <p>
                <a href="{{ route('item.show', $item->id) }}">{{ $item->name }}</a>
            </p>

            @if ($item->sold)
            <span class="sold-label">sold</span>
            @endif
        </div>
        @endforeach

        @else
        <p>まだ商品はありません。気になる商品を探してみましょう！</p>
        @endif
    </div>
</div>
@endsection