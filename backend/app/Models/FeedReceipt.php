<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class FeedReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'feed_product_id',
        'received_at',
        'supplier_name',
        'batch_number',
        'quantity',
        'unit_cost',
        'expires_at',
        'notes',


    ];

    protected function casts(): array
    {
        return [
            'received_at' => 'date',
            'expires_at' => 'date',
            'quantity' => 'integer',
            'unit_cost' => 'decimal:2',
            'cancelled_at' => 'datetime',
        ];
    }

    public function feedProduct(): BelongsTo
    {
        return $this->belongsTo(FeedProduct::class);
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
