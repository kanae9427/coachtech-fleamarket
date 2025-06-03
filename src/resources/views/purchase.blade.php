@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css')}}">
@endsection

@section('content')
<div class="purchase-container">
    <!-- 商品購入フォーム -->
    <div class="purchase-form">

        <!-- 商品情報 -->
        <div class="item-container">
            <img src="{{ asset($item->image) }}" alt="{{ $item->name }}" class="item-image">

            <label class="item-name">{{ $item->name }}</label>
            <label class="item-price">¥{{ $item->price }}</label>
        </div>

        <!-- 商品データを送信 -->
        <input type="hidden" name="item_id" value="{{ $item->id }}">

        <!-- 支払方法の選択 -->
        <div class="payment-container">
            <form method="get" action="{{ route('purchase.show', $item->id) }}">
                <label for="payment_method" class="payment_method">支払い方法</label>
                <select name="payment_method" id="payment_method" class="payment-select" onchange="this.form.submit()" onfocus="this.options[0].hidden=true;">
                    <option value="" disabled {{ $errors->has('payment_method') ? 'selected' : (empty(old('payment_method')) ? 'selected' : '') }}>選択してください</option>
                    <option value="convenience_store" {{ old('payment_method') === 'convenience_store' ? 'selected' : '' }}>コンビニ支払い</option>
                    <option value="credit_card" {{ old('payment_method') === 'credit_card' ? 'selected' : '' }}>カード支払い</option>
                </select>
            </form>
            @error('payment_method')
            <p class="error-text">{{ $message }}</p>
            @enderror
        </div>


        <!-- 配送先（変更可能） -->
        <div class="shipping-container">
            <label class="shipping-title">配送先</label>
            <p class="shipping-address">
                {{ session('shipping_postal_code', auth()->user()->postal_code) }}<br>
                {{ session('shipping_address', auth()->user()->address) }}<br>
                {{ session('shipping_building_name', auth()->user()->building_name) }}
            </p>
            <!-- 変更ボタン -->
            <form method="get" action="{{ route('address.edit', ['item_id' => $item->id]) }}">
                <button type="submit" class="change-button">変更する</button>
            </form>
        </div>

        <form method="post" action="{{ route('purchase.store', $item->id) }}">
            @csrf
            <input type="hidden" name="item_id" value="{{ $item->id }}">
            <input type="hidden" name="shipping_postal_code" value="{{ session('shipping_postal_code', auth()->user()->postal_code) }}">
            <input type="hidden" name="shipping_address" value="{{ session('shipping_address', auth()->user()->address) }}">
            <input type="hidden" name="shipping_building_name" value="{{ session('shipping_building_name', auth()->user()->building_name) }}">
            <input type="hidden" name="payment_method" value="{{ $payment_method }}">

            <button type="submit" class="purchase-button">購入する</button>
        </form>
    </div>

    <!-- 購入情報枠 -->
    <div class="purchase-summary">
        <p>商品代金 ¥<span id="price_display">{{ $item->price }}</span></p>
        <p>支払方法 <span id="payment_display">{{ $payment_method_jp }}</span></p>
    </div>
</div>
@endsection