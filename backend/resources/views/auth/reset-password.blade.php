<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="referrer" content="no-referrer">
    <title>Buat Kata Sandi Baru — JarakFeed</title>

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

        .field {
            margin-bottom: 20px;
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

        input[readonly] {
            background: #f3f5f1;
            color: #58685c;
        }

        input:focus-visible,
        button:focus-visible,
        a:focus-visible {
            outline: 3px solid #88b79a;
            outline-offset: 3px;
        }

        .hint {
            margin: 6px 0 0;
            color: #58685c;
            font-size: 14px;
        }

        .error {
            margin: 8px 0 0;
            color: #b42318;
            font-size: 14px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 14px;
            color: #b42318;
            background: #fff1f0;
            border: 1px solid #f3b9b4;
            border-radius: 10px;
        }

        button {
            width: 100%;
            min-height: 48px;
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
            <h1 id="page-title">Buat Kata Sandi Baru</h1>

            <p class="description">
                Masukkan kata sandi baru, lalu ketik ulang
                untuk memastikan keduanya sama.
            </p>

            @error('token')
                <div class="alert" role="alert">
                    {{ $message }}
                </div>
            @enderror

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="field">
                    <label for="email">Email Akun</label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ $email }}"
                        autocomplete="email"
                        readonly
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
                </div>

                <div class="field">
                    <label for="password">Kata Sandi Baru</label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="72"
                        required
                        aria-describedby="password-hint password-error"
                        @error('password')
                            aria-invalid="true"
                        @enderror
                    >

                    <p id="password-hint" class="hint">
                        Minimal 8 karakter. Gunakan kata sandi
                        yang sulit ditebak.
                    </p>

                    <p id="password-error" class="error" role="alert">
                        @error('password')
                            {{ $message }}
                        @enderror
                    </p>
                </div>

                <div class="field">
                    <label for="password_confirmation">
                        Ulangi Kata Sandi Baru
                    </label>

                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="72"
                        required
                    >
                </div>

                <button type="submit">
                    Simpan Kata Sandi Baru
                </button>
            </form>

            <a class="back-link" href="{{ route('password.request') }}">
                Minta Tautan Reset Baru
            </a>

            <a class="back-link" href="{{ route('login') }}">
                Kembali ke Halaman Masuk
            </a>
        </section>
    </main>
</body>
</html>