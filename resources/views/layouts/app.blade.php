<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') — SIMRS dr. Tjahyo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --sidebar: #0B1F33;
            --sidebar-lembut: #122A44;
            --teal: #0E9384;
            --tinta: #0F172A;
            --abu: #64748B;
            --garis: #E7EEF6;
        }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            background-color: #F3F7FB;
            color: var(--tinta);
            min-height: 100vh;
        }

        .sidebar {
            width: 252px;
            min-height: 100vh;
            background: var(--sidebar);
            color: #C7D5E4;
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
        }
        .sidebar .brand {
            display: flex;
            align-items: center;
            gap: .65rem;
            color: #fff;
            text-decoration: none;
            padding: .35rem .25rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, .08);
            margin-bottom: 1rem;
        }
        .sidebar .brand .logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: var(--teal);
            display: grid;
            place-items: center;
            font-size: 1.15rem;
            color: #fff;
            flex-shrink: 0;
        }
        .sidebar .brand small { color: #8FA6BD; }

        .sidebar .judul-seksi {
            font-size: .68rem;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #6F88A1;
            font-weight: 700;
            padding: .9rem .75rem .35rem;
        }

        .sidebar .nav-link {
            color: #C7D5E4;
            border-radius: .6rem;
            padding: .55rem .75rem;
            font-size: .92rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: .65rem;
        }
        .sidebar .nav-link i { font-size: 1.05rem; opacity: .85; width: 20px; text-align: center; }
        .sidebar .nav-link:hover { background: rgba(255, 255, 255, .07); color: #fff; }
        .sidebar .nav-link.active { background: var(--teal); color: #fff; }
        .sidebar .nav-link.active i { opacity: 1; }

        .sidebar .pengguna-aktif {
            border-top: 1px solid rgba(255, 255, 255, .08);
            padding-top: .9rem;
            font-size: .85rem;
        }
        .sidebar .pengguna-aktif .nama { color: #fff; font-weight: 600; }
        .sidebar .pengguna-aktif .peran { color: #8FA6BD; font-size: .76rem; }

        .konten { min-width: 0; }

        .card { border: 1px solid var(--garis); border-radius: 14px; }
        .card-stat .card-body { padding: 1.1rem 1.2rem; }
        .card-stat .label { color: var(--abu); font-size: .8rem; font-weight: 500; }
        .card-stat .angka { font-size: 1.7rem; font-weight: 800; line-height: 1.1; letter-spacing: -.02em; }
        .card-stat .ikon {
            width: 42px; height: 42px; border-radius: 11px;
            display: grid; place-items: center; font-size: 1.2rem; flex-shrink: 0;
        }

        .kepala-halaman { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem; justify-content: space-between; margin-bottom: 1.4rem; }
        .kepala-halaman h1 { font-size: 1.5rem; font-weight: 800; letter-spacing: -.02em; margin: 0; }
        .kepala-halaman .sub { color: var(--abu); font-size: .88rem; }

        .table > :not(caption) > * > * { padding: .7rem .85rem; }
        .table thead th { font-size: .76rem; text-transform: uppercase; letter-spacing: .05em; color: var(--abu); font-weight: 700; }

        @media (max-width: 991.98px) {
            body { flex-direction: column !important; }
            .sidebar { width: 100%; min-height: auto; position: static; }
        }
    </style>
</head>
<body class="d-flex">

    @php
        $pengguna = auth()->user();
        $seksi = [
            [
                'judul' => null,
                'item' => [
                    ['rute' => 'dashboard', 'pola' => 'dashboard', 'ikon' => 'bi-grid-1x2', 'label' => 'Dashboard', 'izin' => null],
                    ['rute' => 'pasien.index', 'pola' => 'pasien*', 'ikon' => 'bi-people', 'label' => 'Pasien', 'izin' => 'pasien.lihat'],
                    ['rute' => 'kunjungan.index', 'pola' => 'kunjungan*', 'ikon' => 'bi-clipboard2-plus', 'label' => 'Pendaftaran', 'izin' => 'kunjungan.lihat'],
                    ['rute' => 'rekam-medis.index', 'pola' => 'rekam-medis*', 'ikon' => 'bi-file-medical', 'label' => 'Rekam Medis', 'izin' => 'rekam-medis.lihat'],
                    ['rute' => 'obat.index', 'pola' => 'obat*', 'ikon' => 'bi-capsule', 'label' => 'Farmasi', 'izin' => 'obat.lihat'],
                ],
            ],
            [
                'judul' => 'Master Data',
                'item' => [
                    ['rute' => 'dokter.index', 'pola' => 'dokter*', 'ikon' => 'bi-person-badge', 'label' => 'Dokter', 'izin' => 'dokter.kelola'],
                    ['rute' => 'poli.index', 'pola' => 'poli*', 'ikon' => 'bi-building', 'label' => 'Poli', 'izin' => 'poli.kelola'],
                ],
            ],
            [
                'judul' => 'Administrasi',
                'item' => [
                    ['rute' => 'pengguna.index', 'pola' => 'pengguna*', 'ikon' => 'bi-person-gear', 'label' => 'Pengguna', 'izin' => 'pengguna.kelola'],
                    ['rute' => 'log-login.index', 'pola' => 'log-login*', 'ikon' => 'bi-shield-check', 'label' => 'Log Login', 'izin' => 'log-login.lihat'],
                    ['rute' => 'audit-log.index', 'pola' => 'audit-log*', 'ikon' => 'bi-journal-text', 'label' => 'Audit Trail', 'izin' => 'audit.lihat'],
                ],
            ],
        ];
    @endphp

    <nav class="sidebar p-3">
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="logo"><i class="bi bi-hospital"></i></span>
            <span>
                <span class="fw-bold d-block lh-1">SIMRS</span>
                <small>RS dr. Tjahyo</small>
            </span>
        </a>

        <div class="d-flex flex-column gap-1 flex-grow-1">
            @foreach ($seksi as $grup)
                @php
                    $item = collect($grup['item'])
                        ->filter(fn ($menu) => $menu['izin'] === null || $pengguna?->can($menu['izin']))
                        ->values();
                @endphp

                @if ($item->isNotEmpty())
                    @if ($grup['judul'])
                        <div class="judul-seksi">{{ $grup['judul'] }}</div>
                    @endif

                    @foreach ($item as $menu)
                        <a href="{{ route($menu['rute']) }}"
                           class="nav-link {{ request()->routeIs($menu['pola']) ? 'active' : '' }}">
                            <i class="bi {{ $menu['ikon'] }}"></i>
                            {{ $menu['label'] }}
                        </a>
                    @endforeach
                @endif
            @endforeach
        </div>

        <div class="pengguna-aktif mt-3">
            <div class="d-flex align-items-center gap-2 mb-2">
                <span class="d-grid place-items-center rounded-circle bg-secondary bg-opacity-25" style="width: 34px; height: 34px; place-items: center;">
                    <i class="bi bi-person"></i>
                </span>
                <div style="min-width: 0;">
                    <div class="nama text-truncate">{{ $pengguna?->name }}</div>
                    <div class="peran text-truncate">{{ $pengguna?->labelRole() }}</div>
                </div>
            </div>

            @if ($pengguna && ! $pengguna->is_active)
                <div class="badge text-bg-warning w-100 mb-2">Akun nonaktif</div>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="d-grid gap-1">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light text-start">
                    <i class="bi bi-box-arrow-right me-2"></i>Keluar
                </button>
            </form>
            <a href="{{ route('home') }}" class="btn btn-sm btn-link text-secondary text-start text-decoration-none mt-1 px-0">
                <i class="bi bi-globe2 me-2"></i>Situs Publik
            </a>
        </div>
    </nav>

    <main class="konten p-3 p-lg-4 flex-grow-1">
        @if (session('sukses'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle-fill me-1"></i>{{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if (session('gagal'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>{{ session('gagal') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>Ada yang perlu diperbaiki:</strong>
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
