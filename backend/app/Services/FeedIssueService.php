<?php

namespace App\Services;

use App\Models\FeedIssue;
use App\Models\FeedProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FeedIssueService
{
    public function create(array $data): FeedIssue
    {
        $validated = Validator::make($data, [
            'issue_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('feed_issues', 'issue_number'),
            ],
            'feed_product_id' => [
                'required',
                'integer',
                'exists:feed_products,id',
            ],
            'issued_at' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'reason' => [
                'required',
                Rule::in(['percontohan', 'rusak', 'kedaluwarsa', 'lainnya']),
            ],
            'recipient_name' => ['nullable', 'string', 'max:255'],
            'notes' => ['required', 'string', 'max:5000'],
        ], [
            'issue_number.unique' => 'Nomor pengeluaran sudah digunakan.',
            'feed_product_id.exists' => 'Produk pakan tidak ditemukan.',
            'quantity.integer' => 'Jumlah kemasan harus berupa bilangan bulat.',
            'quantity.min' => 'Jumlah keluar minimal 1 kemasan.',
            'reason.in' => 'Pilih alasan pengeluaran yang tersedia.',
            'notes.required' => 'Isi keterangan pengeluaran pakan.',
        ])->validate();

        return DB::transaction(function () use ($validated) {
            $product = FeedProduct::query()
                ->lockForUpdate()
                ->find($validated['feed_product_id']);

            if (! $product) {
                throw ValidationException::withMessages([
                    'feed_product_id' => 'Produk pakan tidak ditemukan.',
                ]);
            }

            $stok = $product->availableStock();

            if ((int) $validated['quantity'] > $stok) {
                throw ValidationException::withMessages([
                    'quantity' => "Stok tidak cukup. Sisa stok {$stok} kemasan.",
                ]);
            }

            return FeedIssue::create($validated);
        });
    }
}