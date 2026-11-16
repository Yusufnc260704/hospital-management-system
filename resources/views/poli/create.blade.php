@extends('layouts.app')

@section('title', 'Poli Baru')

@section('content')
    <h1 class="fs-3 fw-bold mb-4">🏢 Poli Baru</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('poli.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nama" class="form-label">Nama poli</label>
                        <input id="nama" name="nama" value="{{ old('nama') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="deskripsi" class="form-label">Deskripsi (opsional)</label>
                        <input id="deskripsi" name="deskripsi" value="{{ old('deskripsi') }}" class="form-control">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('poli.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
