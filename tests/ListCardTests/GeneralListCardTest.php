<?php

namespace NovaListCard\Tests\ListCardTests;

use NovaListCard\Tests\Fixtures\Nova\Metrics\OrdersWithProductsSum;

class GeneralListCardTest extends ListCardTestCase
{
    /** @test */
    public function error_if_wrong_uri_key()
    {
        $response = $this->getJson(route('nova-list-card.data', 'foo-bar'));

        $response->assertNotFound();
    }

    /** @test */
    public function correct_default_component_name()
    {
        $this->assertEquals('nova-list-card', $this->getCard(OrdersWithProductsSum::class)->component());
    }

    /** @test */
    public function component_return_correct_json()
    {
        $card = $this->getCard(OrdersWithProductsSum::class);
        $data = $card->jsonSerialize();

        $this->assertIsArray($data);
        $this->assertEquals($card->uriKey(), $data['uriKey']);
        $this->assertEquals('orders orders-with-products-sum', $data['classes']);
        $this->assertEquals('Orders With Products Sum', $data['heading']['left']);
        $this->assertEquals('Unique products', $data['heading']['right']);
        $this->assertFalse($data['noMaxHeight']);
        $this->assertArrayHasKey('url', $data);
    }
}
