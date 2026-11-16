@extends('layouts.app')

@section('title', 'Farmasi')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <h1 class="fs-3 fw-bold mb-0">Farmasi</h1>
        @can('obat.kelola')
            <a href="{{ route('obat.create') }}" class="btn btn-primary">+ Obat Baru</a>
        @endcan
    </div>

    <form method="GET" action="{{ route('obat.index') }}" class="row g-2 mb-3 align-items-center">
        <div class="col-md-4">
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Cari nama atau jenis obat...">
        </div>
        <div class="col-md-auto">
            <select name="stok" class="form-select">
                <option value="">Semua stok</option>
                <option value="kritis" @selected($stok === 'kritis')>Hanya stok kritis</option>
            </select>
        </div>
        <div class="col-md-auto">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '' || $stok !== '')
                <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr><th>Nama</th><th>Jenis</th><th>Stok</th><th>Min.</th><th>Harga</th><th>Restok</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse ($obats as $obat)
                        <tr>
                            <td>{{ $obat->nama }} @if ($obat->stok_kritis)<span class="badge bg-danger">kritis</span>@endif</td>
                            <td class="text-capitalize">{{ $obat->jenis ?: '-' }}</td>
                            <td><strong>{{ number_format($obat->stok) }}</strong> {{ $obat->satuan }}</td>
                            <td>{{ number_format($obat->stok_minimum) }}</td>
                            <td>Rp{{ number_format($obat->harga) }}</td>
                            <td>
                                @can('obat.kelola')
                                    <form method="POST" action="{{ route('obat.restok', $obat) }}" class="d-flex gap-1" style="max-width: 170px;">
                                        @csrf
                                        <input type="number" name="jumlah" value="10" min="1" class="form-control form-control-sm" required aria-label="Jumlah restok">
                                        <button class="btn btn-sm btn-outline-success" title="Tambah stok">+</button>
                                    </form>
                                @else
                                    <span class="text-secondary small">Hanya lihat</span>
                                @endcan
                            </td>
                            <td class="text-end text-nowrap">
                                @can('obat.kelola')
                                    <a href="{{ route('obat.edit', $obat) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form method="POST" action="{{ route('obat.destroy', $obat) }}" class="d-inline"
                                          onsubmit="return confirm('Hapus obat {{ $obat->nama }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data obat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer bg-white">{{ $obats->links('pagination::bootstrap-4') }}</div>
    </div>
@endsection
