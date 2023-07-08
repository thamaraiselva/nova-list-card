<?php

namespace NovaListCard\Tests\Fixtures\Nova\Metrics;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use NovaListCard\ListCard;
use NovaListCard\Tests\Fixtures\Nova\Resources\Order;

class OrdersWithValueFormat extends ListCard
{

    public function __construct($component = null)
    {
        parent::__construct($component);

        $this->resource(Order::class)
            ->heading($this->name(), 'Created at')
            ->limit(2)
            ->queryCallback(fn (Builder $q) => $q->skip(1))
            ->timestamp('updated_at', 'm/Y')
            ->value('created_at', 'datetime', 'd/Y')
            ->classes('bg-yellow-300')
            ->noMaxHeight();
    }

    public function cacheFor(): int|Carbon
    {
        return Carbon::now()->addMinutes(2);
    }
}
