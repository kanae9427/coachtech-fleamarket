<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;



class ProductActionsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品一覧チェック
     *
     * @return void
     */
    public function test_item_page()
    {
        Item::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);

        $response->assertSeeText(Item::first()->name);
    }

    //soldが表示されるかチェック
    public function test_sold_items()
    {
        $soldItem = Item::factory()->create([
            'name' => 'Sold Item',
            'sold' => true,
        ]);

        $unsoldItem = Item::factory()->create([
            'name' => 'Unsold Item',
            'sold' => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSeeText($unsoldItem->name);

        $response->assertSeeText($soldItem->name);
        $response->assertSee('sold', false);
    }

    //自分が出品した商品が表示されないかチェック
    public function test_user_does_not_see_their_own_items()
    {
        $userA = User::factory()->create();

        $userB = User::factory()->create();

        $itemA = Item::factory()->create([
            'name' => 'User A Item',
            'user_id' => $userA->id,
        ]);

        $itemB = Item::factory()->create([
            'name' => 'User B Item',
            'user_id' => $userB->id,
        ]);

        $this->actingAs($userA);
        $response = $this->get('/?tab=all');
        $response->assertStatus(200);
        $response->assertDontSeeText($itemA->name);
        $response->assertSeeText($itemB->name);
    }

    //マイリストチェック
    public function test_mylist_items()
    {
        $user = User::factory()->create();
        $favoriteItem = Item::factory()->create(['name' => 'Favorite Item']);

        // ✅ ユーザーが「いいね」した商品を設定
        $user->favorites()->attach($favoriteItem->id);

        $this->actingAs($user)
            ->get('/?tab=mylist') // 🔹 マイリストページを取得
            ->assertStatus(200)
            ->assertSeeText('Favorite Item'); // ✅ いいねした商品が表示される
    }

    //マイリストの購入済商品チェック
    public function test_purchased_items_in_mylist()
    {
        $user = User::factory()->create();
        $favoriteItem = Item::factory()->create(['name' => 'Favorite Item', 'sold' => false]);

        $user->favorites()->attach($favoriteItem->id);
        $favoriteItem->update(['sold' => true]);

        $this->actingAs($user)
            ->get('/?tab=mylist')
            ->assertStatus(200)
            ->assertSeeText('Favorite Item')
            ->assertSeeText('sold');
    }

    //マイリストにも自分の商品が表示されないチェック
    public function test_user_does_not_see_their_own_items_in_mylist()
    {
        $userA = User::factory()->create();

        $userB = User::factory()->create();

        $itemA = Item::factory()->create([
            'name' => 'User A Item',
            'user_id' => $userA->id,
        ]);

        $itemB = Item::factory()->create([
            'name' => 'User B Item',
            'user_id' => $userB->id,
        ]);

        $userA->favorites()->attach($itemB->id);

        $this->actingAs($userA);
        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertDontSeeText($itemA->name);
        $response->assertSeeText($itemB->name);
    }

    //いいねなしの場合チェック
    public function test_empty_mylist_when_no_favorites()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/?tab=mylist')
            ->assertStatus(200)
            ->assertSeeText('');
    }

    //部分一致チェック
    public function test_search_shows_matching_items()
    {
        $matchingItem = Item::factory()->create(['name' => 'Red Jacket']);
        $nonMatchingItem = Item::factory()->create(['name' => 'Blue Jeans']);

        $this->get('/?search=Red') // 🔹 "Red" を検索
            ->assertStatus(200)
            ->assertSeeText('Red Jacket') // ✅ 部分一致する商品が表示される
            ->assertDontSeeText('Blue Jeans'); // ✅ 関係ない商品は表示されない
    }

    //検索情報保持チェック
    public function test_search_persists_across_tabs()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $matchingItem = Item::factory()->create(['name' => 'Red Jacket']);
        $nonMatchingItem = Item::factory()->create(['name' => 'Blue Jeans']);

        $user->favorites()->attach($matchingItem->id);

        $response = $this->get('/?tab=all&search=Red');
        $response->assertStatus(200);
        $response->assertSeeText('Red Jacket');
        $response->assertDontSeeText('Blue Jeans');

        $response = $this->get('/?tab=mylist&search=Red');
        $response->assertStatus(200);
        $response->assertSeeText('Red Jacket');
    }

    //商品詳細チェック
    public function test_item_information()
    {
        $category = Category::factory()->create(['name' => 'Electronics']);
        $item = Item::factory()->create([
            'name' => 'Laptop',
            'price' => 15000,
            'description' => 'High-performance laptop.',
            'image' => 'https://example.com/laptop.jpg',
            'condition' => '新品',
            'brand_name' => 'TechBrand',
        ]);

        $item->categories()->attach($category->id);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200)
            ->assertSeeText('Laptop')
            ->assertSeeText('¥15,000(税込)')
            ->assertSeeText('Electronics')
            ->assertSeeText('新品')
            ->assertSeeText('TechBrand')
            ->assertSeeText('High-performance laptop.')
            ->assertSee('https://example.com/laptop.jpg');
    }

    //カテゴリーチェック
    public function test_item_categories()
    {
        $category1 = Category::factory()->create(['name' => 'Electronics']);
        $category2 = Category::factory()->create(['name' => 'Gaming']);

        $item = Item::factory()->create([
            'name' => 'Gaming Laptop',
            'price' => 200000,
            'description' => 'High-performance gaming laptop.',
            'image' => 'https://example.com/gaming-laptop.jpg',
            'condition' => '新品',
            'brand_name' => 'TechPower',
        ]);

        $item->categories()->attach([$category1->id, $category2->id]);

        $response = $this->get('/item/' . $item->id);

        $response->assertStatus(200)
            ->assertSeeText('Gaming Laptop')
            ->assertSeeText('¥200,000(税込)')
            ->assertSeeText('新品')
            ->assertSeeText('TechPower')
            ->assertSeeText('High-performance gaming laptop.')
            ->assertSee('https://example.com/gaming-laptop.jpg')
            ->assertSeeText('Electronics')
            ->assertSeeText('Gaming');
    }

    //いいね登録チェック
    public function test_user_can_like_item()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create(['name' => 'Gaming Laptop',]);

        $this->actingAs($user);
        $this->assertEquals(0, $user->favorites()->count());
        $this->post('/item/' . $item->id . '/favorite');
        $this->assertEquals(1, $user->favorites()->count());
    }

    //いいねアイコンチェック
    public function test_like_button_changes_color()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user)
            ->get('/item/' . $item->id)
            ->assertDontSee('favorited');

        $user->favorites()->attach($item->id);

        $this->get('/item/' . $item->id)
            ->assertSee('<button type="submit" class="favorite-button favorited">', false);

    }

    //いいね解除チェック
    public function test_user_can_toggle_favorite()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->assertEquals(0, $user->favorites()->count());

        $this->post('/item/' . $item->id . '/favorite');
        $this->assertEquals(1, $user->favorites()->count());

        $this->post('/item/' . $item->id . '/favorite');
        $this->assertEquals(0, $user->favorites()->count());
    }

    //コメントチェック
    public function test_user_can_post_comment()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $this->get('/item/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('コメント(0)');

        $this->post('/item/' . $item->id . '/comment', [
            'content' => 'これはテストコメントです。',
        ]);

        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'これはテストコメントです。',
        ]);

        $this->get('/item/' . $item->id)
            ->assertStatus(200)
            ->assertSeeText('コメント(1)');
    }

    //未ログインコメントチェック
    public function test_guest_cannot_post_comment()
    {
        $item = Item::factory()->create();

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => '未ログインユーザーのコメント',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'content' => '未ログインユーザーのコメント',
        ]);
    }

    //コメント入力なしエラー文チェック
    public function test_comment_validation_fails_for_empty_content()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => '',
        ]);

        $response->assertSessionHasErrors(['content']);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    //256文字以上エラー文
    public function test_comment_validation_fails_for_long_content()
    {
        $user = User::factory()->create();
        $item = Item::factory()->create();

        $this->actingAs($user);

        $longComment = str_repeat('あ', 257);

        $response = $this->post('/item/' . $item->id . '/comment', [
            'content' => $longComment,
        ]);

        $response->assertSessionHasErrors(['content']);

        $this->assertDatabaseMissing('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => $longComment,
        ]);
    }

}
