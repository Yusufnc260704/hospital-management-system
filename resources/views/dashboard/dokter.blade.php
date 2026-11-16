@extends('layouts.app')

@section('title', 'Dashboard Dokter')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Selamat bertugas, {{ auth()->user()?->name }}</h1>
            <div class="sub">
                @if ($dokter)
                    {{ $dokter->spesialisasi }} — {{ $dokter->poli?->nama }}
                @else
                    Jadwal dan pasien Anda hari ini
                @endif
            </div>
        </div>
        <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-list-ul me-1"></i>Semua Kunjungan
        </a>
    </div>

    @if ($dokter === null)
        <div class="alert alert-warning">
            <i class="bi bi-exclamation-triangle-fill me-1"></i>
            Akun ini belum ditautkan ke data dokter. Hubungi administrator agar akun terhubung
            ke jadwal praktik Anda.
        </div>
    @endif

    <div class="row g-3 mb-4">
        @include('dashboard._stat', [
            'label' => 'Pasien Hari Ini',
            'nilai' => number_format($statistik['kunjunganHariIni']),
            'ikon' => 'bi-calendar2-check',
        ])
        @include('dashboard._stat', [
            'label' => 'Menunggu',
            'nilai' => number_format($statistik['menungguHariIni']),
            'ikon' => 'bi-hourglass-split',
            'latar' => '#FEF3C7',
            'warnaIkon' => '#B45309',
            'kelas' => 'text-warning',
        ])
        @include('dashboard._stat', [
            'label' => 'Sedang Ditangani',
            'nilai' => number_format($statistik['diprosesHariIni']),
            'ikon' => 'bi-activity',
            'latar' => '#EAF2FE',
            'warnaIkon' => '#2563EB',
        ])
        @include('dashboard._stat', [
            'label' => 'Selesai',
            'nilai' => number_format($statistik['selesaiHariIni']),
            'ikon' => 'bi-check2-circle',
            'latar' => '#DCFCE7',
            'warnaIkon' => '#15803D',
            'kelas' => 'text-success',
        ])
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="bi bi-person-check me-1"></i>Pasien Saya Hari Ini
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli</th>
                                    <th>Keluhan</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($antrian as $kunjungan)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $kunjungan->pasien->nama }}</div>
                                            <small class="text-secondary">{{ $kunjungan->pasien->no_rm }}</small>
                                        </td>
                                        <td class="small">{{ $kunjungan->poli->nama }}</td>
                                        <td class="small">{{ \Illuminate\Support\Str::limit($kunjungan->keluhan, 40) }}</td>
                                        <td>
                                            @php($warna = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success'])
                                            <span class="badge text-bg-{{ $warna[$kunjungan->status->value] }}">{{ $kunjungan->status->label() }}</span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            @if ($kunjungan->status === \App\Enums\StatusKunjungan::Menunggu)
                                                @can('kunjungan.proses')
                                                    <form method="POST" action="{{ route('kunjungan.proses', $kunjungan) }}" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-primary">Mulai</button>
                                                    </form>
                                                @endcan
                                            @elseif ($kunjungan->status === \App\Enums\StatusKunjungan::Diproses)
                                                @can('rekam-medis.kelola')
                                                    <a href="{{ route('rekam-medis.create', ['kunjungan_id' => $kunjungan->id]) }}" class="btn btn-sm btn-success">
                                                        Isi Rekam Medis
                                                    </a>
                                                @endcan
                                            @elseif ($kunjungan->rekamMedis)
                                                @can('rekam-medis.lihat')
                                                    <a href="{{ route('rekam-medis.show', $kunjungan->rekamMedis) }}" class="btn btn-sm btn-outline-secondary">Rekam Medis</a>
                                                @endcan
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-secondary py-4">
                                            Tidak ada pasien untuk Anda hari ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-file-medical me-1"></i>Rekam Medis Terbaru</span>
                    <a href="{{ route('rekam-medis.index') }}" class="small text-decoration-none">Semua</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($rekamMedisTerbaru as $rekam)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start gap-2">
                                <div style="min-width: 0;">
                                    <div class="fw-semibold text-truncate">{{ $rekam->kunjungan?->pasien?->nama }}</div>
                                    <small class="text-secondary d-block text-truncate">{{ $rekam->diagnosa }}</small>
                                </div>
                                <a href="{{ route('rekam-medis.show', $rekam) }}" class="btn btn-sm btn-outline-secondary">Buka</a>
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-secondary small">Belum ada rekam medis.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
