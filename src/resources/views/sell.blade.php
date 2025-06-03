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
            <div class="sell-form__group">
                <h4 class="image-form__heading">商品画像</h4>
                <div class="image-box">
                    <input type="file" name="image" class="d-none" accept="image/png,image/jpeg" id="item-image" />
                    <label class="sell-form__label" for="item-image">画像を選択する</label>
                    <p class="sell-form__error-message">
                        @error('image')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
            </div>

            <h3 class="sell-form__subheading">商品詳細</h3>

            <h4 class="category-form__heading">カテゴリー</h4>
            <div>
                @foreach ($categories as $category)
                <label class="category-label">
                    <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                        {{ is_array(old('category_id')) && in_array($category->id, old('category_id')) ? 'checked' : '' }}>
                    <span>{{ $category->name }}</span>
                </label>
                @endforeach
            </div>

            <!-- バリデーションエラーメッセージ -->
            @error('category_id')
            <div class="error-message">{{ $message }}</div>
            @enderror

    </div>

    <div class="sell-form__group">
        <label class="sell-form__label" for="condition">商品の状態</label>
        <select class="sell-form__select" name="condition" id="condition">
            <option value="" disabled {{ old('condition')  === null ? 'selected' : '' }}>選択してください</option>
            <option value="良好" {{ old('condition') === '良好' ? 'selected' : '' }}>良好</option>
            <option value="目立った傷や汚れなし" {{ old('condition') === '目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
            <option value="やや傷や汚れあり" {{ old('condition') === 'やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
            <option value="状態が悪い" {{ old('condition') === '状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
        </select>
    </div>

    <h3 class="sell-form__subheading">商品名と説明</h3>

    <div class="sell-form__group">
        <label class="sell-form__label" for="name">商品名</label>
        <input class="sell-form__input" type="text" name="name" id="name">
        <p class="sell-form__error-message">
            @error('name')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="sell-form__group">
        <label class="sell-form__label" for="brand_name">ブランド名</label>
        <input class="sell-form__input" type="text" name="brand_name" id="brand_name">
        <p class="sell-form__error-message">
            @error('brand_name')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="sell-form__group">
        <label class="sell-form__label" for="description">商品の説明</label>
        <input class="sell-form__input" type="text" name="description" id="description">
        <p class="sell-form__error-message">
            @error('description')
            {{ $message }}
            @enderror
        </p>
    </div>

    <div class="sell-form__group">
        <label class="sell-form__label" for="price">販売価格</label>
        <input class="sell-form__input" type="number" name="price" id="price">
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