@extends('layouts.app')

@section('title', 'Dashboard Registrasi')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Loket Registrasi</h1>
            <div class="sub">Antrian dan pendaftaran — {{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            @can('pasien.kelola')
                <a href="{{ route('pasien.create') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-person-plus me-1"></i>Pasien Baru
                </a>
            @endcan
            @can('kunjungan.kelola')
                <a href="{{ route('kunjungan.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Daftar Kunjungan
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4">
        @include('dashboard._stat', [
            'label' => 'Total Pasien',
            'nilai' => number_format($statistik['totalPasien']),
            'ikon' => 'bi-people',
        ])
        @include('dashboard._stat', [
            'label' => 'Kunjungan Hari Ini',
            'nilai' => number_format($statistik['kunjunganHariIni']),
            'ikon' => 'bi-calendar2-check',
            'latar' => '#EAF2FE',
            'warnaIkon' => '#2563EB',
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
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-list-ol me-1"></i>Antrian Hari Ini</span>
                    <a href="{{ route('kunjungan.index') }}" class="small text-decoration-none">Lihat semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Pasien</th>
                                    <th>Poli / Dokter</th>
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
                                        <td>
                                            <div>{{ $kunjungan->poli->nama }}</div>
                                            <small class="text-secondary">{{ $kunjungan->dokter->nama }}</small>
                                        </td>
                                        <td>
                                            @php($warna = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success'])
                                            <span class="badge text-bg-{{ $warna[$kunjungan->status->value] }}">{{ $kunjungan->status->label() }}</span>
                                        </td>
                                        <td class="text-end text-nowrap">
                                            @if ($kunjungan->status === \App\Enums\StatusKunjungan::Menunggu)
                                                @can('kunjungan.proses')
                                                    <form method="POST" action="{{ route('kunjungan.proses', $kunjungan) }}" class="d-inline">
                                                        @csrf
                                                        <button class="btn btn-sm btn-primary">Proses</button>
                                                    </form>
                                                @endcan
                                            @endif
                                            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">Belum ada antrian hari ini.</td>
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
                    <span class="fw-semibold"><i class="bi bi-person-lines-fill me-1"></i>Pasien Terbaru</span>
                    <a href="{{ route('pasien.index') }}" class="small text-decoration-none">Semua</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($pasienTerbaru as $pasien)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div style="min-width: 0;">
                                <div class="fw-semibold text-truncate">{{ $pasien->nama }}</div>
                                <small class="text-secondary">{{ $pasien->no_rm }}</small>
                            </div>
                            <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-sm btn-outline-secondary">Buka</a>
                        </li>
                    @empty
                        <li class="list-group-item text-secondary small">Belum ada data pasien.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
@endsection
