<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan — JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #faf8f1;
            color: #24382b;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a {
            color: #075734;
        }

        header {
            background: #fff;
            border-bottom: 1px solid #dedfd3;
        }

        .header-content {
            width: min(960px, calc(100% - 32px));
            margin: auto;
            padding: 16px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            width: min(680px, calc(100% - 32px));
            margin: 28px auto;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        h2 {
            margin: 0 0 16px;
            font-size: 20px;
        }

        p {
            margin: 0 0 12px;
        }

        .muted {
            color: #627065;
        }

        .order-number {
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .card {
            margin-top: 20px;
            padding: 24px;
            background: #fff;
            border: 1px solid #dedfd3;
            border-radius: 16px;
        }

        .notice {
            margin-top: 20px;
            padding: 16px;
            background: #e9f5eb;
            border: 1px solid #acd0b3;
            border-radius: 10px;
            color: #18592d;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            margin-bottom: 12px;
            border-radius: 20px;
            background: #edf0ed;
            color: #435247;
            font-weight: bold;
            font-size: 14px;
        }

        .status-menunggu {
            background: #fff2cb;
            color: #72520b;
        }

        .status-dikonfirmasi {
            background: #e5efff;
            color: #224d83;
        }

        .status-selesai {
            background: #e9f5eb;
            color: #18592d;
        }

        .status-ditolak,
        .status-dibatalkan {
            background: #fff0f0;
            color: #8b2424;
        }

        .details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin: 0;
        }

        .details>div {
            min-width: 0;
        }

        dt {
            color: #627065;
            font-size: 13px;
        }

        dd {
            margin: 4px 0 0;
            overflow-wrap: anywhere;
        }

        .full {
            grid-column: 1 / -1;
        }

        .text {
            white-space: pre-line;
        }

        .total {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid #dedfd3;
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            font-size: 18px;
            font-weight: bold;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            min-height: 48px;
            padding: 12px 18px;
            background: #075734;
            border: 1px solid #075734;
            border-radius: 10px;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .button:hover {
            background: #043d24;
        }

        .secondary {
            background: #fff;
            color: #075734;
        }

        .secondary:hover {
            background: #edf4eb;
        }

        a:focus-visible {
            outline: 3px solid #bd891f;
            outline-offset: 3px;
        }

        footer {
            padding: 8px 16px 24px;
            text-align: center;
            color: #627065;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 26px;
            }

            .card {
                padding: 18px;
            }

            .details {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>
            <a href="{{ route('customer.orders.index') }}">Pesanan Saya</a>
        </div>
    </header>

    <main>
        <h1>Detail Pesanan</h1>
        <p class="muted order-number">{{ $order->order_number }}</p>

        @if (session('success'))
            <div class="notice" role="status">
                {{ session('success') }}
            </div>
        @endif

        <section class="card" aria-label="Status pesanan">
            <span @class([
                'status',
                'status-menunggu' => $order->status === 'menunggu',
                'status-dikonfirmasi' => $order->status === 'dikonfirmasi',
                'status-selesai' => $order->status === 'selesai',
                'status-ditolak' => $order->status === 'ditolak',
                'status-dibatalkan' => $order->status === 'dibatalkan',
            ])>
                {{ $order->statusLabel() }}
            </span>

            @switch($order->status)
                @case('menunggu')
                    <p>
                        Pesanan sudah diterima sistem dan menunggu
                        pemeriksaan pengurus.
                    </p>
                    <p class="muted">
                        Tunggu konfirmasi sebelum mengambil pakan
                        atau melakukan pembayaran.
                    </p>
                @break

                @case('dikonfirmasi')
                    <p>
                        Pesanan telah dikonfirmasi. Ikuti informasi
                        pengambilan dan pembayaran dari pengurus.
                    </p>
                @break

                @case('selesai')
                    <p>Pesanan telah diselesaikan oleh pengurus.</p>
                @break

                @case('ditolak')
                    <p>Pesanan belum dapat dipenuhi oleh pengurus.</p>
                @break

                @case('dibatalkan')
                    <p>Pesanan ini telah dibatalkan.</p>
                @break

                @default
                    <p>Hubungi pengurus untuk mengetahui status pesanan.</p>
            @endswitch
        </section>

        <section class="card">
            <h2>Rincian Pakan</h2>

            <dl class="details">
                <div class="full">
                    <dt>Produk Pakan</dt>
                    <dd>{{ $order->product_name }}</dd>
                </div>

                <div>
                    <dt>Berat per Kemasan</dt>
                    <dd>
                        {{ number_format((float) $order->weight_kg, 2, ',', '.') }}
                        kg
                    </dd>
                </div>

                <div>
                    <dt>Jumlah Pesanan</dt>
                    <dd>
                        {{ number_format($order->quantity, 0, ',', '.') }}
                        kemasan
                    </dd>
                </div>

                <div>
                    <dt>Harga per Kemasan</dt>
                    <dd>
                        Rp {{ number_format((float) $order->unit_price, 2, ',', '.') }}
                    </dd>
                </div>

                <div>
                    <dt>Tanggal Pesanan</dt>
                    <dd>
                        {{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                        WIB
                    </dd>
                </div>
            </dl>

            <div class="total">
                <span>Total Pesanan</span>
                <span>
                    Rp {{ number_format((float) $order->total_price, 2, ',', '.') }}
                </span>
            </div>

            <p class="muted" style="margin-top: 10px; font-size: 13px;">
                Total pesanan bukan bukti pembayaran.
            </p>
        </section>

        <section class="card">
            <h2>Data Pemesan</h2>

            <dl class="details">
                <div>
                    <dt>Nama</dt>
                    <dd>{{ $order->customer_name }}</dd>
                </div>

                <div>
                    <dt>Nomor HP</dt>
                    <dd>{{ $order->customer_phone }}</dd>
                </div>

                <div>
                    <dt>Dusun</dt>
                    <dd>{{ $order->customer_dusun ?: 'Tidak dicantumkan' }}</dd>
                </div>

                <div class="full">
                    <dt>Alamat</dt>
                    <dd class="text">{{ $order->customer_address ?: 'Tidak dicantumkan' }}</dd>
                </div>

                <div class="full">
                    <dt>Catatan Pemesan</dt>
                    <dd class="text">{{ $order->customer_notes ?: 'Tidak ada catatan' }}</dd>
                </div>
            </dl>
        </section>

        @if ($order->admin_notes || $order->cancellation_reason)
            <section class="card">
                <h2>Informasi Pesanan</h2>

                @if ($order->admin_notes)
                    <p class="text">{{ $order->admin_notes }}</p>
                @endif

                @if ($order->cancellation_reason)
                    <p><strong>Alasan Pembatalan</strong></p>
                    <p class="text">{{ $order->cancellation_reason }}</p>
                @endif
            </section>
        @endif

        @if ($order->status === \App\Models\FeedOrder::STATUS_WAITING && $order->feed_sale_id === null)
            <section class="card" aria-labelledby="cancel-heading">
                <h2 id="cancel-heading">Batalkan Pesanan</h2>

                <p class="muted" id="cancel-help">
                    Salah memilih jumlah atau berubah pikiran?
                    Kamu bisa membatalkan sebelum pengurus mengonfirmasi.
                </p>

                <form method="POST"
                    action="{{ route('customer.orders.cancel', [
                        'feedOrder' => $order->id,
                    ]) }}"
                    onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                    @csrf

                    <label for="cancellation_reason">
                        <strong>Alasan Pembatalan</strong>
                    </label>

                    <textarea id="cancellation_reason" name="cancellation_reason" rows="3" minlength="5" maxlength="1000" required
                        aria-describedby="cancel-help cancel-error" @error('cancellation_reason') aria-invalid="true" @enderror
                        placeholder="Contoh: Salah memasukkan jumlah kemasan."
                        style="display: block; width: 100%; box-sizing: border-box;
                    margin-top: 8px; padding: 12px; border: 1px solid #627065;
                    border-radius: 10px; font: inherit; resize: vertical;">{{ old('cancellation_reason') }}</textarea>

                    <div id="cancel-error" role="alert" style="color: #8b2424;">
                        @error('cancellation_reason')
                            <p>{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="button"
                        style="margin-top: 12px; background: #8b2424;
                    border-color: #8b2424; font: inherit; font-weight: bold;
                    cursor: pointer;">
                        Batalkan Pesanan
                    </button>
                </form>
            </section>
        @endif

        <div class="actions">
            <a class="button" href="{{ route('customer.orders.index') }}">
                Pesanan Saya
            </a>

            <a class="button secondary" href="{{ route('catalog.index') }}">
                Lihat Pakan
            </a>
        </div>
    </main>

    <footer>BUMDes Desa Jarak · JarakFeed</footer>
</body>

</html>
