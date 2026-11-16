@extends('layouts.app')

@section('title', 'Poli')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">🏢 Daftar Poli</h1>
        <a href="{{ route('poli.create') }}" class="btn btn-primary">+ Poli Baru</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Nama Poli</th><th>Deskripsi</th><th>Dokter</th><th>Kunjungan</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($polis as $poli)
                        <tr>
                            <td class="fw-semibold">{{ $poli->nama }}</td>
                            <td>{{ $poli->deskripsi ?: '-' }}</td>
                            <td><span class="badge bg-secondary">{{ $poli->dokter_count }}</span></td>
                            <td><span class="badge bg-secondary">{{ $poli->kunjungan_count }}</span></td>
                            <td class="text-end text-nowrap">
                                <a href="{{ route('poli.edit', $poli) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form method="POST" action="{{ route('poli.destroy', $poli) }}" class="d-inline"
                                      onsubmit="return confirm('Hapus poli {{ $poli->nama }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada poli.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $polis->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
