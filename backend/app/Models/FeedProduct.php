<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}