<?php

namespace App\Services;

use App\Models\FeedOrder;
use App\Models\FeedProduct;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FeedOrderService
{
    public function create(User $actor, array $data): FeedOrder
    {
        $validated = Validator::make($data, [
            'feed_product_id' => ['required', 'integer', 'min:1'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'request_token' => ['required', 'uuid'],
            'customer_notes' => ['nullable', 'string', 'max:1000'],
        ], [
            'feed_product_id.required' => 'Pilih produk pakan.',
            'feed_product_id.integer' => 'Produk pakan tidak valid.',
            'quantity.required' => 'Jumlah pesanan wajib diisi.',
            'quantity.integer' => 'Jumlah harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah minimal 1 kemasan.',
            'quantity.max' => 'Jumlah maksimal 10.000 kemasan.',
            'request_token.required' => 'Muat ulang formulir pesanan.',
            'request_token.uuid' => 'Formulir tidak valid. Muat ulang halaman.',
            'customer_notes.max' => 'Catatan maksimal 1.000 karakter.',
        ])->validate();

        $productId = (int) $validated['feed_product_id'];
        $quantity = (int) $validated['quantity'];
        $token = strtolower($validated['request_token']);
        $notes = trim($validated['customer_notes'] ?? '') ?: null;

        return DB::transaction(function () use (
            $actor,
            $productId,
            $quantity,
            $token,
            $notes
        ) {
            // Kunci akun agar pengiriman ulang diproses bergantian.
            $customer = User::query()
                ->lockForUpdate()
                ->findOrFail($actor->getKey());

            if ($customer->role !== 'pelanggan') {
                throw new AuthorizationException(
                    'Hanya pelanggan yang dapat membuat pesanan.'
                );
            }

            // Jika form yang sama dikirim ulang, gunakan pesanan lama.
            $existing = FeedOrder::query()
                ->where('request_token', $token)
                ->first();

            if ($existing) {
                if (
                    (int) $existing->user_id !== (int) $customer->id
                    || (int) $existing->feed_product_id !== $productId
                    || (int) $existing->quantity !== $quantity
                    || $existing->customer_notes !== $notes
                ) {
                    throw ValidationException::withMessages([
                        'request_token' =>
                        'Formulir sudah digunakan. Buka formulir baru.',
                    ]);
                }

                return $existing;
            }

            if (
                trim((string) $customer->name) === ''
                || ! preg_match('/^628[0-9]{8,11}$/', (string) $customer->phone)
            ) {
                throw ValidationException::withMessages([
                    'customer_profile' =>
                    'Nama atau nomor HP akun belum lengkap. Hubungi pengurus.',
                ]);
            }

            // Kunci produk saat memeriksa stok dan harga.
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->find($productId);

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'feed_product_id' =>
                    'Produk pakan ini tidak tersedia untuk dipesan.',
                ]);
            }

            $stock = $product->availableStock();

            if ($quantity > $stock) {
                throw ValidationException::withMessages([
                    'quantity' =>
                    "Stok saat ini {$stock} kemasan. Kurangi jumlah pesanan.",
                ]);
            }

            // Hitung uang sebagai bilangan bulat dalam satuan sen.
            $price = (string) $product->price;

            if (! preg_match('/^\d{1,13}(?:\.\d{1,2})?$/', $price)) {
                throw ValidationException::withMessages([
                    'feed_product_id' =>
                    'Harga produk belum valid. Hubungi pengurus.',
                ]);
            }

            $parts = explode('.', $price);
            $unitPriceInCents = ((int) $parts[0] * 100)
                + (int) str_pad($parts[1] ?? '', 2, '0');

            // Sesuaikan batas total dengan kolom decimal(15, 2).
            $maximumInCents = 999999999999999;

            if ($unitPriceInCents > intdiv($maximumInCents, $quantity)) {
                throw ValidationException::withMessages([
                    'quantity' => 'Total pesanan terlalu besar.',
                ]);
            }

            $totalInCents = $unitPriceInCents * $quantity;

            $order = new FeedOrder();

            $order->order_number = 'PS-' . now()->format('Ymd')
                . '-' . Str::ulid();
            $order->request_token = $token;
            $order->user_id = $customer->id;
            $order->feed_product_id = $product->id;

            $order->customer_name = $customer->name;
            $order->customer_phone = $customer->phone;
            $order->customer_dusun = $customer->dusun;
            $order->customer_address = $customer->address;

            $order->product_name = $product->name;
            $order->weight_kg = $product->weight_kg;
            $order->quantity = $quantity;
            $order->unit_price = $this->decimalFromCents($unitPriceInCents);
            $order->total_price = $this->decimalFromCents($totalInCents);

            $order->status = FeedOrder::STATUS_WAITING;
            $order->customer_notes = $notes;
            $order->save();

            return $order;
        }, 3);
    }
    public function cancelByCustomer(
        FeedOrder $record,
        string $reason,
        User $actor
    ): FeedOrder {
        return DB::transaction(function () use ($record, $reason, $actor) {
            $customer = User::query()
                ->lockForUpdate()
                ->findOrFail($actor->getKey());

            if ($customer->role !== 'pelanggan') {
                throw new AuthorizationException(
                    'Hanya pelanggan yang dapat membatalkan pesanannya sendiri.'
                );
            }

            // Ambil hanya pesanan milik pelanggan yang sedang login.
            $order = FeedOrder::query()
                ->where('user_id', $customer->id)
                ->lockForUpdate()
                ->findOrFail($record->getKey());

            if (
                $order->status !== FeedOrder::STATUS_WAITING
                || $order->feed_sale_id !== null
            ) {
                throw ValidationException::withMessages([
                    'cancellation_reason' =>
                    'Hanya pesanan yang masih menunggu konfirmasi '
                        . 'yang dapat dibatalkan sendiri.',
                ]);
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
                    'Alasan maksimal 1.000 karakter.',
                ]
            )->validate();

            $order->status = FeedOrder::STATUS_CANCELLED;
            $order->cancelled_at = now();
            $order->cancellation_reason =
                $validated['cancellation_reason'];
            $order->save();

            return $order;
        }, 3);
    }
    private function decimalFromCents(int $amount): string
    {
        return intdiv($amount, 100) . '.'
            . str_pad((string) ($amount % 100), 2, '0', STR_PAD_LEFT);
    }
}
