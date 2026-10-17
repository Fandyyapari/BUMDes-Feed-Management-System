<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi — JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f8f2;
            color: #243b2c;
            line-height: 1.6;
        }

        .container {
            width: min(100% - 32px, 460px);
            margin: 48px auto;
        }

        .brand {
            display: block;
            margin-bottom: 24px;
            color: #245b3b;
            text-align: center;
            text-decoration: none;
        }

        .brand strong {
            display: block;
            font-size: 28px;
        }

        .brand small {
            color: #58685c;
        }

        .card {
            padding: 28px;
            background: white;
            border: 1px solid #dce5dc;
            border-radius: 20px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 25px;
            line-height: 1.3;
        }

        .description {
            margin: 0 0 24px;
            color: #58685c;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input {
            width: 100%;
            min-height: 48px;
            padding: 12px;
            border: 1px solid #aab9ad;
            border-radius: 10px;
            font: inherit;
        }

        input:focus-visible,
        button:focus-visible,
        a:focus-visible {
            outline: 3px solid #88b79a;
            outline-offset: 3px;
        }

        button {
            width: 100%;
            min-height: 48px;
            margin-top: 20px;
            padding: 12px 16px;
            background: #245b3b;
            color: white;
            border: 0;
            border-radius: 10px;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background: #19482d;
        }

        .message {
            margin-bottom: 20px;
            padding: 14px;
            background: #edf7ef;
            border: 1px solid #b9d9bf;
            border-radius: 10px;
            overflow-wrap: anywhere;
        }

        .error {
            margin: 8px 0 0;
            color: #b42318;
            font-size: 14px;
        }

        .back-link {
            display: block;
            margin-top: 22px;
            color: #245b3b;
            text-align: center;
        }

        @media (max-width: 480px) {
            .container {
                margin: 24px auto;
            }

            .card {
                padding: 22px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <a class="brand" href="{{ route('home') }}">
            <strong>JarakFeed</strong>
            <small>BUMDes Desa Jarak</small>
        </a>

        <section class="card" aria-labelledby="page-title">
            <h1 id="page-title">Lupa Kata Sandi?</h1>

            <p class="description">
                Masukkan email yang kamu gunakan saat mendaftar.
                Kami akan mengirim tautan untuk membuat kata sandi baru.
            </p>

            @if (session('status'))
                <div class="message" role="status">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <label for="email">Email Terdaftar</label>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    placeholder="Contoh: nama@email.com"
                    autocomplete="email"
                    maxlength="255"
                    required
                    @error('email')
                        aria-invalid="true"
                        aria-describedby="email-error"
                    @enderror
                >

                @error('email')
                    <p id="email-error" class="error" role="alert">
                        {{ $message }}
                    </p>
                @enderror

                <button type="submit">
                    Kirim Tautan Reset
                </button>
            </form>

            <a class="back-link" href="{{ route('login') }}">
                Kembali ke Halaman Masuk
            </a>
        </section>
    </main>
</body>
</html>