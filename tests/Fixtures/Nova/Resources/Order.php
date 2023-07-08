<?php

namespace NovaListCard\Tests\Fixtures\Nova\Resources;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

/**
 * @extends Resource<\NovaListCard\Tests\Fixtures\Models\Order>
 */
class Order extends Resource
{
    public static $model = \NovaListCard\Tests\Fixtures\Models\Order::class;

    public static $title = 'reference';

    public function fields(NovaRequest $request)
    {
        return [];
    }
}
