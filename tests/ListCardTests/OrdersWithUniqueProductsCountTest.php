<?php

namespace NovaListCard\Tests\ListCardTests;

use NovaListCard\Tests\Fixtures\Models\ProductOrder;
use NovaListCard\Tests\Fixtures\Nova\Metrics\OrdersWithUniqueProductsCount;

class OrdersWithUniqueProductsCountTest extends ListCardTestCase
{

    protected function card(): ?\NovaListCard\ListCard
    {
        return $this->getCard(OrdersWithUniqueProductsCount::class);
    }

    /** @test */
    public function success_response()
    {
        $order1 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(4), 'products')->create();
        $order2 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(5), 'products')->create();
        $order3 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(8), 'products')->create();
        $order4 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(7), 'products')->create();
        $order5 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(6), 'products')->create();

        $response = $this->getJson(route('nova-list-card.data', $this->card()->uriKey()));

        $response->assertJsonStructure([
            [
                'title',
                'url',
                'value',
            ],
        ]);

        $response->assertJsonCount(4);

        $response->assertJsonPath('0.title', $order3->reference);
        $response->assertJsonPath('3.title', $order2->reference);
    }

}
