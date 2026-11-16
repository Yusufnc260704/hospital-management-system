@extends('layouts.app')

@section('title', 'Pasien')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Daftar Pasien</h1>
        @can('pasien.kelola')
            <a href="{{ route('pasien.create') }}" class="btn btn-primary">+ Pasien Baru</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('pasien.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama, No. RM, atau NIK...">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '')
                <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No. RM</th>
                        <th>Nama</th>
                        <th>NIK</th>
                        <th>Umur</th>
                        <th>Kunjungan</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pasiens as $pasien)
                        <tr>
                            <td class="text-nowrap">{{ $pasien->no_rm }}</td>
                            <td>
                                <div>{{ $pasien->nama }}</div>
                                <small class="text-secondary">{{ $pasien->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}{{ $pasien->no_telp ? ' — ' . $pasien->no_telp : '' }}</small>
                            </td>
                            <td>{{ $pasien->nik }}</td>
                            <td>{{ $pasien->umur }} th</td>
                            <td><span class="badge bg-secondary">{{ $pasien->kunjungan_count }}x</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                <a href="{{ route('pasien.edit', $pasien) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('pasien.destroy', $pasien) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus pasien {{ $pasien->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-secondary py-4">
                            {{ $q !== '' ? 'Pasien tidak ditemukan.' : 'Belum ada pasien terdaftar.' }}
                        </td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $pasiens->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
