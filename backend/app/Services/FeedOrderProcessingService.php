<?php

namespace App\Services;

use App\Models\FeedOrder;
use App\Models\FeedProduct;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class FeedOrderProcessingService
{
    public function confirm(
        FeedOrder $record,
        string $notes,
        User $actor
    ): FeedOrder {
        $validated = Validator::make(
            ['admin_notes' => trim($notes)],
            [
                'admin_notes' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'admin_notes.required' =>
                'Informasi pengambilan wajib diisi.',
                'admin_notes.min' =>
                'Informasi minimal 5 karakter.',
                'admin_notes.max' =>
                'Informasi maksimal 1000 karakter.',
            ]
        )->validate();

        return DB::transaction(function () use (
            $record,
            $validated,
            $actor
        ) {
            $user = $actor->fresh();

            if (
                ! $user
                || ! in_array(
                    $user->role,
                    ['admin', 'petugas'],
                    true
                )
            ) {
                throw new AuthorizationException(
                    'Akun ini tidak boleh memproses pesanan.'
                );
            }

            $original = FeedOrder::query()
                ->findOrFail($record->getKey());

            // Kunci produk sebelum mengunci pesanan.
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->findOrFail($original->feed_product_id);

            $order = FeedOrder::query()
                ->lockForUpdate()
                ->findOrFail($original->id);

            if (
                (int) $order->feed_product_id
                !== (int) $product->id
            ) {
                throw ValidationException::withMessages([
                    'admin_notes' =>
                    'Data produk berubah. Muat ulang halaman.',
                ]);
            }

            if (
                $order->status !== FeedOrder::STATUS_WAITING
                || $order->feed_sale_id !== null
            ) {
                throw ValidationException::withMessages([
                    'admin_notes' =>
                    'Pesanan sudah diproses. Muat ulang halaman.',
                ]);
            }

            if (! $product->is_active) {
                throw ValidationException::withMessages([
                    'admin_notes' =>
                    'Produk sudah tidak aktif.',
                ]);
            }

            $stock = $product->availableStock();

            if ($stock < $order->quantity) {
                throw ValidationException::withMessages([
                    'admin_notes' =>
                    "Stok hanya {$stock} kemasan. "
                        . 'Stok belum cukup untuk pesanan ini.',
                ]);
            }

            $order->status = FeedOrder::STATUS_CONFIRMED;
            $order->admin_notes = $validated['admin_notes'];
            $order->processed_by = $user->id;
            $order->processed_at = now();
            $order->save();

            return $order;
        }, 3);
    }
    public function reject(
        FeedOrder $record,
        string $reason,
        User $actor
    ): FeedOrder {
        $validated = Validator::make(
            ['admin_notes' => trim($reason)],
            [
                'admin_notes' => [
                    'required',
                    'string',
                    'min:5',
                    'max:1000',
                ],
            ],
            [
                'admin_notes.required' => 'Alasan penolakan wajib diisi.',
                'admin_notes.min' => 'Alasan minimal 5 karakter.',
                'admin_notes.max' => 'Alasan maksimal 1000 karakter.',
            ]
        )->validate();

        return DB::transaction(function () use (
            $record,
            $validated,
            $actor
        ) {
            $user = $actor->fresh();

            if (
                ! $user
                || ! in_array($user->role, ['admin', 'petugas'], true)
            ) {
                throw new AuthorizationException(
                    'Akun ini tidak boleh memproses pesanan.'
                );
            }

            $order = FeedOrder::query()
                ->lockForUpdate()
                ->findOrFail($record->getKey());

            if (
                $order->status !== FeedOrder::STATUS_WAITING
                || $order->feed_sale_id !== null
            ) {
                throw ValidationException::withMessages([
                    'admin_notes' =>
                    'Hanya pesanan yang menunggu konfirmasi '
                        . 'yang dapat ditolak.',
                ]);
            }

            $order->status = FeedOrder::STATUS_REJECTED;
            $order->admin_notes = $validated['admin_notes'];
            $order->processed_by = $user->id;
            $order->processed_at = now();
            $order->save();

            return $order;
        }, 3);
    }
    public function complete(
        FeedOrder $record,
        string $paymentMethod,
        User $actor
    ): FeedOrder {
        $validated = Validator::make(
            ['payment_method' => $paymentMethod],
            [
                'payment_method' => [
                    'required',
                    'in:tunai,transfer',
                ],
            ],
            [
                'payment_method.required' =>
                'Metode pembayaran wajib dipilih.',
                'payment_method.in' =>
                'Metode pembayaran tidak valid.',
            ]
        )->validate();

        return DB::transaction(function () use (
            $record,
            $validated,
            $actor
        ) {
            $user = $actor->fresh();

            if (
                ! $user
                || ! in_array($user->role, ['admin', 'petugas'], true)
            ) {
                throw new AuthorizationException(
                    'Akun ini tidak boleh menyelesaikan pesanan.'
                );
            }

            $original = FeedOrder::query()
                ->findOrFail($record->getKey());

            $product = FeedProduct::query()
                ->lockForUpdate()
                ->findOrFail($original->feed_product_id);

            $order = FeedOrder::query()
                ->lockForUpdate()
                ->findOrFail($original->id);

            if (
                (int) $order->feed_product_id
                !== (int) $product->id
            ) {
                throw ValidationException::withMessages([
                    'payment_method' =>
                    'Data produk berubah. Muat ulang halaman.',
                ]);
            }

            if (
                $order->status !== FeedOrder::STATUS_CONFIRMED
                || $order->feed_sale_id !== null
            ) {
                throw ValidationException::withMessages([
                    'payment_method' =>
                    'Pesanan harus berstatus Dikonfirmasi '
                        . 'dan belum memiliki transaksi penjualan.',
                ]);
            }

            // Fungsi penjualan saat ini memakai rupiah bulat.
            $price = (string) $order->unit_price;

            if (
                ! preg_match('/^[1-9]\d{0,9}(?:\.00)?$/', $price)
            ) {
                throw ValidationException::withMessages([
                    'payment_method' =>
                    'Harga pesanan harus berupa rupiah bulat '
                        . 'dan lebih dari nol.',
                ]);
            }

            $unitPrice = (int) $price;
            $quantity = (int) $order->quantity;

            if (
                $unitPrice > 1000000000
                || $quantity < 1
                || $quantity > 1000000
            ) {
                throw ValidationException::withMessages([
                    'payment_method' =>
                    'Harga atau jumlah melebihi batas penjualan.',
                ]);
            }

            $expectedTotal = $quantity * $unitPrice;

            if (
                (string) $order->total_price
                !== number_format($expectedTotal, 2, '.', '')
            ) {
                throw ValidationException::withMessages([
                    'payment_method' =>
                    'Total harga pesanan tidak sesuai. '
                        . 'Periksa data sebelum melanjutkan.',
                ]);
            }

            // Catat penjualan menggunakan harga saat pemesanan.
            $sale = app(FeedSaleService::class)->create([
                'feed_product_id' => $product->id,
                'sold_at' => now('Asia/Jakarta')->format('Y-m-d'),
                'customer_name' => $order->customer_name,
                'customer_phone' => $order->customer_phone,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'payment_method' => $validated['payment_method'],
                'notes' => 'Penjualan dari pesanan '
                    . $order->order_number,
            ], $user);

            $order->feed_sale_id = $sale->id;
            $order->status = FeedOrder::STATUS_COMPLETED;
            $order->completed_at = now();
            $order->processed_by = $user->id;
            $order->save();

            return $order;
        }, 3);
    }
}
