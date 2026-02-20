<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderArticleFactory extends Factory
{
    protected $model = OrderArticle::class;

    public function definition(): array
    {
        return [
            'orders_id' => Order::factory()->create(),
            'article_id' => fake()->numberBetween(1000, 9999),
            'amount' => fake()->randomFloat(3, 1, 100),
            'price' => fake()->randomFloat(2, 10, 100),
            'price_eur' => function (array $attributes) {
                return $attributes['price'];
            },
            'currency' => 'EUR',
            'measure' => 'mq',
            'delivery_time_min' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'delivery_time_max' => fake()->dateTimeBetween('+2 weeks', '+3 weeks'),
            'weight' => fake()->randomFloat(3, 10, 50),
            'packaging_count' => fake()->randomFloat(3, 1, 10),
            'packaging' => fake()->randomFloat(3, 1, 5),
            'pallet' => fake()->randomFloat(3, 50, 100),
            'swimming_pool' => fake()->boolean(10),
        ];
    }
}