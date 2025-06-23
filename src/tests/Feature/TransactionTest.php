<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    //商品購入チェック
    public function test_user_can_purchase_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['sold' => false]);

        $this->actingAs($user);

        $this->get('/purchase/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('購入する');

        $response = $this->post('/purchase/' . $item->id, [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building_name' => 'コーポ渋谷',
            'payment_method' => 'credit_card',
            'status' => 'pending',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold' => true,
        ]);
    }

    use RefreshDatabase;

    //商品購入後sold表記チェック
    public function test_sold_status_update()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['sold' => false]);

        $this->actingAs($user);

        $this->get('/purchase/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('購入する');

        $response = $this->post('/purchase/' . $item->id, [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区1-1-1',
            'shipping_building_name' => 'コーポ渋谷',
            'payment_method' => 'credit_card',
            'status' => 'pending',
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'sold' => true,
        ]);

        $this->get('/?tab=all')
            ->assertStatus(200)
            ->assertSeeText($item->name)
            ->assertSeeText('sold');
    }

    //購入した商品一覧に反映されてるかチェック
    public function test_profile_shows_purchase()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['sold' => false]);

        $this->actingAs($user);

        $this->get('/purchase/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('購入する');

        $this->post('/purchase/' . $item->id, [
            'payment_method' => 'credit_card',
        ])->assertRedirect();

        $this->get('/purchase/success/' . $item->id)
            ->assertRedirect('/mypage');

        $purchase = Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => $user->postal_code,
            'shipping_address' => $user->address,
            'shipping_building_name' => $user->building_name,
            'payment_method' => 'credit_card',
            'status' => 'completed',
        ]);

        $this->get('/mypage?view=purchases') // 購入した商品一覧を表示
            ->assertStatus(200)
            ->assertSeeText($purchase->item->name);
    }

    //支払方法選択チェック
    public function test_selected_payment_method()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->get('/purchase/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('未選択');

        // クレジットカードを選択
        $this->get('/purchase/' . $item->id . '?payment_method=credit_card')
            ->assertStatus(200)
            ->assertSeeText('カード支払い');

        // コンビニ支払いを選択
        $this->get('/purchase/' . $item->id . '?payment_method=convenience_store')
            ->assertStatus(200)
            ->assertSeeText('コンビニ支払い');
    }

    // 配送先変更購入画面に反映チェック
    public function test_address_update_on_purchase()
    {
        $user = User::factory()->create([
                'postal_code' => '749-9730',
                'address' => '東京都新宿区',
                'building_name' => '新宿タワー'
        ]);
        $item = Item::factory()->create();

        $this->actingAs($user);

        session()->put([
            'shipping_postal_code' => $user->postal_code,
            'shipping_address' => $user->address,
            'shipping_building_name' => $user->building_name
        ]);

        $response = $this->get('/purchase/address/' . $item->id);

        $response->assertStatus(200)
            ->assertSee('749-9730')
            ->assertSee('東京都新宿区')
            ->assertSee('新宿タワー');

        $this->put('/purchase/address/' . $item->id, [
            'shipping_postal_code' => '123-4567',
            'shipping_address' => '東京都渋谷区',
            'shipping_building_name' => 'コーポ渋谷',
        ])->assertRedirect('/purchase/' . $item->id);

        $response = $this->followingRedirects()->get('/purchase/' . $item->id);

        $response->assertStatus(200)
            ->assertSee('123-4567')
            ->assertSee('東京都渋谷区')
            ->assertSee('コーポ渋谷');
    }

    //購入商品に変更住所が紐づくかチェック
    public function test_purchase_flow_with_address_update()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'postal_code' => '123-4567',
            'address' => '東京都新宿区',
            'building_name' => '新宿タワー'
        ]);

        $item = Item::factory()->create();

        $this->actingAs($user);

        // ✅ 住所変更画面を開く
        $this->get('/purchase/address/' . $item->id)
            ->assertStatus(200)
            ->assertSee('123-4567')
            ->assertSee('東京都新宿区')
            ->assertSee('新宿タワー');

        // ✅ 住所を変更
        $this->put('/purchase/address/' . $item->id, [
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '東京都渋谷区',
            'shipping_building_name' => 'コーポ渋谷',
        ])->assertRedirect('/purchase/' . $item->id);

        $this->post('/purchase/' . $item->id, [
            'payment_method' => 'credit_card',
        ])->assertRedirect();

        $this->get('/purchase/success/' . $item->id)
            ->assertRedirect('/mypage');

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'shipping_postal_code' => '987-6543',
            'shipping_address' => '東京都渋谷区',
            'shipping_building_name' => 'コーポ渋谷',
        ]);
    }
}
