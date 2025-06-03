<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\CreatesNewUsers;


class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        // `RegisterRequest` でバリデーション済みのデータが渡されてくる前提
        $user = User::create([
            'account_name' => $input['account_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        Auth::login($user);

        return $user;
    }
}
