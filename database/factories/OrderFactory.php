<?php

namespace Database\Factories;

use App\Enums\Order\OrderStatus;
use App\Enums\Order\PayType;
use App\Enums\Order\VatType;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-1 year');

        return [
            'hash' => Str::random(32),
            'user_id' => fake()->optional()->randomNumber(),
            'token' => Str::random(64),
            'number' => 'ORD-' . fake()->numberBetween(1000, 9999),
            'status' => fake()->randomElement(OrderStatus::cases()),
            'pay_type' => fake()->randomElement(PayType::cases()),
            'vat_type' => fake()->randomElement(VatType::cases()),

            'email' => fake()->safeEmail(),
            'client_name' => fake()->firstName(),
            'client_surname' => fake()->lastName(),
            'company_name' => fake()->optional()->company(),

            'delivery' => fake()->randomFloat(2, 0, 500),
            'discount' => fake()->optional()->numberBetween(0, 20),

            'delivery_city' => fake()->city(),
            'delivery_address' => fake()->streetAddress(),
            'delivery_country' => fake()->countryCode(),
            'delivery_type' => fake()->numberBetween(0, 1),

            'locale' => fake()->languageCode(),
            'currency' => 'EUR',
            'name' => 'Order ' . fake()->word(),
            'description' => fake()->optional()->text(),

            'create_date' => $date,
            'update_date' => fake()->dateTimeBetween($date),

            'address_equal' => fake()->boolean(),
            'accept_pay' => fake()->boolean(80),
            'payment_euro' => fake()->boolean(),
        ];
    }

    public function lastMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'create_date' => now()->subMonth(),
        ]);
    }
}