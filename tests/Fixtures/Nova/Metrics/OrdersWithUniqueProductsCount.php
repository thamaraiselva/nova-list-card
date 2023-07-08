<?php

namespace NovaListCard\Tests\Fixtures\Nova\Metrics;

use NovaListCard\ListCard;
use NovaListCard\Tests\Fixtures\Nova\Resources\Order;

class OrdersWithUniqueProductsCount extends ListCard
{

    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(Order::class)
            ->heading($this->name(), 'Unique products')
            ->withCount('products')
            ->orderBy('products_count', 'foo')
            ->limit(4)
            ->value('products_count');
    }
}
