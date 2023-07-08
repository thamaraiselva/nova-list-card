<?php

namespace NovaListCard\Tests\ListCardTests;

use NovaListCard\Tests\Fixtures\Models\ProductOrder;
use NovaListCard\Tests\Fixtures\Nova\Metrics\OrdersWithProductsSum;

class OrdersWithProductsSumTest extends ListCardTestCase
{

    protected function card(): ?\NovaListCard\ListCard
    {
        return $this->getCard(OrdersWithProductsSum::class);
    }

    /** @test */
    public function success_response()
    {
        $order1 = \NovaListCard\Tests\Fixtures\Models\Order::factory()
            ->has(ProductOrder::factory()->count(4), 'products')->create();


        $response = $this->getJson(route('nova-list-card.data', $this->card()->uriKey()));

        $response->assertJsonStructure([
            [
                'title',
                'url',
                'value',
            ],
        ]);

        $response->assertJsonCount(1);

        $response->assertJsonPath('0.title', $order1->reference);
        $response->assertJsonPath('0.value', $order1->products->sum('quantity'));
    }

}
