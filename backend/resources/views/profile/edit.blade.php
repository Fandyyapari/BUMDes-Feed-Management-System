<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya — JarakFeed</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #faf8f1;
            color: #24382b;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        a {
            color: #075734;
        }

        .header {
            background: #fff;
            border-bottom: 1px solid #dedfd3;
        }

        .header-content {
            width: min(960px, calc(100% - 32px));
            margin: auto;
            padding: 18px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .brand {
            color: #075734;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .back-link {
            padding: 10px 0;
            font-size: 14px;
        }

        main {
            width: min(620px, calc(100% - 32px));
            margin: 32px auto;
        }

        h1 {
            margin: 0 0 8px;
            font-size: 30px;
        }

        .intro {
            margin: 0 0 24px;
            color: #627065;
        }

        .card {
            padding: 28px;
            background: #fff;
            border: 1px solid #dedfd3;
            border-radius: 16px;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        .optional {
            color: #627065;
            font-size: 13px;
            font-weight: normal;
        }

        input,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #adb8ae;
            border-radius: 10px;
            background: #fff;
            color: #24382b;
            font: inherit;
            font-size: 16px;
        }

        textarea {
            min-height: 110px;
            resize: vertical;
        }

        input:focus,
        textarea:focus {
            border-color: #075734;
            outline: 3px solid #e2eee5;
        }

        input[aria-invalid="true"],
        textarea[aria-invalid="true"] {
            border-color: #a52a2a;
        }

        .hint {
            margin: 6px 0 0;
            color: #627065;
            font-size: 13px;
        }

        .error {
            margin: 6px 0 0;
            color: #a52a2a;
            font-size: 14px;
        }

        .notice {
            padding: 14px 16px;
            margin-bottom: 20px;
            border-radius: 10px;
            overflow-wrap: anywhere;
        }

        .success {
            background: #e9f5eb;
            border: 1px solid #acd0b3;
            color: #18592d;
        }

        .error-notice {
            background: #fff0f0;
            border: 1px solid #e6b7b7;
            color: #8b2424;
        }

        .account-info {
            margin-bottom: 24px;
            padding: 16px;
            background: #f5f7f2;
            border-radius: 10px;
        }

        .account-info h2 {
            margin: 0 0 12px;
            font-size: 17px;
        }

        .account-info dl {
            margin: 0;
        }

        .account-info dt {
            color: #627065;
            font-size: 13px;
        }

        .account-info dd {
            margin: 0 0 12px;
            overflow-wrap: anywhere;
        }

        .actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 48px;
            padding: 12px 20px;
            border: 1px solid #075734;
            border-radius: 10px;
            background: #075734;
            color: #fff;
            font: inherit;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .button:hover {
            background: #043d24;
        }

        .button-secondary {
            background: #fff;
            color: #075734;
        }

        .button-secondary:hover {
            background: #edf4eb;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid #bd891f;
            outline-offset: 3px;
        }

        footer {
            padding: 0 16px 24px;
            text-align: center;
            color: #627065;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            main {
                margin: 24px auto;
            }

            .card {
                padding: 20px;
            }

            h1 {
                font-size: 26px;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .button {
                width: 100%;
            }
        }

        .profile-navigation {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 8px 16px;
        }

        .profile-navigation a {
            display: inline-flex;
            align-items: center;
            min-height: 44px;
        }

        @media (max-width: 600px) {
            .header-content {
                flex-wrap: wrap;
                gap: 12px;
            }

            .profile-navigation {
                width: 100%;
                justify-content: flex-start;
            }
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="header-content">
            <a class="brand" href="{{ route('home') }}">JarakFeed</a>

            <nav class="profile-navigation" aria-label="Menu utama">
                <a class="back-link" href="{{ route('home') }}">
                    Kembali ke Beranda
                </a>

                @auth
                    @if (auth()->user()->role === 'pelanggan')
                        <a class="back-link" href="{{ route('customer.orders.index') }}">
                            Pesanan Saya
                        </a>
                    @endif
                @endauth
            </nav>
        </div>
    </header>

    <main>
        <h1>Profil Saya</h1>
        <p class="intro">
            Lengkapi nama dan alamat agar pengurus lebih mudah
            mengenali data pelanggan.
        </p>

        @if (session('success'))
            <div class="notice success" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="notice error-notice" role="alert">
                Profil belum tersimpan. Periksa isian yang ditandai di bawah.
            </div>
        @endif

        <div class="card">
            <section class="account-info" aria-labelledby="account-heading">
                <h2 id="account-heading">Informasi Akun</h2>

                <dl>
                    <dt>Nomor HP</dt>
                    <dd>{{ $user->phone ?: 'Belum diisi' }}</dd>

                    <dt>Email</dt>
                    <dd>{{ $user->email }}</dd>
                </dl>

                <p class="hint">
                    Nomor HP dan email digunakan untuk masuk ke akun.
                    Keduanya belum dapat diubah melalui halaman ini.
                </p>
            </section>

            <form method="POST" action="{{ route('customer.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="name">Nama Lengkap</label>

                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        autocomplete="name" maxlength="255" required
                        aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}"
                        @error('name') aria-describedby="name-error" @enderror>

                    @error('name')
                        <p class="error" id="name-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="dusun">
                        Dusun <span class="optional">(boleh dikosongkan)</span>
                    </label>

                    <input id="dusun" name="dusun" type="text" value="{{ old('dusun', $user->dusun) }}"
                        maxlength="100" placeholder="Nama dusun tempat tinggal"
                        aria-invalid="{{ $errors->has('dusun') ? 'true' : 'false' }}"
                        @error('dusun') aria-describedby="dusun-error" @enderror>

                    @error('dusun')
                        <p class="error" id="dusun-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="field">
                    <label for="address">
                        Alamat Lengkap
                        <span class="optional">(boleh dikosongkan)</span>
                    </label>

                    <textarea id="address" name="address" autocomplete="street-address" maxlength="1000"
                        placeholder="Contoh: RT 02/RW 01, Desa Jarak" aria-invalid="{{ $errors->has('address') ? 'true' : 'false' }}"
                        aria-describedby="address-hint{{ $errors->has('address') ? ' address-error' : '' }}">{{ old('address', $user->address) }}</textarea>

                    <p class="hint" id="address-hint">
                        Isi RT/RW dan keterangan alamat yang mudah dikenali.
                    </p>

                    @error('address')
                        <p class="error" id="address-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="actions">
                    <button class="button" type="submit">
                        Simpan Profil
                    </button>

                    <a class="button button-secondary" href="{{ route('home') }}">
                        Kembali
                    </a>
                </div>
            </form>

            <section aria-labelledby="password-heading"
                style="margin-top: 24px; padding: 24px; background: #fff; border: 1px solid #dce5dc; border-radius: 16px;">
                <h2 id="password-heading" style="margin: 0 0 8px; color: #234b35;">
                    Ubah Kata Sandi
                </h2>

                <p style="margin-bottom: 20px; color: #526354;">
                    Masukkan kata sandi lama dan kata sandi baru untuk akunmu.
                </p>

                <form method="POST" action="{{ route('customer.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 16px;">
                        <label for="current_password" style="display: block; margin-bottom: 8px;">
                            Kata Sandi Lama
                        </label>

                        <input id="current_password" name="current_password" type="password"
                            autocomplete="current-password" required
                            style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #87988a; border-radius: 8px;">

                        @error('current_password', 'updatePassword')
                            <p role="alert" style="color: #b91c1c; margin-top: 6px;">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="password" style="display: block; margin-bottom: 8px;">
                            Kata Sandi Baru
                        </label>

                        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8"
                            maxlength="72" aria-describedby="password-help" required
                            style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #87988a; border-radius: 8px;">

                        <p id="password-help" style="font-size: 14px; margin-top: 6px;">
                            Minimal 8 karakter dan berbeda dari kata sandi lama.
                        </p>

                        @error('password', 'updatePassword')
                            <p role="alert" style="color: #b91c1c; margin-top: 6px;">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label for="password_confirmation" style="display: block; margin-bottom: 8px;">
                            Konfirmasi Kata Sandi Baru
                        </label>

                        <input id="password_confirmation" name="password_confirmation" type="password"
                            autocomplete="new-password" minlength="8" maxlength="72" required
                            style="width: 100%; box-sizing: border-box; padding: 12px; border: 1px solid #87988a; border-radius: 8px;">
                    </div>

                    <button type="submit"
                        style="padding: 12px 20px; background: #245b3b; color: #fff; border: none; border-radius: 8px; font: inherit; cursor: pointer;">
                        Simpan Kata Sandi
                    </button>
                </form>
            </section>
        </div>
    </main>

    <footer>BUMDes Desa Jarak · JarakFeed</footer>
</body>

</html>
