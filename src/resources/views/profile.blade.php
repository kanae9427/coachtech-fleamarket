@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css')}}">
@endsection

@section('content')
<div class="profile-container">
    <h2 class="profile__heading">プロフィール設定</h2>

    <form action="{{ $user->exists ? route('profile.update') : route('profile.store') }}" method="post" enctype="multipart/form-data" class="profile-form">
        @csrf
        @if ($user->exists)
        @method('PUT')
        @endif

        <div class="icon-container">
            <div class="profile-icon">
                @if($user->icon)
                <img src="{{ $user->icon ?  asset($user->icon)  : asset('storage/icons/default.png') }}" alt="プロフィール画像">
                @endif
            </div>
            <div class="icon-input">
                <label class="icon-label" for="icon">画像を選択する</label>
                <input type="file" name="icon" id="icon" class="d-none">
            </div>
            @error('icon')
            <p class="error-text">{{ $message }}</p>
            @enderror
        </div>

        <!-- ユーザー名 -->
        <div class="profile-form-group">
            <label for="username">ユーザー名</label>
            <input type="text" id="username" name="profile_name" class="profile-input" value="{{ old('profile_name', $user->profile_name) }}">
        </div>

        <!-- 郵便番号 -->
        <div class="profile-form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" id="postal_code" name="postal_code" class="profile-input" value="{{ old('postal_code', $user->postal_code) }}">
        </div>

        <!-- 住所 -->
        <div class="profile-form-group">
            <label for="address">住所</label>
            <input type="text" id="address" name="address" class="profile-input" value="{{ old('address', $user->address) }}">
        </div>

        <!-- 建物名 -->
        <div class="profile-form-group">
            <label for="building_name">建物名</label>
            <input type="text" id="building_name" name="building_name" class="profile-input" value="{{ old('building_name', $user->building_name) }}">
        </div>

        <!-- 送信ボタン -->
        <button type="submit" class="btn btn-success">更新する</button>
    </form>
</div>
@endsection