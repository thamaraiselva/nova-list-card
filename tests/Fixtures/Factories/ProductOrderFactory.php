<?php

namespace NovaListCard\Tests\Fixtures\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use NovaListCard\Tests\Fixtures\Models\Order;
use NovaListCard\Tests\Fixtures\Models\ProductOrder;

/**
 * @extends Factory<ProductOrder>
 */
class ProductOrderFactory extends Factory
{

    protected $model = ProductOrder::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'name'     => $this->faker->unique()->word(),
            'quantity' => rand(1, 10),
            'price'    => rand(10, 9999),
        ];
    }
}
