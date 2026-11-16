@extends('layouts.app')

@section('title', 'Edit Rekam Medis')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">✏️ Edit Rekam Medis #{{ $rekamMedis->id }}</h1>

    <div class="card shadow-sm mb-3">
        <div class="card-body py-2 text-secondary">
            Kunjungan: {{ $rekamMedis->kunjungan->tanggal_kunjungan->isoFormat('D MMM Y') }} — {{ $rekamMedis->kunjungan->pasien->nama }}
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('rekam-medis.update', $rekamMedis) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-12">
                        <label for="diagnosa" class="form-label">Diagnosa</label>
                        <textarea id="diagnosa" name="diagnosa" rows="2" class="form-control" required>{{ old('diagnosa', $rekamMedis->diagnosa) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="tindakan" class="form-label">Tindakan (opsional)</label>
                        <textarea id="tindakan" name="tindakan" rows="2" class="form-control">{{ old('tindakan', $rekamMedis->tindakan) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="resep" class="form-label">Resep (opsional)</label>
                        <textarea id="resep" name="resep" rows="2" class="form-control">{{ old('resep', $rekamMedis->resep) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label for="catatan" class="form-label">Catatan (opsional)</label>
                        <textarea id="catatan" name="catatan" rows="2" class="form-control">{{ old('catatan', $rekamMedis->catatan) }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('rekam-medis.show', $rekamMedis) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
