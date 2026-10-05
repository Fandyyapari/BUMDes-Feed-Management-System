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
        ];
    }

    public function feedProduct(): BelongsTo
    {
        return $this->belongsTo(FeedProduct::class);
    }
}