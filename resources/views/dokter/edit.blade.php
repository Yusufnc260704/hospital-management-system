@extends('layouts.app')

@section('title', 'Edit Dokter')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">✏️ Edit Dokter: {{ $dokter->nama }}</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('dokter.update', $dokter) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama dokter</label>
                        <input id="nama" name="nama" value="{{ old('nama', $dokter->nama) }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="poli_id" class="form-label">Poli praktik</label>
                        <select id="poli_id" name="poli_id" class="form-select" required>
                            <option value="" disabled>-- pilih poli --</option>
                            @foreach ($polis as $poli)
                                <option value="{{ $poli->id }}" @selected(old('poli_id', $dokter->poli_id) == $poli->id)>{{ $poli->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="spesialisasi" class="form-label">Spesialisasi (opsional)</label>
                        <input id="spesialisasi" name="spesialisasi" value="{{ old('spesialisasi', $dokter->spesialisasi) }}" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label for="no_telp" class="form-label">No. telepon (opsional)</label>
                        <input id="no_telp" name="no_telp" value="{{ old('no_telp', $dokter->no_telp) }}" class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
