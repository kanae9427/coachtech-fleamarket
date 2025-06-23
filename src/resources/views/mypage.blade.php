@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/mypage.css')}}">
@endsection

@section('content')
<div class="mypage-container">
    <!-- プロフィール -->
    <div class="profile-header">
        <img src="{{ $user->icon ? asset($user->icon) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P4//8/AwAI/AL+ne+6AAAAAElFTkSuQmCC' }}">
        <h2>{{ auth()->user()->profile_name }}</h2>
        <a class="edit-button" href="{{ route('profile.show') }}">プロフィールを編集</a>
    </div>

    <!-- 商品カテゴリの切り替え -->
    <div class="toggle-buttons">
        <a href="{{ route('mypage.show', ['view' => 'items']) }}" class="toggle-button {{ $viewType === 'items' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.show', ['view' => 'purchases']) }}" class="toggle-button {{ $viewType === 'purchases' ? 'active' : '' }}">購入した商品</a>
    </div>

    <!-- 商品リスト -->
    @if($items->isNotEmpty())
    <div class="items">
        @foreach($items as $item)
        <div class="item">
            <a class="item-link" href="{{ route('item.show', $item->id) }}">
                <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" onerror="this.style.display='none'; this.insertAdjacentHTML('afterend' , '<div class=\'image-placeholder\'>' + this.alt + '</div>');">
            </a>

            <p class="item-label">
                <a class="item-name" href="{{ route('item.show', $item->id) }}">{{ $item->name }}</a>
                @if ($item->sold)
                <span class="sold-label">sold</span>
                @endif
            </p>
        </div>
        @endforeach
    </div>
    @else
    <p class="no-items">まだ商品はありません。気になる商品を探してみましょう！</p>
    @endif
</div>
@endsection