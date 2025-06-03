<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CategorySeeder::class, // カテゴリーを登録
            UserSeeder::class, // ユーザーを登録
            ItemSeeder::class, // アイテムを登録
        ]);
    }
}
