@extends('layouts/app')


@section('css')
<link rel="stylesheet" href="{{ asset('css/verify_email.css')}}">
@endsection

@section('content')
<p>登録していただいたメールアドレスに認証メールを送付しました。</p>
<p>メール認証を完了してください。</p>
<a href="{{ route('verification.notice') }}" class="btn btn-primary">
    認証はこちらから
</a>
<form method="post" action="{{ route('verification.resend') }}">
    @csrf
    <button type="submit">認証メールを再送する</button>
</form>
@endsection