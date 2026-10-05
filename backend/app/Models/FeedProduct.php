<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedProduct extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'image',
        'weight_kg',
        'price',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'weight_kg' => 'decimal:2',
            'price' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }
    public function receipts(): HasMany
    {
        return $this->hasMany(FeedReceipt::class);
    }
}
