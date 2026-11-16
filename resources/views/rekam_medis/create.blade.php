@extends('layouts.app')

@section('title', 'Rekam Medis Baru')

@section('content')
    <h1 class="fs-3 fw-bold mb-1">📋 Rekam Medis Baru</h1>
    <p class="text-secondary">Menyimpan rekam medis akan otomatis menandai kunjungan sebagai <strong>selesai</strong>.</p>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="{{ route('rekam-medis.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-12">
                        <label for="kunjungan_id" class="form-label">Kunjungan (status: diproses)</label>
                        <select id="kunjungan_id" name="kunjungan_id" class="form-select" required>
                            <option value="" disabled>-- pilih kunjungan --</option>
                            @foreach ($kunjunganOptions as $opsi)
                                <option value="{{ $opsi->id }}" @selected((int) $kunjunganTerpilih === $opsi->id)>
                                    {{ $opsi->tanggal_kunjungan->isoFormat('D MMM Y') }} — {{ $opsi->pasien->no_rm }} {{ $opsi->pasien->nama }} ({{ $opsi->dokter->nama }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="diagnosa" class="form-label">Diagnosa</label>
                        <textarea id="diagnosa" name="diagnosa" rows="2" class="form-control" required>{{ old('diagnosa') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="tindakan" class="form-label">Tindakan (opsional)</label>
                        <textarea id="tindakan" name="tindakan" rows="2" class="form-control">{{ old('tindakan') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="resep" class="form-label">Resep (opsional)</label>
                        <textarea id="resep" name="resep" rows="2" class="form-control" placeholder="Paracetamol 500mg 3x1, selama 3 hari">{{ old('resep') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label for="catatan" class="form-label">Catatan (opsional)</label>
                        <textarea id="catatan" name="catatan" rows="2" class="form-control">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Simpan Rekam Medis</button>
                    <a href="{{ route('rekam-medis.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    @if ($kunjunganOptions->isEmpty())
        <div class="alert alert-info mt-3">Tidak ada kunjungan berstatus <em>diproses</em>. Ubah salah satu kunjungan terlebih dahulu di halaman Pendaftaran.</div>
    @endif
@endsection
