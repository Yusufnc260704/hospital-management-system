@extends('layouts.app')

@section('title', 'Detail Rekam Medis')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Rekam Medis #{{ $rekamMedis->id }}</h1>
        @can('rekam-medis.kelola')
            <div class="d-flex gap-2">
                <a href="{{ route('rekam-medis.edit', $rekamMedis) }}" class="btn btn-outline-secondary">Edit</a>
                <form method="POST" action="{{ route('rekam-medis.destroy', $rekamMedis) }}"
                      onsubmit="return confirm('Hapus rekam medis ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger">Hapus</button>
                </form>
            </div>
        @endcan
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Tanggal kunjungan</dt>
                <dd class="col-md-9">{{ $rekamMedis->kunjungan->tanggal_kunjungan->isoFormat('dddd, D MMMM Y') }}</dd>
                <dt class="col-md-3">Pasien</dt>
                <dd class="col-md-9"><a href="{{ route('pasien.show', $rekamMedis->kunjungan->pasien) }}">{{ $rekamMedis->kunjungan->pasien->nama }}</a> ({{ $rekamMedis->kunjungan->pasien->no_rm }})</dd>
                <dt class="col-md-3">Poli / Dokter</dt>
                <dd class="col-md-9">{{ $rekamMedis->kunjungan->poli->nama }} — {{ $rekamMedis->kunjungan->dokter->nama }}</dd>
                <dt class="col-md-3">Keluhan</dt>
                <dd class="col-md-9">{{ $rekamMedis->kunjungan->keluhan }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">Diagnosa</dt><dd class="col-md-9">{{ $rekamMedis->diagnosa }}</dd>
                <dt class="col-md-3">Tindakan</dt><dd class="col-md-9">{{ $rekamMedis->tindakan ?: '-' }}</dd>
                <dt class="col-md-3">Resep</dt><dd class="col-md-9">{{ $rekamMedis->resep ?: '-' }}</dd>
                <dt class="col-md-3">Catatan</dt><dd class="col-md-9">{{ $rekamMedis->catatan ?: '-' }}</dd>
            </dl>
            <a href="{{ route('kunjungan.show', $rekamMedis->kunjungan) }}" class="btn btn-sm btn-outline-primary mt-3">Lihat Kunjungan Terkait</a>
        </div>
    </div>
@endsection
