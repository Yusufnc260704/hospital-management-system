@extends('layouts.app')

@section('title', 'Rekam Medis')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Rekam Medis</h1>
        @can('rekam-medis.kelola')
            <a href="{{ route('rekam-medis.create') }}" class="btn btn-primary">+ Rekam Medis Baru</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('rekam-medis.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari diagnosa, nama pasien, atau No. RM...">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '')
                <a href="{{ route('rekam-medis.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Pasien</th><th>Dokter</th><th>Diagnosa</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($rekamMedis as $rm)
                        <tr>
                            <td class="text-nowrap">{{ $rm->kunjungan->tanggal_kunjungan->isoFormat('D MMM Y') }}</td>
                            <td>
                                <div>{{ $rm->kunjungan->pasien->nama }}</div>
                                <small class="text-secondary">{{ $rm->kunjungan->pasien->no_rm }}</small>
                            </td>
                            <td>{{ $rm->kunjungan->dokter->nama }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($rm->diagnosa, 50) }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('rekam-medis.show', $rm) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                @can('rekam-medis.kelola')
                                    <a href="{{ route('rekam-medis.edit', $rm) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form method="POST" action="{{ route('rekam-medis.destroy', $rm) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus rekam medis ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada rekam medis.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $rekamMedis->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
