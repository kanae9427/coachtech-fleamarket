@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-form">
    <h2 class="sell-form__heading">商品の出品</h2>
    <div class="sell-form__inner">
        <form class="sell-form__form" action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="image-form__group">
                <h4 class="image-form__heading">商品画像</h4>
                <div class="image-box">
                    <input type="file" name="image" class="d-none" accept="image/png,image/jpeg" id="item-image" />
                    <label class="image-form__label" for="item-image">画像を選択する</label>
                </div>
                <p class="sell-form__error-message">
                    @error('image')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <h3 class="sell-form__subheading--first">商品詳細</h3>

            <div class="category__group">
                <h4 class="category-form__heading">カテゴリー</h4>
                <div class="category-form__group">
                    @foreach ($categories as $category)
                    <input class="category-checkbox" type="checkbox" id="checkbox-{{ $category->id }}" name="category_id[]" value="{{ $category->id }}"
                        {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                    <label for="checkbox-{{ $category->id }}" class="category-label">{{ $category->name }}
                    </label>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('category_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>

    </div>

    <div class="condition-form__group">
        <label class="condition-form__label" for="condition">商品の状態</label>
        <div class="condition-wrapper">
            <select class="condition-form__select" name="condition" id="condition">
                <option value="" disabled hidden {{ old('condition')  === null ? 'selected' : '' }}>選択してください</option>
                <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
                <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
            </select>
            <div class="triangle">▼</div>
        </div>
        <p class="sell-form__error-message">
            @error('condition')
            {{ $message }}
            @enderror
        </p>
    </div>

    <h3 class="sell-form__subheading--second">商品名と説明</h3>

    <div class="name-form__group">
        <label class="name-form__label" for="name">商品名</label>
        <input class="name-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
        >
        <p class="sell-form__error-message">
            @error('name')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="brand_name-form__group">
        <label class="brand_name-form__label" for="brand_name">ブランド名</label>
        <input class="brand_name-form__input" type="text" name="brand_name" id="brand_name" value="{{ old('brand_name') }}">
        >
        <p class="sell-form__error-message">
            @error('brand_name')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="description-form__group">
        <label class="description-form__label" for="description">商品の説明</label>
        <textarea class="description-form__input" type="text" name="description" id="description">{{ old('description') }}</textarea>
        <p class="sell-form__error-message">
            @error('description')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="price-form__group">
        <label class="price-form__label" for="price">販売価格</label>
        <div class="price-form__wrapper">
            <div class="yen-symbol">￥</div>
            <input class="price-form__input" type="number" name="price" id="price" value="{{ old('price') }}">
        </div>

        <p class="sell-form__error-message">
            @error('price')
            {{ $message }}
            @enderror
        </p>
    </div>

    <input class="sell-form__btn" type="submit" value="出品する">
    </form>
</div>
</div>
@endsection