@extends('layouts.app')

@section('title', 'Detail Kunjungan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Kunjungan #{{ $kunjungan->id }}</h1>
        <div class="d-flex gap-2 flex-wrap">
            @if ($kunjungan->status === \App\Enums\StatusKunjungan::Menunggu)
                @can('kunjungan.proses')
                    <form method="POST" action="{{ route('kunjungan.proses', $kunjungan) }}">
                        @csrf
                        <button class="btn btn-primary">
                            <i class="bi bi-play-fill"></i> Mulai Proses
                        </button>
                    </form>
                @endcan
            @endif

            @can('kunjungan.kelola')
                <form method="POST" action="{{ route('kunjungan.selesai', $kunjungan) }}"
                      onsubmit="return confirm('Tandai kunjungan ini selesai?')">
                    @csrf
                    <button class="btn btn-success" @if ($kunjungan->status === \App\Enums\StatusKunjungan::Selesai || $kunjungan->rekamMedis !== null) disabled title="Sudah selesai" @endif>
                        <i class="bi bi-check2-circle"></i> Tandai Selesai
                    </button>
                </form>
                <a href="{{ route('kunjungan.edit', $kunjungan) }}" class="btn btn-outline-secondary">Edit</a>
                <form method="POST" action="{{ route('kunjungan.destroy', $kunjungan) }}"
                      onsubmit="return confirm('Hapus kunjungan #{{ $kunjungan->id }}?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">Hapus</button>
                </form>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Pasien</dt>
                <dd class="col-md-9"><a href="{{ route('pasien.show', $kunjungan->pasien) }}">{{ $kunjungan->pasien->nama }}</a> ({{ $kunjungan->pasien->no_rm }})</dd>
                <dt class="col-md-3">Poli</dt>
                <dd class="col-md-9">{{ $kunjungan->poli->nama }}</dd>
                <dt class="col-md-3">Dokter</dt>
                <dd class="col-md-9">{{ $kunjungan->dokter->nama }}</dd>
                <dt class="col-md-3">Tanggal</dt>
                <dd class="col-md-9">{{ $kunjungan->tanggal_kunjungan->isoFormat('dddd, D MMMM Y') }}</dd>
                <dt class="col-md-3">Keluhan</dt>
                <dd class="col-md-9">{{ $kunjungan->keluhan }}</dd>
                <dt class="col-md-3">Status</dt>
                <dd class="col-md-9">
                    @php($warna = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success'])
                    <span class="badge bg-{{ $warna[$kunjungan->status->value] }}">{{ $kunjungan->status->label() }}</span>
                </dd>
            </dl>
        </div>
    </div>

    @can('rekam-medis.lihat')
        <div class="card shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-semibold"><i class="bi bi-file-medical me-1"></i>Rekam Medis</span>
                @can('rekam-medis.kelola')
                    @unless ($kunjungan->rekamMedis)
                        @unless ($kunjungan->status === \App\Enums\StatusKunjungan::Menunggu)
                            <a href="{{ route('rekam-medis.create', ['kunjungan_id' => $kunjungan->id]) }}" class="btn btn-primary btn-sm">+ Buat Rekam Medis</a>
                        @endunless
                    @endunless
                @endcan
            </div>

            @if ($kunjungan->rekamMedis)
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-md-3">Diagnosa</dt><dd class="col-md-9">{{ $kunjungan->rekamMedis->diagnosa }}</dd>
                        <dt class="col-md-3">Tindakan</dt><dd class="col-md-9">{{ $kunjungan->rekamMedis->tindakan ?: '-' }}</dd>
                        <dt class="col-md-3">Resep</dt><dd class="col-md-9">{{ $kunjungan->rekamMedis->resep ?: '-' }}</dd>
                        <dt class="col-md-3">Catatan</dt><dd class="col-md-9">{{ $kunjungan->rekamMedis->catatan ?: '-' }}</dd>
                    </dl>
                    <a href="{{ route('rekam-medis.show', $kunjungan->rekamMedis) }}" class="btn btn-sm btn-outline-primary mt-3">Buka Detail Rekam Medis</a>
                </div>
            @else
                <div class="card-body text-secondary">
                    Belum ada rekam medis. Mulai proses kunjungan, lalu isi rekam medis untuk menyelesaikannya.
                </div>
            @endif
        </div>
    @endcan
@endsection
