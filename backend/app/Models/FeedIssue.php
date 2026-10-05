<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedIssue extends Model
{
    protected $fillable = [
        'issue_number',
        'feed_product_id',
        'issued_at',
        'quantity',
        'reason',
        'recipient_name',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'issued_at' => 'date',
            'quantity' => 'integer',
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
