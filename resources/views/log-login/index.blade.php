@extends('layouts.app')

@section('title', 'Log Login')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Log Login</h1>
            <div class="sub">Jejak aktivitas masuk ke sistem — pantau percobaan yang mencurigakan.</div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge text-bg-success align-self-center">Hari ini: {{ $ringkasan['berhasil'] }} berhasil</span>
            <span class="badge text-bg-danger align-self-center">{{ $ringkasan['gagal'] }} gagal</span>
        </div>
    </div>

    <form method="GET" action="{{ route('log-login.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small mb-1">Cari email</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control" placeholder="nama@rsdrjth.test">
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Status</label>
            <select name="status" class="form-select">
                <option value="">Semua status</option>
                <option value="berhasil" @selected($statusTerpilih === 'berhasil')>Berhasil</option>
                <option value="gagal" @selected($statusTerpilih === 'gagal')>Gagal</option>
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '' || $statusTerpilih !== '')
                <a href="{{ route('log-login.index') }}" class="btn btn-outline-secondary">Reset</a>
            @endif
        </div>
    </form>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="small text-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                <td class="small">
                                    {{ $log->email }}
                                    @if ($log->user?->name)
                                        <div class="text-secondary">{{ $log->user->name }}</div>
                                    @endif
                                </td>
                                <td class="small text-capitalize">{{ $log->role ?: '-' }}</td>
                                <td><span class="badge text-bg-{{ $log->warnaStatus() }}">{{ $log->labelStatus() }}</span></td>
                                <td class="small">{{ $log->alasan ?: '-' }}</td>
                                <td class="small">
                                    {{ $log->ip_address }}
                                    <div class="text-secondary text-truncate" style="max-width: 220px;" title="{{ $log->user_agent }}">
                                        {{ \Illuminate\Support\Str::limit($log->user_agent, 40) }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">Belum ada catatan login.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($logs->hasPages())
            <div class="card-footer bg-white">{{ $logs->links() }}</div>
        @endif
    </div>
@endsection
