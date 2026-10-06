<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk — JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f7f8f2;
            color: #24392c;
            line-height: 1.6;
        }

        a {
            color: #25613e;
        }

        .container {
            width: min(100% - 32px, 480px);
            margin: 40px auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            padding: 32px;
            background: #fff;
            border: 1px solid #e1e8de;
            border-radius: 20px;
            box-shadow: 0 8px 28px rgba(36, 57, 44, 0.05);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        h1 {
            margin: 20px 0 8px;
            font-size: 28px;
            line-height: 1.3;
        }

        .intro {
            margin: 0 0 24px;
            color: #637267;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .text-input {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #b9c7bc;
            border-radius: 10px;
            background: #fff;
            color: #24392c;
            font: inherit;
            font-size: 16px;
        }

        .text-input:focus {
            outline: 3px solid #dcebdc;
            border-color: #25613e;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #8eba96;
            outline-offset: 3px;
        }

        .error {
            margin: 6px 0 0;
            color: #a32929;
            font-size: 14px;
        }

        .alert {
            margin-bottom: 20px;
            padding: 12px 16px;
            border-radius: 10px;
        }

        .alert-error {
            border: 1px solid #efc4c4;
            background: #fff1f1;
            color: #a32929;
        }

        .alert-success {
            border: 1px solid #c8dfcc;
            background: #edf7ee;
            color: #25613e;
        }

        .password-toggle {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0 0 24px;
            font-weight: normal;
            cursor: pointer;
        }

        .password-toggle input {
            width: 20px;
            height: 20px;
            margin: 0;
            accent-color: #25613e;
        }

        .submit-button {
            width: 100%;
            min-height: 50px;
            padding: 13px 20px;
            border: 0;
            border-radius: 12px;
            background: #25613e;
            color: #fff;
            font: inherit;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-button:hover {
            background: #1d4d31;
        }

        .register-link {
            margin: 22px 0 0;
            text-align: center;
        }

        .admin-link {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #e1e8de;
            text-align: center;
            font-size: 14px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #637267;
            font-size: 14px;
        }

        @media (max-width: 480px) {
            .container {
                margin: 24px auto;
            }

            .card {
                padding: 24px 20px;
            }

            h1 {
                font-size: 25px;
            }
        }
    </style>
</head>
<body>
    <main class="container">
        <a class="back-link" href="{{ route('home') }}">
            ← Kembali ke beranda
        </a>

        <section class="card" aria-labelledby="login-heading">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>

            <h1 id="login-heading">Masuk akun pelanggan</h1>

            <p class="intro">
                Gunakan email atau nomor HP yang kamu daftarkan.
            </p>

            @if (session('success'))
                <div class="alert alert-success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error" role="alert">
                    Belum berhasil masuk. Periksa isian di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login.store') }}">
                @csrf

                <div class="field">
                    <label for="login">Email atau nomor HP</label>
                    <input
                        class="text-input"
                        id="login"
                        name="login"
                        type="text"
                        value="{{ old('login') }}"
                        autocomplete="username"
                        autocapitalize="none"
                        spellcheck="false"
                        maxlength="255"
                        required
                        @error('login')
                            aria-invalid="true"
                            aria-describedby="login-error"
                        @enderror
                    >

                    @error('login')
                        <p class="error" id="login-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input
                        class="text-input"
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        maxlength="72"
                        required
                        @error('password')
                            aria-invalid="true"
                            aria-describedby="password-error"
                        @enderror
                    >

                    @error('password')
                        <p class="error" id="password-error">{{ $message }}</p>
                    @enderror
                </div>

                <label class="password-toggle" for="show-password">
                    <input id="show-password" type="checkbox">
                    Tampilkan kata sandi
                </label>

                <button class="submit-button" type="submit">
                    Masuk
                </button>
            </form>

            <p class="register-link">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar di sini</a>
            </p>

            <div class="admin-link">
                Pengurus BUMDes?
                <a href="{{ url('/admin/login') }}">Masuk pengurus</a>
            </div>
        </section>

        <p class="footer">BUMDes Desa Jarak</p>
    </main>

    <script>
        document.getElementById('show-password')
            .addEventListener('change', function () {
                document.getElementById('password').type =
                    this.checked ? 'text' : 'password';
            });
    </script>
</body>
</html>