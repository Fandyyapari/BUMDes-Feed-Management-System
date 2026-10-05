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
    public function issues(): HasMany
    {
        return $this->hasMany(FeedIssue::class);
    }

    public function availableStock(): int
    {
        $totalMasuk = (int) $this->activeReceipts()->sum('quantity');
        $totalKeluar = (int) $this->activeIssues()->sum('quantity');

        return $totalMasuk - $totalKeluar;
    }

    public function activeReceipts(): HasMany
    {
        return $this->receipts()->whereNull('cancelled_at');
    }

    public function activeIssues(): HasMany
    {
        return $this->issues()->whereNull('cancelled_at');
    }
}
