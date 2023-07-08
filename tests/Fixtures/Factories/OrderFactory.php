<?php

namespace NovaListCard\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NovaListCard\Tests\Fixtures\Models\Order;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{

    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'reference' => $this->faker->unique()->word(),
        ];
    }
}
