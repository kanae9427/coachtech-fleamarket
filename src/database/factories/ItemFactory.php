<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Category;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(100, 10000),
            'image' => $this->faker->imageUrl(),
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし','やや傷や汚れあり','状態が悪い']),
            'brand_name' => $this->faker->company(),
            'description' => $this->faker->text(),
            'user_id' => \App\Models\User::factory(),
            'sold' => $this->faker->boolean(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Item $item) {
            $category = Category::factory()->create();
            $item->categories()->attach($category->id);
        });
    }
}
