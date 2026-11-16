@extends('layouts.app')

@section('title', 'Pendaftaran Kunjungan')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Daftar Kunjungan</h1>
        @can('kunjungan.kelola')
            <a href="{{ route('kunjungan.create') }}" class="btn btn-primary">+ Kunjungan Baru</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('kunjungan.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label small mb-0">Tanggal</label>
            <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-0">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                @foreach (['menunggu', 'diproses', 'selesai'] as $nilai)
                    <option value="{{ $nilai }}" @selected($statusTerpilih?->value === $nilai)>{{ ucfirst($nilai) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary">Filter</button>
            @if ($tanggal !== '' || $statusTerpilih !== null)
                <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Pasien</th><th>Poli / Dokter</th><th>Keluhan</th><th>Status</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($kunjungans as $kunjungan)
                        <tr>
                            <td class="text-nowrap">{{ $kunjungan->tanggal_kunjungan->isoFormat('D MMM Y') }}</td>
                            <td>
                                <div>{{ $kunjungan->pasien->nama }}</div>
                                <small class="text-secondary">{{ $kunjungan->pasien->no_rm }}</small>
                            </td>
                            <td>
                                <div>{{ $kunjungan->poli->nama }}</div>
                                <small class="text-secondary">{{ $kunjungan->dokter->nama }}</small>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($kunjungan->keluhan, 40) }}</td>
                            <td>
                                @php($warna = ['menunggu' => 'warning', 'diproses' => 'info', 'selesai' => 'success'])
                                <span class="badge bg-{{ $warna[$kunjungan->status->value] }}">{{ $kunjungan->status->label() }}</span>
                            </td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('kunjungan.show', $kunjungan) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                @can('kunjungan.kelola')
                                    <form method="POST" action="{{ route('kunjungan.destroy', $kunjungan) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus kunjungan #{{ $kunjungan->id }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" @if ($kunjungan->rekamMedis !== null) disabled title="Hapus rekam medisnya dulu" @endif>Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">Belum ada kunjungan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $kunjungans->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
