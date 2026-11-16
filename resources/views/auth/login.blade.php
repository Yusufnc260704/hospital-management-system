<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk Pegawai — SIMRS dr. Tjahyo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --teal: #0E9384; --teal-gelap: #0B7A6E; --tinta: #0F172A; --abu: #64748B; --garis: #E7EEF6; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--tinta);
            min-height: 100vh;
            background:
                radial-gradient(560px 320px at 90% 0%, #E4F7F4, transparent 60%),
                radial-gradient(520px 320px at 0% 100%, #EAF2FE, transparent 58%),
                #F8FCFC;
        }

        .bingkai { min-height: 100vh; }

        .panel-info {
            background: linear-gradient(150deg, #0B1F33, #0D3B5E 55%, #0B7A6E);
            color: #fff;
            border-radius: 24px;
            padding: 2.6rem;
            position: relative;
            overflow: hidden;
            height: 100%;
        }
        .panel-info::after {
            content: '';
            position: absolute;
            right: -90px; top: -90px;
            width: 280px; height: 280px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .08);
        }
        .panel-info::before {
            content: '';
            position: absolute;
            left: -60px; bottom: -110px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(14, 147, 132, .35);
        }
        .panel-info .logo {
            width: 46px; height: 46px; border-radius: 13px;
            background: var(--teal);
            display: grid; place-items: center; font-size: 1.3rem;
        }
        .panel-info .daftar { display: grid; gap: .9rem; margin-top: 2rem; position: relative; z-index: 1; }
        .panel-info .daftar .baris { display: flex; gap: .75rem; align-items: flex-start; font-size: .92rem; color: #D8E6F2; }
        .panel-info .daftar i { color: #7FE3D6; font-size: 1.05rem; margin-top: .1rem; }

        .kartu-masuk {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .08);
            padding: 2.4rem;
            width: 100%;
            max-width: 430px;
        }

        .form-label { font-size: .86rem; font-weight: 600; }
        .form-control {
            border-radius: 12px;
            border-color: var(--garis);
            padding: .7rem .9rem;
        }
        .form-control:focus { border-color: var(--teal); box-shadow: 0 0 0 .2rem rgba(14, 147, 132, .15); }
        .input-group .btn { border-color: var(--garis); border-radius: 0 12px 12px 0; color: var(--abu); }
        .input-group .form-control { border-radius: 12px 0 0 12px; }

        .btn-teal {
            background: var(--teal); border-color: var(--teal); color: #fff;
            font-weight: 700; border-radius: 12px; padding: .78rem 1rem;
        }
        .btn-teal:hover { background: var(--teal-gelap); border-color: var(--teal-gelap); color: #fff; }

        .tautan-kecil { color: var(--abu); text-decoration: none; font-size: .86rem; }
        .tautan-kecil:hover { color: var(--teal); }
    </style>
</head>
<body>

    <div class="container bingkai d-flex align-items-center py-4">
        <div class="row g-4 align-items-stretch w-100 justify-content-center">

            <div class="col-lg-6 d-none d-lg-block">
                <div class="panel-info d-flex flex-column">
                    <div class="d-flex align-items-center gap-3 position-relative" style="z-index: 1;">
                        <span class="logo"><i class="bi bi-hospital"></i></span>
                        <div>
                            <div class="fw-bold fs-5 lh-1">SIMRS dr. Tjahyo</div>
                            <small style="color: #9FB6CB;">Sistem Informasi Manajemen Rumah Sakit</small>
                        </div>
                    </div>

                    <div class="my-auto position-relative" style="z-index: 1;">
                        <h1 class="fw-bold mt-5 mb-3" style="font-size: 2rem; letter-spacing: -.02em;">
                            Satu sistem untuk pelayanan yang lebih cepat.
                        </h1>
                        <p style="color: #C8D9E8; max-width: 430px;">
                            Pendaftaran, rekam medis, dan farmasi terhubung dalam satu alur kerja
                            yang rapi — sehingga petugas bisa fokus melayani pasien.
                        </p>

                        <div class="daftar">
                            <div class="baris">
                                <i class="bi bi-shield-lock"></i>
                                <span>Akses sesuai peran: admin, registrasi, dokter, dan apoteker.</span>
                            </div>
                            <div class="baris">
                                <i class="bi bi-clock-history"></i>
                                <span>Setiap aktivitas masuk tercatat untuk keamanan data pasien.</span>
                            </div>
                            <div class="baris">
                                <i class="bi bi-file-medical"></i>
                                <span>Rekam medis digital tersimpan rapi dan mudah ditelusuri.</span>
                            </div>
                        </div>
                    </div>

                    <div class="small position-relative" style="color: #8FA6BD; z-index: 1;">
                        © {{ now()->year }} Rumah Sakit dr. Tjahyo
                    </div>
                </div>
            </div>

            <div class="col-lg-5 d-flex align-items-center justify-content-center">
                <div class="kartu-masuk">
                    <div class="d-lg-none d-flex align-items-center gap-2 mb-4">
                        <span class="d-grid rounded-3 text-white" style="width: 40px; height: 40px; background: var(--teal); place-items: center;">
                            <i class="bi bi-hospital"></i>
                        </span>
                        <div>
                            <div class="fw-bold lh-1">SIMRS dr. Tjahyo</div>
                            <small class="text-secondary">Masuk pegawai</small>
                        </div>
                    </div>

                    <h2 class="fs-4 fw-bold mb-1">Selamat datang kembali</h2>
                    <p class="text-secondary small mb-4">
                        Gunakan akun pegawai yang diberikan administrator rumah sakit.
                    </p>

                    @if (session('sukses'))
                        <div class="alert alert-success py-2 small">
                            <i class="bi bi-check-circle-fill me-1"></i>{{ session('sukses') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.attempt') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email pegawai</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="nama@rsdrjth.test" autocomplete="username" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Kata sandi</label>
                            <div class="input-group">
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••" autocomplete="current-password" required>
                                <button class="btn btn-outline-secondary" type="button" id="tombolSandi"
                                        aria-label="Tampilkan kata sandi">
                                    <i class="bi bi-eye" id="ikonSandi"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="ingat" value="1" id="ingat">
                                <label class="form-check-label small" for="ingat">Ingat saya</label>
                            </div>
                            <span class="small text-secondary">Lupa sandi? Hubungi admin</span>
                        </div>

                        <button type="submit" class="btn btn-teal w-100">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke SIMRS
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="tautan-kecil">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke halaman rumah sakit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const tombolSandi = document.getElementById('tombolSandi');
        const inputSandi = document.getElementById('password');
        const ikonSandi = document.getElementById('ikonSandi');

        tombolSandi?.addEventListener('click', () => {
            const tampil = inputSandi.type === 'password';
            inputSandi.type = tampil ? 'text' : 'password';
            ikonSandi.className = tampil ? 'bi bi-eye-slash' : 'bi bi-eye';
            tombolSandi.setAttribute('aria-label', tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    </script>
</body>
</html>
