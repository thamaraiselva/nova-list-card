<?php

namespace NovaListCard\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use NovaListCard\Tests\Fixtures\Factories\ProductOrderFactory;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders';

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    protected static function newFactory(): ProductOrderFactory
    {
        return ProductOrderFactory::new();
    }
}
