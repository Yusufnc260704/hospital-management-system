@extends('layouts.app')

@section('title', 'Dokter')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">🩺 Daftar Dokter</h1>
        <a href="{{ route('dokter.create') }}" class="btn btn-primary">+ Dokter Baru</a>
    </div>

    <form method="GET" action="{{ route('dokter.index') }}" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama atau spesialisasi...">
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '')
                <a href="{{ route('dokter.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Poli</th><th>Spesialisasi</th><th>Telepon</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($dokters as $dokter)
                        <tr>
                            <td class="fw-semibold">{{ $dokter->nama }}</td>
                            <td>{{ $dokter->poli->nama }}</td>
                            <td>{{ $dokter->spesialisasi ?: '-' }}</td>
                            <td>{{ $dokter->no_telp ?: '-' }}</td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('dokter.edit', $dokter) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('dokter.destroy', $dokter) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus dokter {{ $dokter->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada dokter.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $dokters->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
