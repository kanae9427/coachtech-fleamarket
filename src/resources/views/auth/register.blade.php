@extends('layouts/auth_app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <div class="register-form__inner">
        <form class="register-form__form" action="{{ route('register') }}" method="post">
            @csrf
            <h2 class="register-form__heading">会員登録</h2>
            <div class="register-form__group">
                <label class="register-form__label" for="name">ユーザー名</label>
                <input class="register-form__input" type="text" name="account_name" id="name" value="{{ old('account_name') }}">
                <p class="register-form__error-message">
                    @error('account_name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="email">メールアドレス</label>
                <input class="register-form__input" type="email" name="email" id="email" value="{{ old('email') }}">
                <p class="register-form__error-message">
                    @error('email')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password">パスワード</label>
                <input class="register-form__input" type="password" name="password" id="password">
                <p class="register-form__error-message">
                    @error('password')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="register-form__group">
                <label class="register-form__label" for="password_confirmation">確認用パスワード</label>
                <input class="register-form__input" type="password" name="password_confirmation" id="password_confirmation">
                <p class="register-form__error-message">
                    @error('password_confirmation')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="form-submit">
                <input class="register-form__btn btn" type="submit" value="登録する">
            </div>
        </form>

        <div class="login__link">
            <a class="switch-to-login" href="/login">ログインはこちら</a>
        </div>
    </div>
</div>
@endsection