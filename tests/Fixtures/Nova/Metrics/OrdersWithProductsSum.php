<?php

namespace NovaListCard\Tests\Fixtures\Nova\Metrics;

use NovaListCard\ListCard;
use NovaListCard\Tests\Fixtures\Nova\Resources\Order;

class OrdersWithProductsSum extends ListCard
{

    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(Order::class)
            ->heading($this->name(), 'Unique products')
            ->withSum('products', 'quantity')
            ->orderBy('products_sum_quantity', 'foo')
            ->limit(4)
            ->value('products_sum_quantity');
    }
}
