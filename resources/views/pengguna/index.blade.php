@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Pengguna Sistem</h1>
            <div class="sub">Kelola akun pegawai dan hak aksesnya.</div>
        </div>
        <a href="{{ route('pengguna.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i>Pengguna Baru
        </a>
    </div>

    <form method="GET" action="{{ route('pengguna.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small mb-1">Cari</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="Nama atau email...">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Peran</label>
            <select name="role" class="form-select">
                <option value="">Semua peran</option>
                @foreach (\App\Enums\Role::opsi() as $nilai => $label)
                    <option value="{{ $nilai }}" @selected($roleTerpilih === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '' || $roleTerpilih !== '')
                <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Peran</th>
                            <th>Dokter</th>
                            <th>Status</th>
                            <th>Login Terakhir</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penggunas as $pengguna)
                            <tr>
                                <td>
                                    <div class="fw-semibold">
                                        {{ $pengguna->name }}
                                        @if ($pengguna->is(auth()->user()))
                                            <span class="badge text-bg-secondary ms-1">Anda</span>
                                        @endif
                                    </div>
                                    <small class="text-secondary">{{ $pengguna->email }}</small>
                                </td>
                                <td>
                                    @php($role = $pengguna->roleEnum())
                                    @if ($role)
                                        <span class="badge text-bg-{{ $role->warna() }}">{{ $role->label() }}</span>
                                    @else
                                        <span class="badge text-bg-secondary">{{ $pengguna->role }}</span>
                                    @endif
                                </td>
                                <td class="small">{{ $pengguna->dokter?->nama ?? '-' }}</td>
                                <td>
                                    @if ($pengguna->is_active)
                                        <span class="badge text-bg-success">Aktif</span>
                                    @else
                                        <span class="badge text-bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="small text-nowrap">
                                    @if ($pengguna->last_login_at)
                                        {{ $pengguna->last_login_at->format('d/m/Y H:i') }}
                                        <div class="text-secondary">{{ $pengguna->last_login_ip }}</div>
                                    @else
                                        <span class="text-secondary">Belum pernah</span>
                                    @endif
                                </td>
                                <td class="text-end text-nowrap">
                                    <a href="{{ route('pengguna.edit', $pengguna) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    @unless ($pengguna->is(auth()->user()))
                                        <form method="POST" action="{{ route('pengguna.destroy', $pengguna) }}" class="d-inline"
                                              onsubmit="return confirm('Hapus akun {{ $pengguna->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>
                                    @endunless
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">Belum ada akun pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($penggunas->hasPages())
            <div class="card-footer bg-white">{{ $penggunas->links() }}</div>
        @endif
    </div>
@endsection
