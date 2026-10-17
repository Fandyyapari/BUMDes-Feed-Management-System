<?php

namespace App\Http\Controllers;

use App\Models\FeedSale;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SalesReportExportController extends Controller
{
    public function __invoke(Request $request): StreamedResponse
    {
        $user = $request->user();

        abort_unless(
            $user instanceof User
                && in_array($user->role, ['admin', 'petugas'], true),
            403
        );

        $validated = $request->validate([
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => [
                'required',
                'date_format:Y-m-d',
                'after_or_equal:start_date',
            ],
        ], [
            'start_date.required' => 'Tanggal mulai wajib diisi.',
            'start_date.date_format' => 'Tanggal mulai tidak valid.',
            'end_date.required' => 'Tanggal akhir wajib diisi.',
            'end_date.date_format' => 'Tanggal akhir tidak valid.',
            'end_date.after_or_equal' =>
                'Tanggal akhir tidak boleh sebelum tanggal mulai.',
        ]);

        $startDate = $validated['start_date'];
        $endDate = $validated['end_date'];

        $filename = "laporan-penjualan-{$startDate}-{$endDate}.csv";

        return response()->streamDownload(function () use (
            $startDate,
            $endDate
        ): void {
            $output = fopen('php://output', 'w');

            // Penanda UTF-8 agar teks terbaca dengan baik di Excel.
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'Tanggal Penjualan',
                'Nomor Penjualan',
                'Nama Pembeli',
                'Produk Pakan',
                'Jumlah Kemasan',
                'Harga per Kemasan (Rp)',
                'Total Penjualan (Rp)',
                'Metode Pembayaran',
            ], ';', '"', '');

            $sales = FeedSale::query()
                ->with('feedProduct')
                ->whereNull('cancelled_at')
                ->whereBetween('sold_at', [$startDate, $endDate])
                ->orderByDesc('sold_at')
                ->orderByDesc('id')
                ->lazy(500);

            try {
                foreach ($sales as $sale) {
                    fputcsv($output, [
                        $sale->sold_at->format('d/m/Y'),
                        $this->safeText($sale->sale_number),
                        $this->safeText($sale->customer_name),
                        $this->safeText(
                            $sale->feedProduct?->name
                                ?? 'Produk tidak tersedia'
                        ),
                        $sale->quantity,
                        str_replace('.', ',', (string) $sale->unit_price),
                        str_replace('.', ',', (string) $sale->total_price),
                        match ($sale->payment_method) {
                            'tunai' => 'Tunai',
                            'transfer' => 'Transfer Bank',
                            default => 'Tidak diketahui',
                        },
                    ], ';', '"', '');
                }
            } finally {
                fclose($output);
            }
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function safeText(?string $value): string
    {
        $text = trim((string) $value);

        // Hindari teks input pengguna dibaca sebagai rumus spreadsheet.
        if (preg_match('/^[\s\x00-\x1F]*[=+\-@]/u', $text)) {
            return "'" . $text;
        }

        return $text;
    }
}