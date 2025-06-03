<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;



class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            [
                'name' => '腕時計',
                'price' => 15000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'categories' => ['ファッション', 'メンズ'],
                'condition' => '良好',
                'brand_name' => 'ARMANI',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
            ],
            [
                'name' => 'HDD',
                'price' => 5000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'categories' => ['家電', 'インテリア'],
                'condition' => '目立った傷や汚れなし',
                'brand_name' => '',
                'description' => '高速で信頼性の高いハードディスク',
            ],
            [
                'name' => '玉ねぎ3束',
                'price' => 300,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'categories' => ['キッチン'],
                'condition' => 'やや傷や汚れあり',
                'brand_name' => '北海道産',
                'description' => '新鮮な玉ねぎ3束のセット',
            ],
            [
                'name' => '革靴',
                'price' => 4000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'categories' => ['ファッション', 'メンズ'],
                'condition' => '状態が悪い',
                'brand_name' => '',
                'description' => 'クラシックなデザインの革靴',
            ],
            [
                'name' => 'ノートPC',
                'price' => 45000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'categories' => ['家電'],
                'condition' => '良好',
                'brand_name' => 'Mac',
                'description' => '高性能なノートパソコン',
            ],
            [
                'name' => 'マイク',
                'price' => 8000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'categories' => ['家電', 'おもちゃ'],
                'condition' => '目立った傷や汚れなし',
                'brand_name' => '',
                'description' => '高音質のレコーディング用マイク',
            ],
            [
                'name' => 'ショルダーバッグ',
                'price' => 3500,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'categories' => ['ファッション', 'レディース'],
                'condition' => 'やや傷や汚れあり',
                'brand_name' => 'COACH',
                'description' => 'おしゃれなショルダーバッグ',
            ],
            [
                'name' => 'タンブラー',
                'price' => 500,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'categories' => ['キッチン'],
                'condition' => '状態が悪い',
                'brand_name' => '',
                'description' => '使いやすいタンブラー',
            ],
            [
                'name' => 'コーヒーミル',
                'price' => 4000,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'categories' => ['家電', 'キッチン'],
                'condition' => '良好',
                'brand_name' => '',
                'description' => '手動のコーヒーミル',
            ],
            [
                'name' => 'メイクセット',
                'price' => 2500,
                'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'categories' => ['ファッション', 'レディース','コスメ'],
                'condition' => '目立った傷や汚れなし',
                'brand_name' => '',
                'description' => '便利なメイクアップセット',
            ],
        ];

        // 商品をループして作成
        foreach ($items as $data) {

            $category = Category::where('name', $data['categories'][0])->first();
            $user = User::first();
            $imagePath = 'public/images/' . basename($data['image']);
            Storage::put($imagePath, file_get_contents($data['image']));


            $item = Item::create([
                'name' => $data['name'],
                'price' => $data['price'],
                'image' => 'storage/images/' . basename($data['image']),
                'condition' => $data['condition'],
                'brand_name' => $data['brand_name'],
                'description' => $data['description'],
                'user_id' => $user->id,
            ]);

            // 商品にカテゴリーを紐付ける
            $categories = Category::whereIn('name', $data['categories'])->get();
            $item->categories()->attach($categories);
        }
    }
}
