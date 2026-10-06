<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pilih Pakan | JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #faf8f1;
            color: #12392d;
            font-family: Arial, sans-serif;
            line-height: 1.5;
        }

        a {
            color: inherit;
        }

        .container {
            width: min(1040px, calc(100% - 32px));
            margin: auto;
        }

        header {
            background: white;
            border-bottom: 1px solid #e3e8df;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 0;
        }

        .brand {
            text-decoration: none;
        }

        .brand strong {
            display: block;
            font-size: 25px;
            color: #075734;
        }

        .brand small {
            display: block;
            font-size: 12px;
        }

        .back-link {
            font-size: 14px;
            font-weight: bold;
        }

        main {
            padding: 32px 0 48px;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .subtitle {
            margin: 0 0 24px;
            color: #5b6b62;
        }

        .search-form {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }

        .search-field {
            flex: 1;
            min-width: 0;
        }

        .search-field label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .search-field input {
            width: 100%;
            min-height: 48px;
            padding: 12px 14px;
            border: 1px solid #cbd5cb;
            border-radius: 10px;
            background: white;
            font: inherit;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 46px;
            padding: 10px 16px;
            border: 0;
            border-radius: 9px;
            background: #086139;
            color: white;
            font: inherit;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background: #064c2d;
        }

        .search-button {
            align-self: flex-end;
            min-height: 48px;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible {
            outline: 3px solid #bd821e;
            outline-offset: 3px;
        }

        .error {
            color: #a12b22;
            margin: 8px 0;
        }

        .search-result {
            margin-bottom: 24px;
            overflow-wrap: anywhere;
        }

        .search-result a {
            margin-left: 8px;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .product-card {
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: white;
            border: 1px solid #e1e7dc;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgb(20 50 30 / 4%);
        }

        .product-image,
        .image-placeholder {
            width: 100%;
            aspect-ratio: 4 / 3;
            object-fit: cover;
            background: #edf1e7;
        }

        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            text-align: center;
            color: #59685e;
        }

        .product-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            padding: 16px;
        }

        .product-content h2 {
            margin: 0 0 6px;
            font-size: 19px;
            overflow-wrap: anywhere;
        }

        .weight {
            margin: 0;
            font-size: 14px;
            color: #5b6b62;
        }

        .price {
            margin: 10px 0 6px;
            font-size: 22px;
            font-weight: bold;
            color: #075734;
        }

        .price small {
            font-size: 12px;
            font-weight: normal;
            color: #5b6b62;
        }

        .stock {
            margin: 0 0 16px;
            font-size: 14px;
            color: #166534;
        }

        .stock.empty {
            color: #8a5418;
        }

        .product-content .button {
            width: 100%;
            margin-top: auto;
        }

        .empty-state {
            padding: 36px 20px;
            border: 1px solid #e1e7dc;
            border-radius: 14px;
            background: white;
            text-align: center;
        }

        .empty-state h2 {
            margin-top: 0;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 28px;
        }

        .pagination a {
            padding: 10px 16px;
            border: 1px solid #cbd5cb;
            border-radius: 9px;
            background: white;
            text-decoration: none;
            font-weight: bold;
        }

        footer {
            padding: 20px 0;
            border-top: 1px solid #e1e7dc;
            background: white;
            color: #5b6b62;
            font-size: 14px;
        }

        @media (max-width: 800px) {
            .product-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 380px) {
            .product-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="container header-content">
            <a class="brand" href="{{ route('home') }}">
                <strong>JarakFeed</strong>
                <small>BUMDes Desa Jarak</small>
            </a>

            <a class="back-link" href="{{ route('home') }}">
                Beranda
            </a>
        </div>
    </header>

    <main class="container">
        <h1>Pilih Pakan</h1>

        <p class="subtitle">
            Lihat produk dan ketersediaan pakan di BUMDes Desa Jarak.
        </p>

        <form
            class="search-form"
            action="{{ route('catalog.index') }}"
            method="GET"
            role="search"
        >
            <div class="search-field">
                <label for="search">Cari pakan</label>

                <input
                    id="search"
                    type="search"
                    name="q"
                    value="{{ $search }}"
                    placeholder="Ketik nama pakan"
                    maxlength="100"
                >
            </div>

            <button class="button search-button" type="submit">
                Cari
            </button>
        </form>

        @error('q')
            <p class="error">{{ $message }}</p>
        @enderror

        @if ($search !== '')
            <p class="search-result">
                Hasil pencarian: <strong>{{ $search }}</strong>
                <a href="{{ route('catalog.index') }}">Lihat semua</a>
            </p>
        @endif

        @if ($products->isEmpty())
            <div class="empty-state">
                <h2>
                    {{ $search !== '' ? 'Pakan tidak ditemukan' : 'Belum ada produk pakan' }}
                </h2>

                <p>
                    {{ $search !== ''
                        ? 'Coba cari dengan nama lain atau lihat semua produk.'
                        : 'Informasi produk akan ditampilkan setelah ditambahkan oleh pengurus.' }}
                </p>

                @if ($search !== '')
                    <a class="button" href="{{ route('catalog.index') }}">
                        Lihat Semua Pakan
                    </a>
                @endif
            </div>
        @else
            <div class="product-grid">
                @foreach ($products as $product)
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
                                height="450"
                            >
                        @else
                            <div class="image-placeholder">
                                Foto pakan belum tersedia
                            </div>
                        @endif

                        <div class="product-content">
                            <h2>{{ $product->name }}</h2>

                            <p class="weight">
                                {{ number_format((float) $product->weight_kg, 2, ',', '.') }}
                                kg / kemasan
                            </p>

                            <p class="price">
                                Rp{{ number_format((float) $product->price, 0, ',', '.') }}
                                <small>/ kemasan</small>
                            </p>

                            @if ($stock > 0)
                                <p class="stock">● Tersedia</p>
                            @else
                                <p class="stock empty">● Stok habis</p>
                            @endif

                            <a
                                class="button"
                                href="{{ route('catalog.show', $product) }}"
                                aria-label="Lihat detail {{ $product->name }}"
                            >
                                Lihat Detail
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($products->hasPages())
                <nav class="pagination" aria-label="Halaman katalog">
                    @if ($products->previousPageUrl())
                        <a href="{{ $products->previousPageUrl() }}">
                            Sebelumnya
                        </a>
                    @endif

                    <span>
                        Halaman {{ $products->currentPage() }}
                        dari {{ $products->lastPage() }}
                    </span>

                    @if ($products->nextPageUrl())
                        <a href="{{ $products->nextPageUrl() }}">
                            Berikutnya
                        </a>
                    @endif
                </nav>
            @endif
        @endif
    </main>

    <footer>
        <div class="container">
            JarakFeed · BUMDes Desa Jarak
        </div>
    </footer>
</body>
</html>