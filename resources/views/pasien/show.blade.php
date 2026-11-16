@extends('layouts.app')

@section('title', 'Detail Pasien')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">{{ $pasien->nama }}</h1>
        <div class="d-flex gap-2">
            @can('kunjungan.kelola')
                <a href="{{ route('kunjungan.create', ['pasien_id' => $pasien->id]) }}" class="btn btn-primary">+ Daftar Kunjungan</a>
            @endcan
            @can('pasien.kelola')
                <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-outline-secondary">Edit</a>
            @endcan
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-md-3">No. Rekam Medis</dt><dd class="col-md-9">{{ $pasien->no_rm }}</dd>
                <dt class="col-md-3">NIK</dt><dd class="col-md-9">{{ $pasien->nik }}</dd>
                <dt class="col-md-3">Jenis kelamin</dt><dd class="col-md-9">{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                <dt class="col-md-3">Tanggal lahir</dt><dd class="col-md-9">{{ $pasien->tanggal_lahir->isoFormat('D MMMM Y') }} ({{ $pasien->umur }} tahun)</dd>
                <dt class="col-md-3">Telepon</dt><dd class="col-md-9">{{ $pasien->no_telp ?: '-' }}</dd>
                <dt class="col-md-3">Alamat</dt><dd class="col-md-9">{{ $pasien->alamat ?: '-' }}</dd>
            </dl>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white fw-semibold">Riwayat Kunjungan</div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Poli</th><th>Dokter</th><th>Keluhan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($kunjungans as $kunjungan)
                        <tr>
                            <td class="text-nowrap">{{ $kunjungan->tanggal_kunjungan->isoFormat('D MMM Y') }}</td>
                            <td>{{ $kunjungan->poli->nama }}</td>
                            <td>{{ $kunjungan->dokter?->nama }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($kunjungan->keluhan, 50) }}</td>
                            <td>
                                @php($warna = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success'])
                                <span class="badge bg-{{ $warna[$kunjungan->status->value] }}">{{ $kunjungan->status->label() }}</span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Pasien ini belum pernah berkunjung.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $kunjungans->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
