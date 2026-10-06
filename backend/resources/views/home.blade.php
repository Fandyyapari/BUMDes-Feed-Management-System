<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pakan Fermentasi | BUMDes Desa Jarak</title>

    <style>
        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f9f6;
            color: #20352b;
            line-height: 1.6;
        }

        .container {
            width: min(1100px, calc(100% - 32px));
            margin: auto;
        }

        header {
            background: white;
            border-bottom: 1px solid #dfe7dc;
        }

        .navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px 0;
        }

        .brand {
            color: #246943;
            font-size: 21px;
            font-weight: bold;
            text-decoration: none;
        }

        nav {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
        }

        nav a {
            color: #20352b;
            text-decoration: none;
        }

        .button {
            display: inline-block;
            padding: 12px 20px;
            border-radius: 10px;
            background: #246943;
            color: white;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            background: #194e31;
        }

        a:focus-visible {
            outline: 3px solid #ba7615;
            outline-offset: 4px;
        }

        .hero {
            padding: 64px 0;
            background: #eaf3e7;
        }

        .eyebrow {
            color: #246943;
            font-weight: bold;
        }

        h1 {
            max-width: 760px;
            margin: 12px 0 20px;
            font-size: clamp(32px, 5vw, 50px);
            line-height: 1.2;
        }

        .hero p {
            max-width: 650px;
        }

        .hero .button {
            margin-top: 12px;
        }

        section {
            padding: 44px 0;
        }

        h2 {
            margin: 0 0 12px;
            font-size: 28px;
        }

        .muted {
            color: #59685e;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            margin-top: 24px;
        }

        .product-card {
            overflow: hidden;
            border: 1px solid #dfe7dc;
            border-radius: 16px;
            background: white;
        }

        .product-image,
        .image-placeholder {
            width: 100%;
            height: 220px;
            background: #eaf0e7;
            object-fit: cover;
        }

        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #59685e;
        }

        .product-content {
            padding: 24px;
        }

        h3 {
            margin: 0 0 8px;
            font-size: 21px;
            overflow-wrap: anywhere;
        }

        .price {
            color: #246943;
            font-size: 24px;
            font-weight: bold;
            margin: 12px 0;
        }

        .price small {
            color: #59685e;
            font-size: 14px;
            font-weight: normal;
        }

        .stock {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #e7f3e8;
            color: #215b35;
            font-size: 14px;
            font-weight: bold;
        }

        .stock.empty {
            background: #fff0df;
            color: #7b4b15;
        }

        .description {
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .notice {
            padding: 24px;
            background: white;
            border: 1px solid #dfe7dc;
            border-radius: 12px;
        }

        .about {
            background: #eef3eb;
        }

        .about p {
            max-width: 800px;
        }

        footer {
            padding: 24px 0;
            background: #203d2c;
            color: white;
        }

        footer p {
            margin: 0;
        }

        @media (max-width: 900px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .product-grid {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 40px 0;
            }

            nav {
                gap: 14px;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container navigation">
            <a class="brand" href="{{ route('home') }}">
                BUMDes Desa Jarak
            </a>

            <nav aria-label="Menu utama">
                <a href="#produk">Produk Pakan</a>
                <a href="#tentang">Tentang Kami</a>

                <a class="button" href="{{ url('/admin/login') }}">
                    Masuk Pengurus
                </a>
            </nav>
        </div>
    </header>

    <main>
        <div class="hero">
            <div class="container">
                <p class="eyebrow">Pakan Fermentasi Desa Jarak</p>

                <h1>Kenali pakannya, cek harga dan ketersediaannya.</h1>

                <p>
                    Temukan informasi pakan fermentasi yang dikelola
                    BUMDes Desa Jarak. Lihat pilihan kemasan,
                    deskripsi, dan harga sebelum membeli.
                </p>

                <a class="button" href="#produk">
                    Lihat Produk Pakan
                </a>
            </div>
        </div>

        <section id="produk" class="container">
            <h2>Pilihan Produk Pakan</h2>

            <p class="muted">
                Harga tercantum untuk setiap kemasan.
                Ketersediaan mengikuti catatan stok BUMDes.
            </p>

            @forelse ($products as $product)
                @if ($loop->first)
                    <div class="product-grid">
                @endif

                @php
                    $stock = (int) ($product->active_receipts_sum_quantity ?? 0)
                        - (int) ($product->active_issues_sum_quantity ?? 0)
                        - (int) ($product->active_sales_sum_quantity ?? 0);
                @endphp

                <article class="product-card">
                    @if ($product->image)
                        <img
                            class="product-image"
                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image) }}"
                            alt="Foto {{ $product->name }}"
                            loading="lazy"
                            width="600"
                            height="400"
                        >
                    @else
                        <div class="image-placeholder">
                            Foto pakan belum tersedia
                        </div>
                    @endif

                    <div class="product-content">
                        <h3>{{ $product->name }}</h3>

                        <p class="muted">
                            Berat kemasan:
                            {{ number_format((float) $product->weight_kg, 2, ',', '.') }}
                            kg
                        </p>

                        <p class="price">
                            Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                            <small>/ kemasan</small>
                        </p>

                        @if ($stock > 0)
                            <span class="stock">
                                Tersedia:
                                {{ number_format($stock, 0, ',', '.') }}
                                kemasan
                            </span>
                        @else
                            <span class="stock empty">
                                Stok belum tersedia
                            </span>
                        @endif

                        <p class="description">{{ $product->description ?: 'Informasi produk akan dilengkapi oleh pengurus BUMDes.' }}</p>
                    </div>
                </article>

                @if ($loop->last)
                    </div>
                @endif
            @empty
                <div class="notice">
                    Produk pakan belum tersedia di halaman ini.
                    Silakan hubungi pengurus BUMDes untuk informasi lebih lanjut.
                </div>
            @endforelse
        </section>

        <section id="tentang" class="about">
            <div class="container">
                <h2>Tentang Pakan Desa Jarak</h2>

                <p>
                    Pengembangan pakan fermentasi di Desa Jarak melibatkan
                    tim produksi, pengurus BUMDes, dan masyarakat.
                    BUMDes mengelola penerimaan pakan yang sudah diproduksi,
                    persediaan, serta penjualannya.
                </p>

                <p>
                    Website ini membantu masyarakat melihat informasi
                    produk dan membantu pengurus mencatat kegiatan usaha.
                    Untuk informasi pembelian dan penggunaan pakan,
                    silakan hubungi pengurus BUMDes Desa Jarak.
                </p>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; {{ date('Y') }} BUMDes Desa Jarak.</p>
            <p>Desa Jarak, Kecamatan Plosoklaten, Kabupaten Kediri.</p>
        </div>
    </footer>
</body>
</html>