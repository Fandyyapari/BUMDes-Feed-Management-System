<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedSale extends Model
{
    protected $fillable = [
        'sale_number',
        'feed_product_id',
        'sold_at',
        'customer_name',
        'customer_phone',
        'quantity',
        'unit_price',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'sold_at' => 'date',
            'quantity' => 'integer',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function feedProduct(): BelongsTo
    {
        return $this->belongsTo(FeedProduct::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function cancelledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function isCancelled(): bool
    {
        return $this->cancelled_at !== null;
    }
}