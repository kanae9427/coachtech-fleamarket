<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    //マイページチャック
    public function test_mypage()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/mypage');
        $response->assertStatus(200)
            ->assertSee($user->profile_name)
            ->assertSee($user->icon);

        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id
        ]);
        foreach ($items as $item) {
            $response = $this->get('/mypage?view=items');
            $response->assertSee($item->name);
        }

        $purchasedItems = Purchase::factory()->count(2)->create([
            'user_id' => $user->id
        ]);
        foreach ($purchasedItems as $purchasedItem) {
            $response = $this->get('/mypage?view=purchases');
            $response->assertSee($purchasedItem->item->name);
        }
    }

    //プロフィール編集画面チェック
    public function test_profile_page()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'profile_name' => 'テストユーザー',
            'icon' => 'https://example.com/profile.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building_name' => '新宿タワー'
        ]);

        $this->actingAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200)
            ->assertSee($user->icon)
            ->assertSee($user->profile_name)
            ->assertSee($user->postal_code)
            ->assertSee($user->address)
            ->assertSee($user->building_name);
    }
}
