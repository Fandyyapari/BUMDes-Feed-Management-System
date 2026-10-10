<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedOrder extends Model
{
    public const STATUS_WAITING = 'menunggu';
    public const STATUS_CONFIRMED = 'dikonfirmasi';
    public const STATUS_COMPLETED = 'selesai';
    public const STATUS_REJECTED = 'ditolak';
    public const STATUS_CANCELLED = 'dibatalkan';

    // Data pesanan akan diisi secara eksplisit melalui service.
    protected $guarded = ['*'];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'weight_kg' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'total_price' => 'decimal:2',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feedProduct(): BelongsTo
    {
        return $this->belongsTo(FeedProduct::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function feedSale(): BelongsTo
    {
        return $this->belongsTo(FeedSale::class);
    }

    public static function statusOptions(): array
    {
        return [
            self::STATUS_WAITING => 'Menunggu Konfirmasi',
            self::STATUS_CONFIRMED => 'Dikonfirmasi',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_CANCELLED => 'Dibatalkan',
        ];
    }

    public function statusLabel(): string
    {
        return self::statusOptions()[$this->status]
            ?? 'Status Tidak Dikenali';
    }
}