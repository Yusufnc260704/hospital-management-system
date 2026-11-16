@extends('layouts.app')

@section('title', 'Dashboard Administrator')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Dashboard</h1>
            <div class="sub">Ringkasan operasional — {{ now()->isoFormat('dddd, D MMMM Y') }}</div>
        </div>
        <div class="d-flex gap-2">
            @can('kunjungan.kelola')
                <a href="{{ route('kunjungan.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i>Daftar Kunjungan
                </a>
            @endcan
            <a href="{{ route('pengguna.create') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-person-plus me-1"></i>Pengguna
            </a>
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
            'label' => 'Belum Dilayani',
            'nilai' => number_format($statistik['menungguHariIni']),
            'ikon' => 'bi-hourglass-split',
            'latar' => '#FEF3C7',
            'warnaIkon' => '#B45309',
            'kelas' => 'text-warning',
        ])
        @include('dashboard._stat', [
            'label' => 'Selesai Hari Ini',
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
                    <span class="fw-semibold"><i class="bi bi-calendar2-week me-1"></i>Kunjungan Hari Ini</span>
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
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kunjunganHariIni as $kunjungan)
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
                                        <td class="text-end">
                                            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn btn-sm btn-outline-secondary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-secondary py-4">Belum ada kunjungan hari ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 d-flex flex-column gap-3">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-building me-1"></i>Kunjungan per Poli</div>
                <div class="card-body">
                    @php($maks = max(1, (int) $poliKunjunganHariIni->max('kunjungan_count')))
                    @forelse ($poliKunjunganHariIni as $poli)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ $poli->nama }}</span>
                                <strong>{{ $poli->kunjungan_count }}</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar" style="width: {{ ($poli->kunjungan_count / $maks) * 100 }}%; background: var(--teal);"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-secondary small">Belum ada data poli.</div>
                    @endforelse
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-capsule me-1"></i>Stok Obat Kritis</div>
                <ul class="list-group list-group-flush">
                    @forelse ($obatStokKritis as $obat)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span class="small">{{ $obat->nama }}</span>
                            <span class="badge text-bg-danger">{{ $obat->stok }} / min {{ $obat->stok_minimum }}</span>
                        </li>
                    @empty
                        <li class="list-group-item text-secondary small">Semua stok obat aman.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <div class="row g-3 mt-0">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white fw-semibold"><i class="bi bi-pie-chart me-1"></i>Data Master</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span>Dokter</span><strong>{{ $statistik['totalDokter'] }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Poli</span><strong>{{ $statistik['totalPoli'] }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Akun pengguna</span><strong>{{ $statistik['totalPengguna'] }}</strong></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-semibold"><i class="bi bi-shield-check me-1"></i>Aktivitas Login Terbaru</span>
                    <div class="d-flex gap-3">
                        <a href="{{ route('log-login.index') }}" class="small text-decoration-none">Buka log</a>
                        <a href="{{ route('audit-log.index') }}" class="small text-decoration-none">Audit trail</a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="table-light">
                                <tr><th>Email</th><th>Status</th><th>Waktu</th><th>IP</th></tr>
                            </thead>
                            <tbody>
                                @forelse ($logTerbaru as $log)
                                    <tr>
                                        <td class="small">{{ $log->email }}</td>
                                        <td>
                                            <span class="badge text-bg-{{ $log->warnaStatus() }}">{{ $log->labelStatus() }}</span>
                                        </td>
                                        <td class="small text-nowrap">{{ $log->created_at?->format('d/m H:i') }}</td>
                                        <td class="small">{{ $log->ip_address }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-secondary small py-3">Belum ada aktivitas login.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
