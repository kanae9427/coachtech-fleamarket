@extends('layouts/auth_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
<div class="login-form">
    <h2 class="login-form__heading">ログイン</h2>
    <div class="login-form__inner">
        <form method="post" action="{{ route('login') }}" class="login-form__form">
            @csrf
            <div class="login-form__group">
                <label class="login-form__label" for="email">メールアドレス</label>
                <input class="login-form__input" type="email" name="email" id="email">
                <p class="login-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="login-form__group">
                <label class="login-form__label" for="password">パスワード</label>
                <input class="login-form__input" type="password" name="password" id="password">
                <p class="login-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <input class="login-form__btn" type="submit" value="ログインする">
        </form>

        <div class="register__link">
            <a class="switch-to-register" href="/register">会員登録の方はこちら</a>
        </div>
    </div>
</div>
@endsection