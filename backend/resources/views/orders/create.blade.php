<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Pakan — JarakFeed</title>

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
            width: min(680px, calc(100% - 32px));
            margin: 28px auto;
        }

        h1 { margin: 0 0 8px; font-size: 30px; }
        h2 { margin: 0 0 12px; font-size: 20px; }
        p { margin: 0 0 12px; }

        .muted { color: #627065; }

        .card {
            padding: 24px;
            margin-top: 20px;
            background: #fff;
            border: 1px solid #dedfd3;
            border-radius: 16px;
        }

        .product {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .product-image {
            width: 96px;
            height: 96px;
            flex-shrink: 0;
            border-radius: 12px;
            object-fit: cover;
            background: #edf4eb;
        }

        .product-info { min-width: 0; }
        .product-info h2 { overflow-wrap: anywhere; }
        .product-info p { margin-bottom: 4px; }

        .price {
            color: #075734;
            font-size: 20px;
            font-weight: bold;
        }

        .notice {
            padding: 14px 16px;
            margin-top: 20px;
            border-radius: 10px;
            background: #fff5db;
            border: 1px solid #e7d29b;
        }

        .error-notice {
            background: #fff0f0;
            border-color: #e6b7b7;
            color: #8b2424;
        }

        .error-notice ul {
            margin: 8px 0 0;
            padding-left: 20px;
        }

        .field { margin-bottom: 20px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #adb8ae;
            border-radius: 10px;
            color: #24382b;
            background: #fff;
            font: inherit;
            font-size: 16px;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        input:focus, textarea:focus {
            outline: 3px solid #e2eee5;
            border-color: #075734;
        }

        .hint {
            margin-top: 6px;
            color: #627065;
            font-size: 13px;
        }

        .error {
            margin-top: 6px;
            color: #a52a2a;
            font-size: 14px;
        }

        dl { margin: 0; }
        dt { color: #627065; font-size: 13px; }
        dd {
            margin: 0 0 12px;
            overflow-wrap: anywhere;
            white-space: pre-line;
        }

        .total {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
            padding: 16px;
            margin-bottom: 8px;
            background: #edf4eb;
            border-radius: 10px;
            font-weight: bold;
        }

        .actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 18px;
            border: 1px solid #075734;
            border-radius: 10px;
            background: #075734;
            color: #fff;
            font: inherit;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover { background: #043d24; }

        .secondary {
            background: #fff;
            color: #075734;
        }

        .secondary:hover { background: #edf4eb; }

        .button:disabled {
            background: #dce2dc;
            border-color: #dce2dc;
            color: #536153;
            cursor: not-allowed;
        }

        a:focus-visible, button:focus-visible {
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
            .card { padding: 18px; }
            h1 { font-size: 26px; }
            .product-image { width: 72px; height: 72px; }
            .product-info h2 { font-size: 18px; }
            .actions { flex-direction: column; }
            .button { width: 100%; }
        }
    </style>
</head>

<body>
    <header>
        <div class="header-content">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>
            <a href="{{ route('catalog.index') }}">Lihat Pakan</a>
        </div>
    </header>

    <main>
        <h1>Pesan Pakan</h1>
        <p class="muted">
            Pilih jumlah kemasan, lalu kirim pesanan kepada pengurus.
        </p>

        @if ($errors->any())
            <div class="notice error-notice" role="alert">
                <strong>Pesanan belum berhasil dikirim.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="card product" aria-label="Produk yang dipesan">
            @if ($product->image)
                <img
                    class="product-image"
                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($product->image) }}"
                    alt="{{ $product->name }}"
                >
            @endif

            <div class="product-info">
                <h2>{{ $product->name }}</h2>

                <p class="muted">
                    {{ number_format((float) $product->weight_kg, 2, ',', '.') }}
                    kg per kemasan
                </p>

                <p class="price">
                    Rp {{ number_format((float) $product->price, 2, ',', '.') }}
                    <small>/ kemasan</small>
                </p>

                <p>
                    Stok saat ini:
                    <strong>{{ number_format($stock, 0, ',', '.') }} kemasan</strong>
                </p>
            </div>
        </section>

        <div class="notice">
            Pesanan perlu dikonfirmasi pengurus. Pengambilan pakan dan
            pembayaran disepakati setelah konfirmasi.
        </div>

        <section class="card">
            <h2>Data Pemesan</h2>

            <dl>
                <dt>Nama</dt>
                <dd>{{ $customer->name }}</dd>

                <dt>Nomor HP</dt>
                <dd>{{ $customer->phone ?: 'Belum diisi' }}</dd>

                <dt>Dusun</dt>
                <dd>{{ $customer->dusun ?: 'Belum diisi' }}</dd>

                <dt>Alamat</dt>
                <dd>{{ $customer->address ?: 'Belum diisi' }}</dd>
            </dl>

            <a href="{{ route('customer.profile.edit') }}">
                Ubah nama atau alamat di Profil Saya
            </a>
        </section>

        <section class="card">
            <h2>Jumlah Pesanan</h2>

            @if ($stock > 0)
                <form
                    id="order-form"
                    method="POST"
                    action="{{ route('customer.orders.store', ['feedProduct' => $product->id]) }}"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="request_token"
                        value="{{ old('request_token', $requestToken) }}"
                    >

                    <div class="field">
                        <label for="quantity">Jumlah Kemasan</label>

                        <input
                            id="quantity"
                            name="quantity"
                            type="number"
                            min="1"
                            max="{{ min($stock, 10000) }}"
                            step="1"
                            value="{{ old('quantity', 1) }}"
                            required
                            aria-invalid="{{ $errors->has('quantity') ? 'true' : 'false' }}"
                            aria-describedby="quantity-hint{{ $errors->has('quantity') ? ' quantity-error' : '' }}"
                        >

                        <p class="hint" id="quantity-hint">
                            Isi jumlah kemasan, bukan berat dalam kilogram.
                        </p>

                        @error('quantity')
                            <p class="error" id="quantity-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="customer_notes">
                            Catatan <span class="muted">(boleh dikosongkan)</span>
                        </label>

                        <textarea
                            id="customer_notes"
                            name="customer_notes"
                            maxlength="1000"
                            placeholder="Contoh: Saya ingin mengambil pakan hari Sabtu."
                            aria-invalid="{{ $errors->has('customer_notes') ? 'true' : 'false' }}"
                            @error('customer_notes') aria-describedby="notes-error" @enderror
                        >{{ old('customer_notes') }}</textarea>

                        @error('customer_notes')
                            <p class="error" id="notes-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="total">
                        <span>Perkiraan Total</span>
                        <output id="estimated-total" for="quantity">
                            Mengikuti jumlah kemasan
                        </output>
                    </div>

                    <p class="hint">
                        Total akhir dihitung saat pesanan dikirim menggunakan
                        harga produk yang berlaku.
                    </p>

                    <div class="actions">
                        <button class="button" id="submit-order" type="submit">
                            Kirim Pesanan
                        </button>

                        <a
                            class="button secondary"
                            href="{{ route('catalog.show', ['feedProduct' => $product->id]) }}"
                        >
                            Kembali ke Produk
                        </a>
                    </div>
                </form>
            @else
                <p>Pakan ini sedang habis. Silakan pilih produk lain.</p>

                <a class="button" href="{{ route('catalog.index') }}">
                    Lihat Pakan Lain
                </a>
            @endif
        </section>
    </main>

    <footer>BUMDes Desa Jarak · JarakFeed</footer>

    <script>
        const quantityInput = document.getElementById('quantity');
        const totalOutput = document.getElementById('estimated-total');
        const orderForm = document.getElementById('order-form');
        const submitButton = document.getElementById('submit-order');
        const unitPrice = Number(@json((string) $product->price));

        const currency = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        });

        function updateTotal() {
            if (!quantityInput || !totalOutput) return;

            const quantity = Number(quantityInput.value);

            totalOutput.textContent =
                quantityInput.value !== ''
                && Number.isInteger(quantity)
                && quantity > 0
                    ? currency.format(quantity * unitPrice)
                    : 'Isi jumlah kemasan';
        }

        quantityInput?.addEventListener('input', updateTotal);
        updateTotal();

        orderForm?.addEventListener('submit', () => {
            submitButton.disabled = true;
            submitButton.textContent = 'Mengirim Pesanan...';
        });

        window.addEventListener('pageshow', () => {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Kirim Pesanan';
            }
        });
    </script>
</body>
</html>