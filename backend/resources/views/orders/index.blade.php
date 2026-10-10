<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya — JarakFeed</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: #faf8f1;
            color: #24382b;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a { color: #075734; }

        header {
            background: #fff;
            border-bottom: 1px solid #dedfd3;
        }

        .header-content {
            width: min(960px, calc(100% - 32px));
            margin: auto;
            padding: 16px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            width: min(760px, calc(100% - 32px));
            margin: 28px auto;
        }

        h1 { margin: 0 0 8px; font-size: 30px; }
        h2 { margin: 12px 0 8px; font-size: 20px; }
        p { margin: 0 0 12px; }

        .muted { color: #627065; }

        .card {
            margin-top: 20px;
            padding: 24px;
            background: #fff;
            border: 1px solid #dedfd3;
            border-radius: 16px;
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .order-number {
            margin: 0;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            background: #edf0ed;
            color: #435247;
            font-size: 13px;
            font-weight: bold;
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

        .product-name { overflow-wrap: anywhere; }

        .summary {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding-top: 16px;
            margin-top: 16px;
            border-top: 1px solid #dedfd3;
        }

        .total {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #075734;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 10px 18px;
            border: 1px solid #075734;
            border-radius: 10px;
            background: #075734;
            color: #fff;
            font-weight: bold;
            text-decoration: none;
        }

        .button:hover { background: #043d24; }

        .secondary {
            background: #fff;
            color: #075734;
        }

        .secondary:hover { background: #edf4eb; }

        .empty {
            text-align: center;
            padding: 36px 24px;
        }

        .pagination {
            margin-top: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        a:focus-visible {
            outline: 3px solid #bd891f;
            outline-offset: 3px;
        }

        footer {
            padding: 8px 16px 24px;
            color: #627065;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            h1 { font-size: 26px; }
            .card { padding: 18px; }
            .summary { align-items: stretch; flex-direction: column; }
            .summary .button { width: 100%; }
            .pagination { justify-content: center; }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>
            <a href="{{ route('customer.profile.edit') }}">Profil Saya</a>
        </div>
    </header>

    <main>
        <h1>Pesanan Saya</h1>
        <p class="muted">
            Lihat rincian dan status pesanan pakan yang sudah kamu kirim.
        </p>

        @forelse ($orders as $order)
            <article class="card">
                <div class="card-top">
                    <p class="order-number muted">
                        {{ $order->order_number }}
                    </p>

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
                </div>

                <h2 class="product-name">{{ $order->product_name }}</h2>

                <p>
                    {{ number_format($order->quantity, 0, ',', '.') }} kemasan
                    ·
                    {{ number_format((float) $order->weight_kg, 2, ',', '.') }}
                    kg per kemasan
                </p>

                <p class="muted">
                    Dipesan:
                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d/m/Y H:i') }}
                    WIB
                </p>

                <div class="summary">
                    <p class="total">
                        Rp {{ number_format((float) $order->total_price, 2, ',', '.') }}
                    </p>

                    <a
                        class="button"
                        href="{{ route('customer.orders.show', ['feedOrder' => $order->id]) }}"
                        aria-label="Lihat detail pesanan {{ $order->order_number }}"
                    >
                        Lihat Detail
                    </a>
                </div>
            </article>
        @empty
            <section class="card empty">
                <h2>Belum Ada Pesanan</h2>
                <p class="muted">
                    Pilih pakan yang tersedia untuk membuat pesanan pertama.
                </p>

                <a class="button" href="{{ route('catalog.index') }}">
                    Lihat Pakan
                </a>
            </section>
        @endforelse

        @if ($orders->hasPages())
            <nav class="pagination" aria-label="Halaman riwayat pesanan">
                @if ($orders->previousPageUrl())
                    <a
                        class="button secondary"
                        href="{{ $orders->previousPageUrl() }}"
                        rel="prev"
                    >
                        Sebelumnya
                    </a>
                @endif

                <span class="muted">
                    Halaman {{ $orders->currentPage() }}
                    dari {{ $orders->lastPage() }}
                </span>

                @if ($orders->nextPageUrl())
                    <a
                        class="button secondary"
                        href="{{ $orders->nextPageUrl() }}"
                        rel="next"
                    >
                        Berikutnya
                    </a>
                @endif
            </nav>
        @endif
    </main>

    <footer>BUMDes Desa Jarak · JarakFeed</footer>
</body>
</html>