<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Category;

class SellTest extends TestCase
{
    use RefreshDatabase;
    //出品チェック
    public function test_sell_page()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        'password' => bcrypt('password'),
        ]);
        $category = Category::factory()->create(['name' => 'Electronics']);

        $this->actingAs($user);

        $response = $this->get('/sell');
        $response->assertStatus(200)
            ->assertSee('出品する');

        $response = $this->post('/sell', [
            'image' =>  \Illuminate\Http\UploadedFile::fake()->create('item.jpeg', 500, 'image/jpeg'),
            'category_id' => [$category->id],
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'この商品はテスト用です。',
            'price' => 5000
        ])->assertRedirect(route('mypage.show'));

        $savedItem = Item::latest()->first();
        dump($savedItem->brand_name);

        // ✅ データベースに商品情報が保存されているか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'image' =>   $savedItem->image,
            'condition' => '新品',
            'name' => 'テスト商品',
            'description' => 'この商品はテスト用です。',
            'price' => 5000,
            'sold' => false
        ]);

        $this->assertDatabaseHas('category_items', [
            'item_id' => Item::latest()->first()->id,
            'category_id' => $category->id
        ]);
    }
}
