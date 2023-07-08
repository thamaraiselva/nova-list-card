<?php

namespace NovaListCard\Tests\ListCardTests;

use NovaListCard\Tests\Fixtures\Models\ProductOrder;
use NovaListCard\Tests\Fixtures\Nova\Metrics\OrdersWithValueFormat;

class OrdersWithValueFormatTest extends ListCardTestCase
{

    protected function card(): ?\NovaListCard\ListCard
    {
        return $this->getCard(OrdersWithValueFormat::class);
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
            ->has(ProductOrder::factory()->count(83), 'products')->create();

        $response = $this->getJson(route('nova-list-card.data', $this->card()->uriKey()));

        $response->assertJsonStructure([
            [
                'title',
                'url',
                'value',
                'timestamp',
            ],
        ]);

        $response->assertJsonCount(2);

        $response->assertJsonPath('0.title', $order2->reference);
        $response->assertJsonPath('0.value', $order2->created_at->format('d/Y'));
        $response->assertJsonPath('0.timestamp', $order2->updated_at->format('m/Y'));
        $response->assertJsonPath('1.title', $order3->reference);
        $response->assertJsonPath('0.value', $order3->created_at->format('d/Y'));
        $response->assertJsonPath('0.timestamp', $order3->updated_at->format('m/Y'));
    }

}
