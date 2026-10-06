<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun — JarakFeed</title>

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
            width: min(100% - 32px, 520px);
            margin: 32px auto;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            font-weight: bold;
        }

        .card {
            background: #fff;
            padding: 32px;
            border: 1px solid #e1e8de;
            border-radius: 20px;
            box-shadow: 0 8px 28px rgba(36, 57, 44, 0.05);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #25613e;
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
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
        }

        .optional {
            font-weight: normal;
            font-size: 14px;
            color: #637267;
        }

        input,
        textarea {
            width: 100%;
            padding: 13px 14px;
            border: 1px solid #b9c7bc;
            border-radius: 10px;
            font: inherit;
            font-size: 16px;
            background: #fff;
            color: #24392c;
        }

        textarea {
            min-height: 96px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            outline: 3px solid #dcebdc;
            border-color: #25613e;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #8eba96;
            outline-offset: 3px;
        }

        .hint {
            display: block;
            margin-top: 5px;
            color: #637267;
            font-size: 14px;
        }

        .error {
            margin: 5px 0 0;
            color: #a32929;
            font-size: 14px;
        }

        .error-summary {
            margin-bottom: 20px;
            padding: 12px 16px;
            border: 1px solid #efc4c4;
            border-radius: 10px;
            background: #fff1f1;
            color: #a32929;
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

        .login-link {
            margin: 20px 0 0;
            text-align: center;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: #637267;
        }

        @media (max-width: 480px) {
            .container {
                margin: 20px auto;
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

        <section class="card" aria-labelledby="register-heading">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>

            <h1 id="register-heading">Buat akun pelanggan</h1>

            <p class="intro">
                Isi data diri untuk membuat akun JarakFeed.
                Dusun dan alamat boleh dilengkapi nanti.
            </p>

            @if ($errors->any())
                <div class="error-summary" role="alert">
                    Data belum berhasil disimpan.
                    Periksa keterangan pada formulir di bawah.
                </div>
            @endif

            <form method="POST" action="{{ route('customer.register.store') }}">
                @csrf

                <div class="field">
                    <label for="name">Nama lengkap</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        autocomplete="name"
                        maxlength="255"
                        required
                    >
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="phone">Nomor HP</label>
                    <input
                        id="phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                        maxlength="20"
                        placeholder="Contoh: 081234567890"
                        required
                    >
                    <small class="hint">
                        Nomor ini bisa digunakan untuk masuk akun.
                    </small>
                    @error('phone')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        maxlength="255"
                        placeholder="Contoh: nama@email.com"
                        required
                    >
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="dusun">
                        Dusun <span class="optional">(opsional)</span>
                    </label>
                    <input
                        id="dusun"
                        name="dusun"
                        type="text"
                        value="{{ old('dusun') }}"
                        maxlength="100"
                        placeholder="Nama dusun tempat tinggal"
                    >
                    @error('dusun')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="address">
                        Alamat <span class="optional">(opsional)</span>
                    </label>
                    <textarea
                        id="address"
                        name="address"
                        autocomplete="street-address"
                        maxlength="1000"
                        placeholder="Jalan, nomor rumah, RT/RW, atau patokan rumah"
                    >{{ old('address') }}</textarea>
                    @error('address')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Kata sandi</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="new-password"
                        minlength="8"
                        maxlength="72"
                        required
                    >
                    <small class="hint">Gunakan minimal 8 karakter.</small>
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">
                        Ulangi kata sandi
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

                <label class="password-toggle" for="show-password">
                    <input id="show-password" type="checkbox">
                    Tampilkan kata sandi
                </label>

                <button class="submit-button" type="submit">
                    Daftar sekarang
                </button>
            </form>

            <p class="login-link">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </section>

        <p class="footer">BUMDes Desa Jarak</p>
    </main>

    <script>
        document.getElementById('show-password')
            .addEventListener('change', function () {
                const type = this.checked ? 'text' : 'password';

                document.getElementById('password').type = type;
                document.getElementById('password_confirmation').type = type;
            });
    </script>
</body>
</html>