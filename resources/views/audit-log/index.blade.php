@extends('layouts.app')

@section('title', 'Audit Trail')

@section('content')
    <div class="kepala-halaman">
        <div>
            <h1>Audit Trail</h1>
            <div class="sub">Jejak perubahan data: siapa, kapan, dan apa yang berubah.</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <span class="badge text-bg-success align-self-center">Hari ini: {{ $ringkasan['dibuat'] }} dibuat</span>
            <span class="badge text-bg-warning align-self-center">{{ $ringkasan['diubah'] }} diubah</span>
            <span class="badge text-bg-danger align-self-center">{{ $ringkasan['dihapus'] }} dihapus</span>
        </div>
    </div>

    <form method="GET" action="{{ route('audit-log.index') }}" class="row g-2 mb-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small mb-1">Cari</label>
            <input type="text" name="q" value="{{ $q }}" class="form-control"
                   placeholder="Nama, email, data, atau IP...">
        </div>
        <div class="col-md-2">
            <label class="form-label small mb-1">Aksi</label>
            <select name="action" class="form-select">
                <option value="">Semua aksi</option>
                @foreach (['created' => 'Dibuat', 'updated' => 'Diubah', 'deleted' => 'Dihapus'] as $nilai => $label)
                    <option value="{{ $nilai }}" @selected($aksiTerpilih === $nilai)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label small mb-1">Jenis data</label>
            <select name="model" class="form-select">
                <option value="">Semua data</option>
                @foreach ($modelOptions as $kelas => $label)
                    <option value="{{ $kelas }}" @selected($modelTerpilih === $kelas)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-auto d-flex gap-2">
            <button class="btn btn-secondary">Cari</button>
            @if ($q !== '' || $aksiTerpilih !== '' || $modelTerpilih !== '')
                <a href="{{ route('audit-log.index') }}" class="btn btn-outline-secondary">Reset</a>
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
                            <th>Pengguna</th>
                            <th>Aksi</th>
                            <th>Data</th>
                            <th>Perubahan</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td class="small text-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                <td class="small">
                                    <div class="fw-semibold">{{ $log->user_name ?: 'Sistem' }}</div>
                                    @php($role = $log->user_role ? \App\Enums\Role::tryFrom($log->user_role) : null)
                                    <div class="text-secondary">{{ $role?->label() ?? ($log->user_role ?: '—') }}</div>
                                </td>
                                <td>
                                    <span class="badge text-bg-{{ $log->warnaAksi() }}">
                                        <i class="bi {{ $log->ikonAksi() }} me-1"></i>{{ $log->labelAksi() }}
                                    </span>
                                </td>
                                <td class="small">
                                    <div class="fw-semibold">{{ $log->label ?: \App\Models\AuditLog::labelModel($log->auditable_type) }}</div>
                                    <div class="text-secondary">
                                        {{ \App\Models\AuditLog::labelModel($log->auditable_type) }} #{{ $log->auditable_id }}
                                    </div>
                                </td>
                                <td class="small" style="max-width: 340px;">
                                    <div>{{ $log->ringkasan() }}</div>

                                    @php($lama = $log->old_values ?? [])
                                    @php($baru = $log->new_values ?? [])

                                    @if ($lama || $baru)
                                        <details class="mt-1">
                                            <summary class="text-primary" style="cursor: pointer;">Lihat detail</summary>
                                            <div class="mt-1">
                                                @foreach (($baru ?: $lama) as $kunci => $nilai)
                                                    <div class="mb-1">
                                                        <span class="text-secondary">{{ $kunci }}:</span>
                                                        @if ($baru && array_key_exists($kunci, $lama))
                                                            <span class="text-danger text-decoration-line-through">{{ \Illuminate\Support\Str::limit((string) $lama[$kunci], 50) }}</span>
                                                            <i class="bi bi-arrow-right mx-1"></i>
                                                        @endif
                                                        <span class="{{ $baru ? 'text-success' : 'text-secondary' }}">{{ \Illuminate\Support\Str::limit((string) $nilai, 50) }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    @endif
                                </td>
                                <td class="small">{{ $log->ip_address ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-secondary py-4">
                                    Belum ada catatan perubahan data.
                                </td>
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
