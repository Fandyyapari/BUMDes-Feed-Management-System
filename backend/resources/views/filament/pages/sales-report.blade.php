<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Periode Laporan
        </x-slot>

        <x-slot name="description">
            Pilih tanggal mulai dan tanggal akhir penjualan.
        </x-slot>

        <form wire:submit="applyFilters">
            <div style="display: flex; flex-wrap: wrap; gap: 16px;">
                <div style="flex: 1 1 220px; min-width: 0;">
                    <label for="startDate" style="display: block; margin-bottom: 8px;">
                        Tanggal Mulai
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input id="startDate" type="date" wire:model="startDate" required />
                    </x-filament::input.wrapper>

                    @error('startDate')
                        <p role="alert" style="color: #dc2626; margin-top: 6px;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div style="flex: 1 1 220px; min-width: 0;">
                    <label for="endDate" style="display: block; margin-bottom: 8px;">
                        Tanggal Akhir
                    </label>

                    <x-filament::input.wrapper>
                        <x-filament::input id="endDate" type="date" wire:model="endDate" required />
                    </x-filament::input.wrapper>

                    @error('endDate')
                        <p role="alert" style="color: #dc2626; margin-top: 6px;">
                            {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>

            <div style="margin-top: 16px;">
                <x-filament::button type="submit" wire:loading.attr="disabled" wire:target="applyFilters">
                    Tampilkan Laporan
                </x-filament::button>
            </div>
        </form>
    </x-filament::section>
    @if ($reportReady)
        <x-filament::section>
            <x-slot name="heading">
                Ringkasan Penjualan
            </x-slot>

            <x-slot name="description">
                Periode
                {{ \Carbon\Carbon::parse($reportStartDate)->format('d/m/Y') }}
                sampai
                {{ \Carbon\Carbon::parse($reportEndDate)->format('d/m/Y') }}
            </x-slot>

            <div style="display: flex; flex-wrap: wrap; gap: 24px;">
                <div style="flex: 1 1 180px; min-width: 0;">
                    <p>Jumlah Transaksi</p>
                    <p style="font-size: 26px; font-weight: 700;">
                        {{ number_format($totalTransactions, 0, ',', '.') }}
                    </p>
                    <small>Transaksi yang tidak dibatalkan</small>
                </div>

                <div style="flex: 1 1 180px; min-width: 0;">
                    <p>Kemasan Terjual</p>
                    <p style="font-size: 26px; font-weight: 700;">
                        {{ number_format($totalPackages, 0, ',', '.') }}
                    </p>
                    <small>Total kemasan semua produk</small>
                </div>

                <div style="flex: 1 1 220px; min-width: 0;">
                    <p>Total Penjualan</p>
                    <p style="font-size: 26px; font-weight: 700; overflow-wrap: anywhere;">
                        Rp {{ number_format((float) $totalRevenue, 2, ',', '.') }}
                    </p>
                    <small>Belum dikurangi biaya usaha</small>
                </div>
            </div>

            @if ($totalTransactions === 0)
                <p style="margin-top: 20px;">
                    Tidak ada penjualan aktif pada periode ini.
                </p>
            @endif
        </x-filament::section>
    @endif

    @if ($reportReady)
        <x-filament::section>
            <x-slot name="heading">
                Rincian Penjualan
            </x-slot>

            <x-slot name="description">
                Menampilkan {{ count($salesDetails) }} dari
                {{ number_format($totalTransactions, 0, ',', '.') }}
                transaksi aktif pada periode laporan.
                Maksimal 50 transaksi terbaru.
            </x-slot>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 2px solid #d1d5db;">
                            <th scope="col" style="padding: 12px;">Tanggal</th>
                            <th scope="col" style="padding: 12px;">Nomor Penjualan</th>
                            <th scope="col" style="padding: 12px;">Pembeli</th>
                            <th scope="col" style="padding: 12px;">Produk Pakan</th>
                            <th scope="col" style="padding: 12px; text-align: right;">
                                Kemasan
                            </th>
                            <th scope="col" style="padding: 12px;">Pembayaran</th>
                            <th scope="col" style="padding: 12px; text-align: right;">
                                Total
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($salesDetails as $sale)
                            <tr wire:key="report-sale-{{ $sale['id'] }}" style="border-bottom: 1px solid #d1d5db;">
                                <td style="padding: 12px; white-space: nowrap;">
                                    {{ $sale['sold_at'] }}
                                </td>

                                <td style="padding: 12px; overflow-wrap: anywhere;">
                                    {{ $sale['sale_number'] }}
                                </td>

                                <td style="padding: 12px;">
                                    {{ $sale['customer_name'] }}
                                </td>

                                <td style="padding: 12px;">
                                    {{ $sale['product_name'] }}
                                </td>

                                <td style="padding: 12px; text-align: right;">
                                    {{ number_format($sale['quantity'], 0, ',', '.') }}
                                </td>

                                <td style="padding: 12px;">
                                    {{ $sale['payment_method'] }}
                                </td>

                                <td style="padding: 12px; text-align: right; white-space: nowrap;">
                                    Rp {{ number_format((float) $sale['total_price'], 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 24px; text-align: center;">
                                    Tidak ada penjualan aktif pada periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($totalTransactions > 50)
                <p style="margin-top: 16px;">
                    Ringkasan di atas menghitung seluruh transaksi aktif.
                    Persempit rentang tanggal untuk melihat rincian transaksi lainnya.
                </p>
            @endif
        </x-filament::section>
    @endif

    @if ($reportReady)
        <div>
            <x-filament::button tag="a" :href="route('reports.sales.export', [
                'start_date' => $reportStartDate,
                'end_date' => $reportEndDate,
            ])" color="success">
                Unduh Laporan CSV
            </x-filament::button>

            <p style="margin-top: 8px; font-size: 14px;">
                Mengunduh seluruh penjualan aktif pada periode laporan.
                File dapat dibuka di Excel.
            </p>
        </div>
    @endif
</x-filament-panels::page>
