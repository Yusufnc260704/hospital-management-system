<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rumah Sakit dr. Tjahyo — Layanan Kesehatan untuk Keluarga Anda</title>
    <meta name="description" content="Rumah Sakit dr. Tjahyo melayani pemeriksaan kesehatan dengan rekam medis digital, tenaga medis berkompeten, dan IGD siaga 24 jam.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --teal: #0E9384;
            --teal-gelap: #0B7A6E;
            --tinta: #0F172A;
            --abu: #64748B;
            --garis: #E9F0F0;
            --lembut: #F6FAFA;
        }

        html { scroll-behavior: smooth; }
        section[id] { scroll-margin-top: 84px; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            color: var(--tinta);
            background: #fff;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h5 { font-weight: 800; letter-spacing: -0.02em; }
        .abu { color: var(--abu); }

        .nav-publik {
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid transparent;
            transition: border-color .2s ease, box-shadow .2s ease;
        }
        .nav-publik.terbayang {
            border-color: var(--garis);
            box-shadow: 0 8px 26px rgba(15, 23, 42, .05);
        }
        .nav-publik .nav-link { color: #334155; font-weight: 600; font-size: .94rem; }
        .nav-publik .nav-link:hover { color: var(--teal); }

        .btn-teal {
            background: var(--teal); border-color: var(--teal); color: #fff;
            font-weight: 600; border-radius: 999px; padding-inline: 1.4rem;
        }
        .btn-teal:hover { background: var(--teal-gelap); border-color: var(--teal-gelap); color: #fff; }
        .btn-tepi {
            border: 1px solid var(--garis); color: var(--tinta); font-weight: 600;
            border-radius: 999px; padding-inline: 1.4rem; background: #fff;
        }
        .btn-tepi:hover { border-color: #CBD5E1; background: var(--lembut); }

        .hero {
            background:
                radial-gradient(640px 320px at 92% 6%, #E4F7F4, transparent 62%),
                radial-gradient(520px 300px at 0% 100%, #EAF4FF, transparent 58%),
                #fff;
            padding: 5.5rem 0 4.5rem;
            overflow: hidden;
        }
        .hero h1 { font-size: clamp(2rem, 4.2vw, 3rem); line-height: 1.18; }
        .hero .sorot { color: var(--teal); }

        .lencana {
            display: inline-flex; align-items: center; gap: .5rem;
            background: #EAF8F6; color: var(--teal-gelap);
            border-radius: 999px; padding: .4rem .9rem;
            font-size: .82rem; font-weight: 700;
        }

        .tumpukan { position: relative; padding: 1rem 0; }
        .kartu-jadwal {
            background: #fff;
            border: 1px solid var(--garis);
            border-radius: 22px;
            box-shadow: 0 24px 60px rgba(15, 23, 42, .08);
            padding: 1.6rem;
        }
        .baris-jadwal {
            display: flex; align-items: center; gap: .85rem;
            padding: .7rem 0;
        }
        .baris-jadwal + .baris-jadwal { border-top: 1px dashed #E8EFEF; }
        .titik-ikon {
            width: 42px; height: 42px; border-radius: 12px;
            display: grid; place-items: center; font-size: 1.05rem; flex-shrink: 0;
        }
        .t-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }

        .chip-float {
            position: absolute; background: #fff; border: 1px solid var(--garis);
            border-radius: 999px; padding: .6rem 1rem;
            box-shadow: 0 14px 34px rgba(15, 23, 42, .10);
            font-size: .82rem; font-weight: 700;
            display: flex; align-items: center; gap: .5rem; z-index: 2;
        }

        .strip { border-top-bottom: none; border-block: 1px solid var(--garis); }
        .strip .angka { font-size: 1.45rem; font-weight: 800; }
        .strip .label { font-size: .8rem; color: var(--abu); }
        @media (min-width: 768px) {
            .strip-item + .strip-item { border-left: 1px solid var(--garis); }
        }

        .eyebrow {
            color: var(--teal);
            font-weight: 700; font-size: .76rem;
            letter-spacing: .12em; text-transform: uppercase;
            margin-bottom: .45rem;
        }

        .kartu {
            border: 1px solid var(--garis);
            border-radius: 18px;
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
            height: 100%;
            background: #fff;
        }
        .kartu:hover {
            transform: translateY(-5px);
            border-color: #D7E9E6;
            box-shadow: 0 18px 40px rgba(15, 23, 42, .07);
        }

        .bagian-lembut { background: var(--lembut); }

        .baris-jam {
            border: 1px solid var(--garis);
            border-radius: 16px;
            padding: 1.05rem 1.2rem;
            display: flex; align-items: center; gap: 1rem;
            background: #fff;
        }
        .baris-jam + .baris-jam { margin-top: .8rem; }
        .chip-waktu {
            font-size: .78rem; font-weight: 700; border-radius: 999px;
            padding: .35rem .8rem; white-space: nowrap;
        }

        .band-cta {
            background: linear-gradient(120deg, #0E9384, #0B7A6E 60%, #086C61);
            color: #fff;
            border-radius: 24px;
            padding: 2.8rem;
            position: relative;
            overflow: hidden;
        }
        .band-cta::after {
            content: '';
            position: absolute; right: -60px; top: -60px;
            width: 220px; height: 220px; border-radius: 50%;
            background: rgba(255, 255, 255, .10);
        }
        .band-cta::before {
            content: '';
            position: absolute; left: 40%; bottom: -90px;
            width: 260px; height: 260px; border-radius: 50%;
            background: rgba(255, 255, 255, .07);
        }

        .footer { background: #FBFDFD; border-top: 1px solid var(--garis); color: var(--abu); }
        .footer a { color: #475569; text-decoration: none; }
        .footer a:hover { color: var(--teal); }

        /* Animasi halus saat scroll */
        .reveal { opacity: 0; transform: translateY(18px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.tampil { opacity: 1; transform: none; }
        @media (prefers-reduced-motion: reduce) {
            html { scroll-behavior: auto; }
            .reveal { opacity: 1; transform: none; transition: none; }
        }
    </style>
</head>
<body id="beranda">

    <nav class="navbar navbar-expand-lg nav-publik sticky-top" id="navPublik">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/" style="color: var(--tinta);">
                <span class="d-inline-grid place-items-center rounded-3 text-white" style="width: 34px; height: 34px; background: var(--teal); place-items: center;">
                    <i class="bi bi-hospital"></i>
                </span>
                <span>RS dr. Tjahyo</span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#menuPublik">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menuPublik">
                <ul class="navbar-nav mx-auto mb-3 mb-lg-0 gap-lg-1">
                    <li class="nav-item"><a class="nav-link" href="/">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#layanan">Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#keunggulan">Keunggulan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#jam-layanan">Jam Layanan</a></li>
                    <li class="nav-item"><a class="nav-link" href="#kontak">Kontak</a></li>
                </ul>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-teal btn-sm">Buka Dashboard</a>
                @endauth
                @guest
                    <a href="{{ route('login') }}" class="btn btn-teal btn-sm">Masuk Pegawai</a>
                @endguest
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <span class="lencana"><i class="bi bi-heart-pulse"></i> Melayani dengan sepenuh hati</span>

                    <h1 class="mt-3 mb-3">
                        Kesehatan keluarga Anda,<br>
                        <span class="sorot">prioritas kami.</span>
                    </h1>

                    <p class="mb-4 abu" style="font-size: 1.06rem; max-width: 480px;">
                        Rumah Sakit dr. Tjahyo — layanan yang ramah dengan teknologi yang memudahkan:
                        daftar lebih cepat, riwayat medis rapi, obat terpantau.
                    </p>

                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('login') }}" class="btn btn-teal py-2">
                            <i class="bi bi-calendar2-check me-2"></i>Daftar Kunjungan
                        </a>
                        <a href="#layanan" class="btn btn-tepi py-2">Lihat Layanan</a>
                    </div>

                    <div class="d-flex align-items-center gap-2 mt-4 small abu">
                        <i class="bi bi-shield-check" style="color: var(--teal);"></i>
                        Data pasien tersimpan aman di rekam medis digital
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="tumpukan ms-lg-3">
                        <div class="kartu-jadwal">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong class="fs-6">Jadwal & Antrian Hari Ini</strong>
                                <span class="badge rounded-pill" style="background: #EAF8F6; color: var(--teal-gelap);">Live</span>
                            </div>

                            <div class="baris-jadwal">
                                <div class="titik-ikon" style="background: #EAF8F6; color: var(--teal);"><i class="bi bi-clipboard2-pulse"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Poli Umum</div>
                                    <div class="abu" style="font-size: .78rem;">dr. Andi Prasetyo</div>
                                </div>
                                <span class="chip-waktu" style="background: #EAF8F6; color: var(--teal-gelap);">08.00</span>
                            </div>

                            <div class="baris-jadwal">
                                <div class="titik-ikon" style="background: #EAF2FE; color: #2563EB;"><i class="bi bi-balloon-heart"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Poli Anak</div>
                                    <div class="abu" style="font-size: .78rem;">dr. Siti Rahmawati, Sp.A</div>
                                </div>
                                <span class="chip-waktu" style="background: #EAF2FE; color: #1D4ED8;">09.30</span>
                            </div>

                            <div class="baris-jadwal">
                                <div class="titik-ikon" style="background: #FDF2F8; color: #DB2777;"><i class="bi bi-activity"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-semibold small">Poli Penyakit Dalam</div>
                                    <div class="abu" style="font-size: .78rem;">dr. Rahmat Hidayat, Sp.PD</div>
                                </div>
                                <span class="chip-waktu" style="background: #FDF2F8; color: #BE185D;">10.15</span>
                            </div>

                            <div class="text-center abu mt-2" style="font-size: .78rem;">
                                Semua pendaftaran tercatat otomatis di sistem
                            </div>
                        </div>

                        <div class="chip-float" style="top: -14px; right: 14px;">
                            <span class="t-dot" style="background: #F59E0B;"></span> IGD Siaga 24 Jam
                        </div>
                        <div class="chip-float" style="bottom: -16px; left: 18px;">
                            <i class="bi bi-check-circle-fill" style="color: var(--teal);"></i> Antrian digital
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="strip">
        <div class="container">
            <div class="row text-center text-md-start py-3 g-0">
                <div class="strip-item col-6 col-md-3 py-3 px-3">
                    <div class="angka">{{ $totalDokter }}</div>
                    <div class="label">Dokter & spesialis</div>
                </div>
                <div class="strip-item col-6 col-md-3 py-3 px-3">
                    <div class="angka">{{ $totalPoli }}</div>
                    <div class="label">Poli aktif</div>
                </div>
                <div class="strip-item col-6 col-md-3 py-3 px-3">
                    <div class="angka">24/7</div>
                    <div class="label">IGD siaga</div>
                </div>
                <div class="strip-item col-6 col-md-3 py-3 px-3">
                    <div class="angka">100%</div>
                    <div class="label">Rekam medis digital</div>
                </div>
            </div>
        </div>
    </div>

    <section id="layanan" class="py-5">
        <div class="container py-4">
            <div class="text-center mb-5 reveal">
                <div class="eyebrow">Layanan</div>
                <h2>Poli yang siap membantu Anda</h2>
                <p class="abu mx-auto" style="max-width: 520px;">
                    Datang langsung ke loket pendaftaran, atau gunakan pendaftaran online
                    untuk pengalaman yang lebih cepat.
                </p>
            </div>

            <div class="row g-4">
                @forelse ($polis as $poli)
                    @php
                        $gaya = match (true) {
                            str_contains($poli->nama, 'Gigi') => ['bi-emoji-laughing', '#FDF4E7', '#B45309'],
                            str_contains($poli->nama, 'Anak') => ['bi-balloon-heart', '#EAF2FE', '#2563EB'],
                            str_contains($poli->nama, 'Umum') => ['bi-clipboard2-pulse', '#EAF8F6', '#0E9384'],
                            str_contains($poli->nama, 'Dalam') => ['bi-activity', '#FDF2F8', '#DB2777'],
                            default => ['bi-heart-pulse', '#EAF8F6', '#0E9384'],
                        };
                    @endphp
                    <div class="col-12 col-md-6 col-lg-3 reveal">
                        <div class="kartu">
                            <div class="p-4">
                                <div class="titik-ikon mb-3" style="background: {{ $gaya[1] }}; color: {{ $gaya[2] }}; width: 52px; height: 52px; border-radius: 15px; font-size: 1.35rem;">
                                    <i class="bi {{ $gaya[0] }}"></i>
                                </div>
                                <h5 class="fs-6 fw-bold mb-1">{{ $poli->nama }}</h5>
                                <p class="abu small mb-3">{{ $poli->deskripsi ?: 'Layanan kesehatan oleh dokter berpengalaman.' }}</p>
                                <span class="badge rounded-pill fw-semibold" style="background: {{ $gaya[1] }}; color: {{ $gaya[2] }};">
                                    {{ $poli->dokter_count }} dokter
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center abu">Daftar poli akan segera tersedia.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section id="keunggulan" class="py-5 bagian-lembut">
        <div class="container py-4">
            <div class="text-center mb-5 reveal">
                <div class="eyebrow">Keunggulan</div>
                <h2>Nyaman dari pendaftaran sampai pulang</h2>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="kartu p-4 h-100">
                        <div class="titik-ikon mb-3" style="background: #EAF8F6; color: var(--teal);"><i class="bi bi-folder2-open"></i></div>
                        <h6 class="fw-bold">Rekam Medis Digital</h6>
                        <p class="abu small mb-0">Riwayat berobat tersusun rapi dan mudah diakses dokter.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="kartu p-4 h-100">
                        <div class="titik-ikon mb-3" style="background: #EAF2FE; color: #2563EB;"><i class="bi bi-people"></i></div>
                        <h6 class="fw-bold">Antrian Digital</h6>
                        <p class="abu small mb-0">Nomor antrean otomatis, waktu tunggu lebih singkat.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="kartu p-4 h-100">
                        <div class="titik-ikon mb-3" style="background: #FDF4E7; color: #B45309;"><i class="bi bi-capsule"></i></div>
                        <h6 class="fw-bold">Farmasi Terpantau</h6>
                        <p class="abu small mb-0">Stok obat selalu dipantau agar resep langsung tersedia.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3 reveal">
                    <div class="kartu p-4 h-100">
                        <div class="titik-ikon mb-3" style="background: #FDF2F8; color: #DB2777;"><i class="bi bi-person-hearts"></i></div>
                        <h6 class="fw-bold">Dokter Ramah</h6>
                        <p class="abu small mb-0">Didampingi tenaga medis yang sabar dan komunikatif.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="jam-layanan" class="py-5">
        <div class="container py-4">
            <div class="row g-5 align-items-start">
                <div class="col-lg-5 reveal">
                    <div class="eyebrow">Jam Layanan</div>
                    <h2 class="mb-3">Kapan kami bisa membantu?</h2>
                    <p class="abu mb-0">
                        Datang sesuai jam praktik untuk pemeriksaan rutin — atau kapan pun
                        untuk keadaan darurat. Kami siap.
                    </p>
                </div>

                <div class="col-lg-7 reveal">
                    <div class="baris-jam">
                        <div class="titik-ikon" style="background: #EAF8F6; color: var(--teal);"><i class="bi bi-hospital"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Poli rawat jalan</div>
                            <div class="abu" style="font-size: .8rem;">Senin — Sabtu</div>
                        </div>
                        <span class="chip-waktu" style="background: #EAF8F6; color: var(--teal-gelap);">08.00 – 16.00 WIB</span>
                    </div>

                    <div class="baris-jam">
                        <div class="titik-ikon" style="background: #FEE2E2; color: #DC2626;"><i class="bi bi-heart-pulse"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">IGD (gawat darurat)</div>
                            <div class="abu" style="font-size: .8rem;">Setiap hari, tanpa libur</div>
                        </div>
                        <span class="chip-waktu" style="background: #FEE2E2; color: #B91C1C;">24 Jam</span>
                    </div>

                    <div class="baris-jam">
                        <div class="titik-ikon" style="background: #FEF3C7; color: #B45309;"><i class="bi bi-person-rolodex"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold">Loket pendaftaran</div>
                            <div class="abu" style="font-size: .8rem;">Senin — Sabtu</div>
                        </div>
                        <span class="chip-waktu" style="background: #FEF3C7; color: #92400E;">07.30 – 15.30 WIB</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="pb-5">
        <div class="container">
            <div class="band-cta reveal">
                <div class="row align-items-center position-relative" style="z-index: 1;">
                    <div class="col-lg-8">
                        <h2 class="fs-3 mb-2">Butuh bantuan cepat?</h2>
                        <p class="mb-0" style="opacity: .92;">
                            IGD kami siaga 24 jam. Untuk keadaan darurat, silakan hubungi
                            atau datang langsung ke rumah sakit.
                        </p>
                    </div>
                    <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                        <a href="tel:+62274123456" class="btn btn-light fw-semibold px-4 py-2 rounded-pill">
                            <i class="bi bi-telephone-fill me-2"></i>(0274) 123-456
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer pt-5 pb-4">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-2 fw-bold" style="color: var(--tinta);">
                        <span class="d-inline-grid rounded-3 text-white" style="width: 30px; height: 30px; background: var(--teal); place-items: center;">
                            <i class="bi bi-hospital"></i>
                        </span>
                        RS dr. Tjahyo
                    </div>
                    <p class="small mb-0">
                        Rawat jalan, gawat darurat, dan rekam medis digital untuk
                        pelayanan yang lebih akurat dan ramah.
                    </p>
                </div>

                <div class="col-6 col-lg-2">
                    <h6 class="fw-bold mb-3" style="color: var(--tinta);">Jelajah</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        <li><a href="/">Beranda</a></li>
                        <li><a href="#layanan">Layanan</a></li>
                        <li><a href="#jam-layanan">Jam Layanan</a></li>
                        <li><a href="{{ route('login') }}">Masuk Pegawai</a></li>
                    </ul>
                </div>

                <div class="col-6 col-lg-3">
                    <h6 class="fw-bold mb-3" style="color: var(--tinta);">Poli Kami</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        @forelse ($polis->take(4) as $poli)
                            <li><a href="#layanan">{{ $poli->nama }}</a></li>
                        @empty
                            <li><a href="#layanan">Layanan segera hadir</a></li>
                        @endforelse
                    </ul>
                </div>

                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3" style="color: var(--tinta);">Kontak</h6>
                    <ul class="list-unstyled small d-grid gap-2">
                        <li><i class="bi bi-geo-alt me-2"></i>Jl. Kesehatan Raya No. 17, Yogyakarta</li>
                        <li><i class="bi bi-telephone me-2"></i>(0274) 123-456</li>
                        <li><i class="bi bi-envelope me-2"></i>info@rsdrjth.test</li>
                    </ul>
                </div>
            </div>

            <hr class="my-4" style="border-color: var(--garis);">

            <div class="d-flex justify-content-between flex-wrap gap-2 small">
                <span>© {{ now()->year }} Rumah Sakit dr. Tjahyo. Semua hak dilindungi.</span>
                <span>SIMRS dibangun dengan Laravel {{ app()->version() }}</span>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const nav = document.getElementById('navPublik');
        const perbaruiNav = () => nav.classList.toggle('terbayang', window.scrollY > 8);
        window.addEventListener('scroll', perbaruiNav, { passive: true });
        perbaruiNav();

        const elemen = document.querySelectorAll('.reveal');
        const pengamat = new IntersectionObserver((daftar) => {
            daftar.forEach((entri) => {
                if (entri.isIntersecting) {
                    entri.target.classList.add('tampil');
                    pengamat.unobserve(entri.target);
                }
            });
        }, { threshold: 0.12 });

        elemen.forEach((el) => pengamat.observe(el));
    </script>
</body>
</html>
