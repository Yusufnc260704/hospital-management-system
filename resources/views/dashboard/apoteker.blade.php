@extends('layouts.app')

@section('title', 'Dashboard Farmasi')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Farmasi</h1>
            <div class="sub">Stok obat dan resep — {{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-capsule me-1"></i>Daftar Obat
            </a>
            @can('obat.kelola')
                <a href="{{ route('obat.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Obat Baru
                </a>
            @endcan
        </div>
    </div>

    <div class="row g-3 mb-4">
        @include('dashboard._stat', [
            'label' => 'Jenis Obat',
            'nilai' => number_format($statistik['totalObat']),
            'ikon' => 'bi-capsule',
        ])
        @include('dashboard._stat', [
            'label' => 'Stok Kritis',
            'nilai' => number_format($statistik['stokKritis']),
            'ikon' => 'bi-exclamation-triangle',
            'latar' => '#FEE2E2',
            'warnaIkon' => '#B91C1C',
            'kelas' => $statistik['stokKritis'] > 0 ? 'text-danger' : '',
        ])
        @include('dashboard._stat', [
            'label' => 'Resep Hari Ini',
            'nilai' => number_format($statistik['resepHariIni']),
            'ikon' => 'bi-file-medical',
            'latar' => '#EAF2FE',
            'warnaIkon' => '#2563EB',
        ])
        @include('dashboard._stat', [
            'label' => 'Nilai Stok',
            'nilai' => 'Rp'.number_format($statistik['nilaiStok'], 0, ',', '.'),
            'ikon' => 'bi-cash-stack',
            'latar' => '#DCFCE7',
            'warnaIkon' => '#15803D',
        ])
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-exclamation-triangle me-1"></i>Perlu Restok</span>
                    <a href="{{ route('obat.index', ['stok' => 'kritis']) }}" class="small text-decoration-none">Kelola</a>
                </div>
                <ul class="list-group list-group-flush">
                    @forelse ($stokKritis as $obat)
                        <li class="list-group-item d-flex justify-content-between align-items-center gap-2">
                            <div style="min-width: 0;">
                                <div class="fw-semibold text-truncate">{{ $obat->nama }}</div>
                                <small class="text-secondary">{{ ucfirst($obat->jenis ?: 'obat') }} · min {{ $obat->stok_minimum }} {{ $obat->satuan }}</small>
                            </div>
                            <span class="badge text-bg-danger">{{ $obat->stok }} {{ $obat->satuan }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-secondary small">Semua stok obat aman.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-prescription2 me-1"></i>Resep Terbaru</span>
                    <a href="{{ route('rekam-medis.index') }}" class="small text-decoration-none">Semua rekam medis</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th>Pasien</th><th>Dokter</th><th>Resep</th><th></th></tr>
                            </thead>
                            <tbody>
                                @forelse ($resepTerbaru as $rekam)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">{{ $rekam->kunjungan?->pasien?->nama }}</div>
                                            <small class="text-secondary">{{ $rekam->kunjungan?->pasien?->no_rm }}</small>
                                        </td>
                                        <td class="small">{{ $rekam->kunjungan?->dokter?->nama }}</td>
                                        <td class="small">{{ \Illuminate\Support\Str::limit($rekam->resep, 50) }}</td>
                                        <td class="text-end">
                                            @can('rekam-medis.lihat')
                                                <a href="{{ route('rekam-medis.show', $rekam) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">Belum ada resep tercatat.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
