<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;



class UserAuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * 会員登録時バリデーションチェック
     *
     * @return void
     * @dataProvider registrationValidationProvider
     */
    public function test_registration_validation_errors($input, $errorField, $expectedMessage)
    {
        $response = $this->post('/register', $input);
        $response->assertSessionHasErrors([$errorField => $expectedMessage]);
    }

    public function registrationValidationProvider()
    {
        return [
            [['account_name' => '', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'password123'], 'account_name', 'お名前を入力してください'],
            [['account_name' => 'Test', 'email' => '', 'password' => 'password123', 'password_confirmation' => 'password123'], 'email', 'メールアドレスを入力してください'],
            [['account_name' => 'Test', 'email' => 'test@example.com', 'password' => '', 'password_confirmation' => 'password123'], 'password', 'パスワードを入力してください'],
            [['account_name' => 'Test', 'email' => 'test@example.com', 'password' => 'short', 'password_confirmation' => 'password123'], 'password', 'パスワードは8文字以上で入力してください'],
            [['account_name' => 'Test', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => 'wrongpass'],'password','パスワードと一致しません'],
            [['account_name' => 'Test', 'email' => 'test@example.com', 'password' => 'password123', 'password_confirmation' => ''], 'password_confirmation', '確認用パスワードを入力してください'],
        ];
    }

    //登録リクエスト送信、リダイレクトチェック
    public function test_registration_redirects()
    {
        $response = $this->post('/register', [
            'account_name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);

        $response->assertRedirect('/email/verify');
    }

    /**
     *  ログイン時バリデーションチェック
     * @dataProvider loginValidationProvider
     */
    public function test_login_validation_errors($input, $errorField, $expectedMessage)
    {
        $response = $this->post('/login', $input);
        $response->assertSessionHasErrors([$errorField => $expectedMessage]);
    }
    
    public function loginValidationProvider()
    {
        return [
            [['email' => '', 'password' => 'password123'], 'email', 'メールアドレスを入力してください'],
            [['email' => 'test@example.com', 'password' => ''], 'password', 'パスワードを入力してください'],
            [['email' => 'invalid-email@example.com', 'password' => 'password123'], 'email', 'ログイン情報が登録されていません'],
            [['email' => 'test@example.com', 'password' => 'passwo321'], 'email', 'ログイン情報が登録されていません'],
        ];
    }

    //ログイン処理チェック
    public function test_login_success()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect('/');
    }

    //ログアウトチェック
    public function test_user_logout()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user);

        $response = $this->post('/logout');

        $this->assertGuest();

        $response->assertSessionMissing('user');

    }
}
