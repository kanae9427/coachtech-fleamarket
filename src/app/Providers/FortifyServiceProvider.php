<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use App\Models\User;

class FortifyServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::authenticateUsing(
            function (Request $request) {
                $user = User::where('email', $request->email)->first();

                if (!$user || !$user->hasVerifiedEmail()) {
                    return null; // メール認証済みでない場合、ログイン不可
                }

                return Auth::attempt(['email' => $request->email, 'password' => $request->password]) ? $user : null;
        });

        Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });
    }
}
