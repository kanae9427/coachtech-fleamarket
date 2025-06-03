@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css')}}">
@endsection

@section('content')
<div class="toggle-buttons">
    <a href="{{ url('/?tab=all') }}" class="toggle-button {{ request()->query('tab', 'all') === 'all' ? 'active' : '' }}">商品一覧</a>

    @if(auth()->check())
    <a href="{{ url('/?tab=mylist') }}" class="toggle-button {{ request()->query('tab') === 'mylist' ? 'active' : '' }}">マイリスト</a>
    @else
    <a href="{{ route('login') }}" class="toggle-button">マイリスト</a>
    @endif
</div>


@if ($items->isNotEmpty())
<div class="items">
    @foreach ($items as $item)
    <div class="item">
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
</div>
@else
<p class="no-items">現在、表示できる商品がありません。</p>
@endif


@endsection