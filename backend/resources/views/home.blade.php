<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>JarakFeed — BUMDes Desa Jarak</title>

    <style>
        :root {
            --green: #075734;
            --green-dark: #043d24;
            --cream: #faf8f1;
            --text: #24382b;
            --muted: #627065;
            --border: #dedfd3;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--text);
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a {
            color: inherit;
        }

        a:focus-visible {
            outline: 3px solid #bd891f;
            outline-offset: 5px;
        }

        .container {
            width: min(1080px, calc(100% - 40px));
            margin: auto;
        }

        .header {
            background: #fff;
            border-bottom: 1px solid var(--border);
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            padding: 18px 0;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-icon {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 14px;
            background: #e8efe2;
            color: var(--green);
        }

        .brand-icon svg {
            width: 28px;
            height: 28px;
        }

        .brand strong {
            display: block;
            color: var(--green);
            font-size: 24px;
            line-height: 1.2;
        }

        .brand small {
            color: var(--muted);
            font-size: 12px;
        }

        .header-link {
            color: var(--green);
            font-weight: bold;
            text-decoration: none;
            padding: 10px 0;
        }

        .hero {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 48px;
            align-items: center;
            padding: 56px 0;
        }

        .eyebrow {
            color: var(--green);
            font-size: 14px;
            font-weight: bold;
            margin: 0 0 14px;
        }

        h1 {
            font-size: clamp(34px, 5vw, 56px);
            line-height: 1.15;
            letter-spacing: -1.5px;
            margin: 0 0 20px;
        }

        .hero-description {
            color: var(--muted);
            font-size: 18px;
            margin: 0 0 28px;
        }

        .buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 24px;
            border: 1px solid var(--green);
            border-radius: 12px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
        }

        .button-primary {
            color: #fff;
            background: var(--green);
        }

        .button-primary:hover {
            background: var(--green-dark);
        }

        .button-secondary {
            color: var(--green);
            background: transparent;
        }

        .button-secondary:hover {
            background: #e8efe2;
        }

        .hero-visual {
            margin: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #e8efe2;
            border: 1px solid var(--border);
        }

        .hero-visual img {
            display: block;
            width: 100%;
            height: 360px;
            object-fit: cover;
        }

        .photo-placeholder {
            min-height: 360px;
            display: grid;
            place-items: center;
            padding: 32px;
            text-align: center;
            color: var(--green);
            background: linear-gradient(135deg, #e8efe2, #d4e3ce);
        }

        .photo-placeholder strong {
            display: block;
            font-size: 32px;
            margin-bottom: 8px;
        }

        figcaption {
            padding: 12px 18px;
            background: #fff;
            color: var(--muted);
            font-size: 14px;
        }

        .section {
            padding: 28px 0 48px;
            scroll-margin-top: 20px;
        }

        .section-heading {
            text-align: center;
            margin-bottom: 28px;
        }

        h2 {
            font-size: 28px;
            line-height: 1.3;
            margin: 0 0 10px;
        }

        .section-heading p {
            margin: 0;
            color: var(--muted);
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .step {
            background: #fff;
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: 18px;
        }

        .step-number {
            width: 44px;
            height: 44px;
            display: grid;
            place-items: center;
            border-radius: 50%;
            color: var(--green);
            background: #e8efe2;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        h3 {
            margin: 0 0 8px;
            font-size: 19px;
        }

        .step p {
            margin: 0;
            color: var(--muted);
        }

        .help {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 24px;
            padding: 30px;
            background: #e8efe2;
            border-radius: 20px;
        }

        .help p {
            margin: 0;
            color: var(--muted);
            max-width: 620px;
        }

        .help .button {
            flex-shrink: 0;
        }

        .footer {
            border-top: 1px solid var(--border);
            padding: 24px 0;
            margin-top: 12px;
            font-size: 14px;
            color: var(--muted);
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer p {
            margin: 0;
        }

        @media (max-width: 760px) {
            .hero {
                grid-template-columns: 1fr;
                gap: 28px;
                padding: 28px 0 36px;
            }

            .hero-visual {
                grid-row: 1;
            }

            .hero-visual img,
            .photo-placeholder {
                height: 260px;
                min-height: 260px;
            }

            .steps {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .help {
                align-items: flex-start;
                flex-direction: column;
                padding: 24px;
            }

            .help .button {
                width: 100%;
            }
        }

        @media (max-width: 400px) {
            .container {
                width: calc(100% - 28px);
            }

            .brand strong {
                font-size: 21px;
            }

            .header-link {
                font-size: 14px;
            }

            .buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container header-content">
            <a class="brand" href="{{ route('home') }}" aria-label="Beranda JarakFeed">
                <span class="brand-icon" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none">
                        <path
                            d="M26 5C14 4 6 9 6 17a9 9 0 0 0 9 9c8 0 12-9 11-21Z"
                            stroke="currentColor"
                            stroke-width="2"
                        />
                        <path
                            d="M6 27 21 12M12 21v-7M17 16h7"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        />
                    </svg>
                </span>

                <span>
                    <strong>JarakFeed</strong>
                    <small>BUMDes Desa Jarak</small>
                </span>
            </a>

            <a class="header-link" href="{{ route('catalog.index') }}">
                Lihat Pakan
            </a>
        </div>
    </header>

    <main class="container">

        <section class="hero" aria-labelledby="hero-title">
            <div>
                <p class="eyebrow">PAKAN FERMENTASI DESA JARAK</p>

                <h1 id="hero-title">
                    Kenali pakannya,<br>
                    cek ketersediaannya.
                </h1>

                <p class="hero-description">
                    Temukan informasi pakan fermentasi yang dikelola
                    BUMDes Desa Jarak. Lihat pilihan kemasan, harga,
                    dan stok sebelum membeli.
                </p>

                <div class="buttons">
                    <a
                        class="button button-primary"
                        href="{{ route('catalog.index') }}"
                    >
                        Lihat Pakan
                    </a>

                    <a
                        class="button button-secondary"
                        href="#panduan"
                    >
                        Panduan Pembelian
                    </a>
                </div>
            </div>

            <figure class="hero-visual">
                @if ($featuredProduct)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($featuredProduct->image) }}"
                        alt="{{ $featuredProduct->name }}"
                        fetchpriority="high"
                    >

                    <figcaption>
                        {{ $featuredProduct->name }} — BUMDes Desa Jarak
                    </figcaption>
                @else
                    <div class="photo-placeholder">
                        <div>
                            <strong>JarakFeed</strong>
                            <span>Pakan fermentasi BUMDes Desa Jarak</span>
                        </div>
                    </div>
                @endif
            </figure>
        </section>

        <section
            class="section"
            id="panduan"
            aria-labelledby="guide-title"
        >
            <div class="section-heading">
                <h2 id="guide-title">Mau membeli pakan?</h2>
                <p>Ikuti langkah sederhana berikut.</p>
            </div>

            <ol class="steps">
                <li class="step">
                    <span class="step-number" aria-hidden="true">1</span>
                    <h3>Pilih Pakan</h3>
                    <p>
                        Buka katalog dan lihat produk, ukuran kemasan,
                        harga, serta ketersediaannya.
                    </p>
                </li>

                <li class="step">
                    <span class="step-number" aria-hidden="true">2</span>
                    <h3>Konfirmasi ke Pengurus</h3>
                    <p>
                        Tanyakan jumlah yang dibutuhkan, ketersediaan
                        terbaru, dan cara pembayaran kepada pengurus BUMDes.
                    </p>
                </li>

                <li class="step">
                    <span class="step-number" aria-hidden="true">3</span>
                    <h3>Ambil di BUMDes</h3>
                    <p>
                        Ambil pakan sesuai waktu dan lokasi yang
                        sudah disepakati dengan pengurus.
                    </p>
                </li>
            </ol>
        </section>

        <section class="section" aria-labelledby="help-title">
            <div class="help">
                <div>
                    <h2 id="help-title">Masih bingung memilih pakan?</h2>
                    <p>
                        Lihat komposisi, cara penggunaan, dan cara penyimpanan
                        pada detail produk. Untuk informasi lebih lanjut,
                        tanyakan kepada pengurus BUMDes Desa Jarak.
                    </p>
                </div>

                <a
                    class="button button-primary"
                    href="{{ route('catalog.index') }}"
                >
                    Buka Katalog
                </a>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container footer-content">
            <p>JarakFeed · BUMDes Desa Jarak</p>

            <p>
                Desa Jarak, Kecamatan Plosoklaten, Kabupaten Kediri
            </p>

            <a href="{{ url('/admin/login') }}">
                Masuk Pengurus
            </a>
        </div>
    </footer>
</body>
</html>