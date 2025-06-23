@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css')}}">
@endsection

@section('content')
<div class="toggle-buttons">
    <a href="{{ url('/?tab=all&search=' . request()->query('search', '')) }}" class="toggle-button {{ $viewType === 'all' ? 'active' : '' }}">おすすめ</a>

    @if(auth()->check())
    <a href="{{ url('/?tab=mylist&search=' . request()->query('search', '')) }}" class="toggle-button {{ $viewType === 'mylist' ? 'active' : '' }}">マイリスト</a>
    @else
    <a href="{{ route('login') }}" class="toggle-button">マイリスト</a>
    @endif
</div>


@if ($items->isNotEmpty())
<div class="items">
    @foreach ($items as $item)
    <div class="item">
        <a class="item-link" href="{{ route('item.show', $item->id) }}">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}"
                onerror="this.style.display='none'; this.insertAdjacentHTML('afterend' , '<div class=\'image-placeholder\'>' + this.alt + '</div>');">
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
<p></p>
@endif


@endsection