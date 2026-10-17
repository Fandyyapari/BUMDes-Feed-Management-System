<x-filament-panels::page>
    <x-filament::section>
        <x-slot name="heading">
            Stok Pakan Saat Ini
        </x-slot>

        <x-slot name="description">
            Rekap seluruh transaksi yang tidak dibatalkan.
            Semua jumlah menggunakan satuan kemasan.
        </x-slot>

        <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 16px; margin-bottom: 20px;">
            <x-filament::button
                type="button"
                wire:click="refreshReport"
                wire:loading.attr="disabled"
                wire:target="refreshReport"
            >
                <span wire:loading.remove wire:target="refreshReport">
                    Perbarui Laporan
                </span>

                <span wire:loading wire:target="refreshReport">
                    Memuat...
                </span>
            </x-filament::button>

            <p style="font-size: 14px;" aria-live="polite">
                Terakhir diperbarui: {{ $updatedAt }} WIB
            </p>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; min-width: 780px; border-collapse: collapse; text-align: left;">
                <caption style="text-align: left; padding-bottom: 12px;">
                    Produk aktif maupun tidak aktif tetap ditampilkan.
                </caption>

                <thead>
                    <tr style="border-bottom: 2px solid #9ca3af;">
                        <th scope="col" style="padding: 12px;">Produk Pakan</th>
                        <th scope="col" style="padding: 12px;">Kode Produk</th>
                        <th scope="col" style="padding: 12px;">Status Produk</th>
                        <th scope="col" style="padding: 12px; text-align: right;">Masuk</th>
                        <th scope="col" style="padding: 12px; text-align: right;">Keluar Nonpenjualan</th>
                        <th scope="col" style="padding: 12px; text-align: right;">Terjual</th>
                        <th scope="col" style="padding: 12px; text-align: right;">Sisa Stok</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($stockDetails as $product)
                        <tr
                            wire:key="stock-product-{{ $product['id'] }}"
                            style="border-bottom: 1px solid #9ca3af;"
                        >
                            <th scope="row" style="padding: 12px; font-weight: 500;">
                                {{ $product['name'] }}
                            </th>

                            <td style="padding: 12px;">
                                {{ $product['sku'] }}
                            </td>

                            <td style="padding: 12px;">
                                {{ $product['is_active'] ? 'Aktif' : 'Tidak Aktif' }}
                            </td>

                            <td style="padding: 12px; text-align: right;">
                                {{ number_format($product['received'], 0, ',', '.') }}
                            </td>

                            <td style="padding: 12px; text-align: right;">
                                {{ number_format($product['issued'], 0, ',', '.') }}
                            </td>

                            <td style="padding: 12px; text-align: right;">
                                {{ number_format($product['sold'], 0, ',', '.') }}
                            </td>

                            <td style="padding: 12px; text-align: right;">
                                <strong>
                                    {{ number_format($product['stock'], 0, ',', '.') }}
                                </strong>

                                @if ($product['stock'] === 0)
                                    <div style="font-size: 12px;">
                                        Stok habis
                                    </div>
                                @elseif ($product['stock'] < 0)
                                    <div style="font-size: 12px; color: #dc2626;">
                                        Periksa transaksi stok
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="padding: 24px; text-align: center;">
                                Belum ada produk pakan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 20px; font-size: 14px;">
            <p>
                <strong>Perhitungan:</strong>
                sisa stok = masuk − keluar nonpenjualan − terjual.
            </p>

            <p style="margin-top: 8px;">
                Pesanan yang menunggu atau sudah dikonfirmasi belum
                mengurangi maupun mencadangkan stok. Stok berkurang
                ketika pesanan diselesaikan menjadi penjualan.
            </p>
        </div>
    </x-filament::section>
</x-filament-panels::page>