@extends('layouts/app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/profile.css')}}">
@endsection

@section('content')
<div class="profile-container">
    <h2 class="profile__heading">プロフィール設定</h2>
    <form action="{{ auth()->user()->postal_code ? route('profile.update') : route('profile.store') }}" method="post" enctype="multipart/form-data" class="profile-form">
        @csrf
        @if (auth()->user()->postal_code)
        @method('PUT')
        @endif

        <div class="icon-container">
            <div class="profile-icon">
                <img src="{{ $user->icon ? asset($user->icon) : 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQYV2P4//8/AwAI/AL+ne+6AAAAAElFTkSuQmCC' }}">
            </div>
            <div class="icon-input">
                <label class="icon-label" for="icon">画像を選択する</label>
                <input type="file" name="icon" id="icon" class="d-none" value="{{ old('icon', $user->icon) }}">

            </div>
        </div>
        @error('icon')
        <p class="error-text">{{ $message }}</p>
        @enderror

        <div class="profile-form-body">
            <!-- ユーザー名 -->
            <div class="profile-form-group">
                <label class="form-label" for="username">ユーザー名</label>
                <input type="text" id="username" name="profile_name" class="form-input" value="{{ old('profile_name', $user->profile_name) }}">
            </div>
            @error('profile_name')
            <p class="error-text">{{ $message }}</p>
            @enderror

            <!-- 郵便番号 -->
            <div class="profile-form-group">
                <label class="form-label" for="postal_code">郵便番号</label>
                <input type="text" id="postal_code" name="postal_code" class="form-input" value="{{ old('postal_code', $user->postal_code) }}">
            </div>
            @error('postal_code')
            <p class="error-text">{{ $message }}</p>
            @enderror

            <!-- 住所 -->
            <div class="profile-form-group">
                <label class="form-label" for="address">住所</label>
                <input type="text" id="address" name="address" class="form-input" value="{{ old('address', $user->address) }}">
            </div>
            @error('address')
            <p class="error-text">{{ $message }}</p>
            @enderror

            <!-- 建物名 -->
            <div class="profile-form-group">
                <label class="form-label" for="building_name">建物名</label>
                <input type="text" id="building_name" name="building_name" class="form-input" value="{{ old('building_name', $user->building_name) }}">
            </div>
            @error('building_name')
            <p class="error-text">{{ $message }}</p>
            @enderror

        </div>

        <button type="submit" class="success-btn">更新する</button>
    </form>
</div>
@endsection