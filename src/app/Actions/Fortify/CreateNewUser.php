<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\CreatesNewUsers;



class CreateNewUser implements CreatesNewUsers
{
    public function create(array $input): User
    {
        $user = User::create([
            'account_name' => $input['account_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

        return $user;
    }
}
