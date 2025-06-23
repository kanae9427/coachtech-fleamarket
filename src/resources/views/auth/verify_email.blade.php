@extends('layouts/auth_app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verify_email.css')}}">
@endsection

@section('content')

@if(session('message'))
<div class="alert alert-success">
    {{ session('message') }}
</div>
@endif
<div>
    <p class="verify-message">登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。</p>
</div>
@if(Auth::check() && !Auth::user()->hasVerifiedEmail())
<form method="post" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit" class="btn btn-primary">認証はこちらから</button>
</form>

<form method="post" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit" class="resend-email">認証メールを再送する</button>
</form>

@endif

@endsection