<?php

namespace App\Services;

use App\Models\FeedIssue;
use App\Models\FeedOrder;
use App\Models\FeedProduct;
use App\Models\FeedReceipt;
use App\Models\FeedSale;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FeedCancellationService
{
    public function cancel(
        FeedReceipt|FeedIssue|FeedSale $record,
        string $reason,
        User $actor
    ): FeedReceipt|FeedIssue|FeedSale {
        $user = $actor->fresh();

        if (
            ! $user
            || ! in_array($user->role, ['admin', 'petugas'], true)
            || ! $user->can_cancel_stock
        ) {
            throw new AuthorizationException(
                'Akun ini tidak memiliki izin membatalkan transaksi stok.'
            );
        }

        $validated = Validator::make(
            ['cancellation_reason' => trim($reason)],
            [
                'cancellation_reason' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'cancellation_reason.required' =>
                'Alasan pembatalan wajib diisi.',
                'cancellation_reason.min' =>
                'Alasan minimal 5 karakter.',
                'cancellation_reason.max' =>
                'Alasan maksimal 1000 karakter.',
            ]
        )->validate();

        $original = $record->newQuery()
            ->findOrFail($record->getKey());

        return DB::transaction(function () use (
            $original,
            $validated,
            $user
        ) {
            // Kunci produk terlebih dahulu.
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->findOrFail($original->feed_product_id);

            // Jika penjualan berasal dari pesanan, kunci pesanannya.
            // Urutannya sama dengan proses penyelesaian pesanan:
            // produk, pesanan, lalu penjualan.
            $order = null;

            if ($original instanceof FeedSale) {
                $order = FeedOrder::query()
                    ->where('feed_sale_id', $original->getKey())
                    ->lockForUpdate()
                    ->first();
            }

            $transaction = $original->newQuery()
                ->lockForUpdate()
                ->findOrFail($original->getKey());

            if (
                (int) $transaction->feed_product_id
                !== (int) $product->id
            ) {
                throw ValidationException::withMessages([
                    'cancellation_reason' =>
                    'Data produk berubah. Muat ulang halaman.',
                ]);
            }

            if ($transaction->isCancelled()) {
                throw ValidationException::withMessages([
                    'cancellation_reason' =>
                    'Transaksi ini sudah dibatalkan.',
                ]);
            }

            // Periksa kesesuaian pesanan dan penjualan.
            if ($order !== null) {
                if (
                    (int) $order->feed_product_id !== (int) $product->id
                    || (int) $order->feed_sale_id
                    !== (int) $transaction->getKey()
                    || $order->status !== FeedOrder::STATUS_COMPLETED
                ) {
                    throw ValidationException::withMessages([
                        'cancellation_reason' =>
                        'Data pesanan tidak sesuai dengan penjualan. '
                            . 'Periksa pesanan sebelum membatalkan.',
                    ]);
                }
            }

            // Pembatalan pakan masuk tidak boleh membuat stok negatif.
            if ($transaction instanceof FeedReceipt) {
                $stock = $product->availableStock();
                $quantity = (int) $transaction->quantity;

                if ($stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cancellation_reason' =>
                        "Pembatalan ditolak. Stok tersisa {$stock} kemasan, "
                            . "sedangkan transaksi ini berjumlah {$quantity} kemasan.",
                    ]);
                }
            }

            $cancelledAt = now();

            // Simpan riwayat pembatalan transaksi.
            $transaction->cancelled_at = $cancelledAt;
            $transaction->cancellation_reason =
                $validated['cancellation_reason'];
            $transaction->cancelled_by = $user->id;
            $transaction->save();

            // Perbarui pesanan yang terhubung dengan penjualan.
            if ($order !== null) {
                $order->status = FeedOrder::STATUS_CANCELLED;
                $order->cancelled_at = $cancelledAt;
                $order->cancellation_reason =
                    $validated['cancellation_reason'];
                $order->save();
            }

            return $transaction;
        }, 3);
    }
}
