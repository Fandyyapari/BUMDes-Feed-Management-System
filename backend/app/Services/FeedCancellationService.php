<?php

namespace App\Services;

use App\Models\FeedIssue;
use App\Models\FeedProduct;
use App\Models\FeedReceipt;
use App\Models\User;
use App\Models\FeedSale;
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
        // Periksa izin akun dari database.
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

        // Alasan harus diisi dan tidak boleh hanya berupa spasi.
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
                'cancellation_reason.required' => 'Alasan pembatalan wajib diisi.',
                'cancellation_reason.min' => 'Alasan minimal 5 karakter.',
                'cancellation_reason.max' => 'Alasan maksimal 1000 karakter.',
            ]
        )->validate();

        // Ambil transaksi asli dari database.
        $original = $record->newQuery()
            ->findOrFail($record->getKey());

        return DB::transaction(function () use (
            $original,
            $validated,
            $user
        ) {
            // Kunci produk terlebih dahulu, seperti proses Pakan Keluar.
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->findOrFail($original->feed_product_id);

            // Ambil ulang dan kunci transaksi yang akan dibatalkan.
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

            // Membatalkan Pakan Masuk akan mengurangi stok.
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

            // Simpan pembatalan tanpa menghapus transaksi.
            $transaction->cancelled_at = now();
            $transaction->cancellation_reason =
                $validated['cancellation_reason'];
            $transaction->cancelled_by = $user->id;
            $transaction->save();

            return $transaction;
        });
    }
}
