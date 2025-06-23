<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function store(RegisterRequest $request, CreateNewUser $creator)
    {
        $validated = $request->validated();
        $creator->create($validated);

        return redirect('/email/verify')->with('success', 'ユーザー登録が完了しました。メール認証をしてください！');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ]);
        }

        $user = Auth::user();

        // ✅ メール認証済みかチェック！
        if (!$user || !$user->email_verified_at) {
            return back()->withErrors([
                'email' => 'メール認証を完了してください',
            ]);
        }

        $request->session()->regenerate();
        return redirect('/');
    }
}
