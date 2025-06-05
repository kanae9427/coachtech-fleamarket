@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/address_edit.css')}}">
@endsection

@section('content')
<div class="shipping_address-container">
    <h2 class="shipping_address__heading">住所の変更</h2>
    <form action="{{ route('address.update', $item->id) }}" method="post" class="shipping_address-form">
        @csrf
        @method('PUT')

        <!-- 郵便番号 -->
        <div class="shipping_address-form-group">
            <label for="shipping_postal_code">郵便番号</label>
            <input type="text" id="shipping_postal_code" name="shipping_postal_code" value="{{ session('shipping_postal_code', auth()->user()->postal_code) }}" class="shipping_address-input" required>
        </div>

        <!-- 住所 -->
        <div class="shipping_address-form-group">
            <label for="shipping_address">住所</label>
            <input type="text" id="shipping_address" name="shipping_address" value="{{ session('shipping_address', auth()->user()->address) }}"
                class="shipping_address-input" required>
        </div>

        <!-- 建物名 -->
        <div class="shipping_address-form-group">
            <label for="shipping_building_name">建物名</label>
            <input type="text" id="shipping_building_name" name="shipping_building_name" value="{{ session('shipping_building_name', auth()->user()->building_name) }}"
                class="shipping_address-input" required>
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-success">更新する</button>
    </form>
</div>
@endsection