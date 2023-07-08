<?php

namespace NovaListCard\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use NovaListCard\Tests\Fixtures\Factories\OrderFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    public function products(): HasMany
    {
        return $this->hasMany(ProductOrder::class, 'order_id', 'id');
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }
}
