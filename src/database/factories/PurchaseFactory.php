<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Purchase;


class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'item_id' => \App\Models\Item::factory(),
            'shipping_postal_code' => $this->faker->postcode,
            'shipping_address' => $this->faker->address,
            'shipping_building_name' => $this->faker->secondaryAddress,
            'payment_method' => 'credit_card',
            'status' => 'completed',

        ];
    }
}
