<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $product->name }} | JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #faf8f1;
            color: #12392d;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .container {
            width: min(960px, calc(100% - 32px));
            margin: auto;
        }

        header {
            background: white;
            border-bottom: 1px solid #e1e7dc;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            padding: 18px 0;
        }

        a {
            color: #075734;
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            padding: 32px 0;
        }

        .product-detail {
            display: grid;
            grid-template-columns: 1fr 1fr;
            overflow: hidden;
            background: white;
            border: 1px solid #e1e7dc;
            border-radius: 16px;
        }

        .product-image {
            display: block;
            width: 100%;
            height: 100%;
            max-height: 600px;
            object-fit: cover;
        }

        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 300px;
            padding: 24px;
            background: #edf1e7;
            color: #59685e;
            text-align: center;
        }

        .content {
            padding: 28px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 28px;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }

        h2 {
            margin: 24px 0 8px;
            font-size: 20px;
        }

        .weight {
            margin: 0;
            color: #5b6b62;
        }

        .price {
            margin: 12px 0;
            color: #075734;
            font-size: 28px;
            font-weight: bold;
        }

        .price small {
            font-size: 14px;
            font-weight: normal;
        }

        .stock {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 8px;
            background: #e7f3e8;
            color: #166534;
        }

        .stock.empty {
            background: #fff0df;
            color: #8a5418;
        }

        .description {
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .information {
            margin-top: 24px;
            border: 1px solid #dfe7dc;
            border-radius: 10px;
        }

        details {
            padding: 14px 16px;
        }

        details+details {
            border-top: 1px solid #dfe7dc;
        }

        summary {
            cursor: pointer;
            font-weight: bold;
        }

        details p {
            margin-bottom: 0;
            color: #5b6b62;
        }

        .note {
            margin-top: 20px;
            font-size: 14px;
            color: #5b6b62;
        }

        .button {
            display: inline-block;
            margin-top: 12px;
            padding: 12px 18px;
            border-radius: 9px;
            background: #086139;
            color: white;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
        }

        .button:hover {
            background: #064c2d;
        }

        a:focus-visible,
        summary:focus-visible {
            outline: 3px solid #bd821e;
            outline-offset: 4px;
        }

        @media (max-width: 700px) {
            .product-detail {
                grid-template-columns: 1fr;
            }

            .product-image {
                max-height: 360px;
            }

            .content {
                padding: 22px;
            }
        }
    </style>
</head>

<body>
    @php
        $stock =
            (int) ($product->active_receipts_sum_quantity ?? 0) -
            (int) ($product->active_issues_sum_quantity ?? 0) -
            (int) ($product->active_sales_sum_quantity ?? 0);
    @endphp

    <header>
        <div class="container header-content">
            <a href="{{ route('catalog.index') }}">
                ← Kembali ke Katalog
            </a>

            <a class="brand" href="{{ route('home') }}">
                JarakFeed
            </a>
        </div>
    </header>

    <main class="container">
        <article class="product-detail">
            <div>
                @if ($product->image)
                    <img class="product-image"
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image) }}"
                        alt="Foto {{ $product->name }}" width="600" height="600">
                @else
                    <div class="image-placeholder">
                        Foto pakan belum tersedia
                    </div>
                @endif
            </div>

            <div class="content">
                <h1>{{ $product->name }}</h1>

                <p class="weight">
                    Berat:
                    {{ number_format((float) $product->weight_kg, 2, ',', '.') }}
                    kg / kemasan
                </p>

                <p class="price">
                    Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                    <small>/ kemasan</small>
                </p>

                @if ($stock > 0)
                    <span class="stock">
                        Tersedia:
                        {{ number_format($stock, 0, ',', '.') }}
                        kemasan
                    </span>
                @else
                    <span class="stock empty">Stok habis</span>
                @endif

                <h2>Tentang Pakan</h2>

                <p class="description">{{ $product->description ?: 'Deskripsi produk belum tersedia.' }}</p>

                <div class="information">
                    <details>
                        <summary>Komposisi</summary>
                        <p class="description">
                            {{ $product->composition ?: 'Informasi komposisi belum tersedia. Silakan tanyakan kepada pengurus BUMDes.' }}
                        </p>
                    </details>

                    <details>
                        <summary>Cara Penggunaan</summary>
                        <p class="description">
                            {{ $product->usage_instructions ?: 'Panduan penggunaan belum tersedia. Silakan tanyakan kepada pengurus BUMDes.' }}
                        </p>
                    </details>

                    <details>
                        <summary>Cara Penyimpanan</summary>
                        <p class="description">
                            {{ $product->storage_instructions ?: 'Panduan penyimpanan belum tersedia. Silakan tanyakan kepada pengurus BUMDes.' }}
                        </p>
                    </details>
                </div>

                <p class="note">
                    Ketersediaan mengikuti catatan stok BUMDes.
                    Untuk pembelian, silakan hubungi pengurus BUMDes Desa Jarak.
                </p>

                <a class="button" href="{{ route('catalog.index') }}">
                    Lihat Pakan Lainnya
                </a>
            </div>
        </article>
    </main>
</body>

</html>
