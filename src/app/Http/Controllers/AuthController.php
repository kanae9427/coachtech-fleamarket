<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\CreateNewUser;
use App\Http\Requests\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function store(RegisterRequest $request, CreateNewUser $creator)
    {
        $validated = $request->validated();
        $creator->create($validated);

        // リダイレクト先を指定
        return redirect('/mypage/profile')->with('success', 'ユーザー登録が完了しました。プロフィールを設定してください！');
    }
}
