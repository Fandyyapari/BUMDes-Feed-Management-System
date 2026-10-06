<?php

namespace App\Services;

use App\Models\FeedProduct;
use App\Models\FeedSale;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class FeedSaleService
{
    public function create(array $data, User $actor): FeedSale
    {
        $actor = $actor->fresh();

        if (! $actor) {
            throw ValidationException::withMessages([
                'customer_name' => 'Akun pencatat tidak ditemukan.',
            ]);
        }

        $data['customer_name'] = trim(
            (string) ($data['customer_name'] ?? '')
        );

        $validated = Validator::make($data, [
            'feed_product_id' => [
                'required',
                'integer',
                'exists:feed_products,id',
            ],
            'sold_at' => ['required', 'date_format:Y-m-d'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['nullable', 'string', 'max:30'],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:1000000',
            ],
            'unit_price' => [
                'required',
                'integer',
                'min:1',
                'max:1000000000',
            ],
            'payment_method' => ['required', 'in:tunai,transfer'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ])->validate();

        return DB::transaction(function () use ($validated, $actor) {
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->findOrFail($validated['feed_product_id']);

            if (! $product->is_active) {
                throw ValidationException::withMessages([
                    'feed_product_id' => 'Produk pakan sudah tidak aktif.',
                ]);
            }

            $quantity = (int) $validated['quantity'];
            $unitPrice = (int) $validated['unit_price'];
            $stock = $product->availableStock();

            if ($quantity > $stock) {
                throw ValidationException::withMessages([
                    'quantity' =>
                        "Stok hanya tersedia {$stock} kemasan.",
                ]);
            }

            $totalPrice = $quantity * $unitPrice;

            if ($totalPrice > 9999999999999) {
                throw ValidationException::withMessages([
                    'unit_price' =>
                        'Total penjualan melebihi batas penyimpanan.',
                ]);
            }

            $sale = new FeedSale();

            $sale->fill($validated);

            $sale->sale_number =
                'PJ-'
                . now('Asia/Jakarta')->format('Ymd')
                . '-'
                . Str::ulid();

            $sale->total_price = $totalPrice;
            $sale->created_by = $actor->id;
            $sale->save();

            return $sale;
        });
    }
}