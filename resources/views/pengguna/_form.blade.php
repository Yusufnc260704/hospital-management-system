@php
    $sedangMembuat = ! $pengguna->exists;
    $aksi = $sedangMembuat ? route('pengguna.store') : route('pengguna.update', $pengguna);
@endphp

<form method="POST" action="{{ $aksi }}" class="row g-3">
    @csrf
    @unless ($sedangMembuat)
        @method('PUT')
    @endunless

    <div class="col-md-6">
        <label for="name" class="form-label">Nama lengkap</label>
        <input id="name" type="text" name="name" value="{{ old('name', $pengguna->name) }}"
               class="form-control @error('name') is-invalid @enderror" required>
    </div>

    <div class="col-md-6">
        <label for="email" class="form-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $pengguna->email) }}"
               class="form-control @error('email') is-invalid @enderror" required>
    </div>

    <div class="col-md-6">
        <label for="role" class="form-label">Peran</label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror" required>
            @foreach (\App\Enums\Role::opsi() as $nilai => $label)
                <option value="{{ $nilai }}" @selected(old('role', $pengguna->role) === $nilai)>{{ $label }}</option>
            @endforeach
        </select>
        <div class="form-text">
            Administrator: akses penuh. Registrasi: pendaftaran. Dokter: rekam medis.
            Apoteker: farmasi.
        </div>
    </div>

    <div class="col-md-6">
        <label for="dokter_id" class="form-label">Tautan data dokter</label>
        <select id="dokter_id" name="dokter_id" class="form-select @error('dokter_id') is-invalid @enderror">
            <option value="">— Tidak ditautkan —</option>
            @foreach ($dokters as $dokter)
                <option value="{{ $dokter->id }}" @selected((string) old('dokter_id', $pengguna->dokter_id) === (string) $dokter->id)>
                    {{ $dokter->nama }} — {{ $dokter->spesialisasi }}
                </option>
            @endforeach
        </select>
        <div class="form-text">Wajib untuk peran Dokter agar jadwal pasien muncul di dashboard.</div>
    </div>

    <div class="col-md-6">
        <label for="password" class="form-label">
            Kata sandi {{ $sedangMembuat ? '' : '(kosongkan bila tidak diubah)' }}
        </label>
        <input id="password" type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               autocomplete="new-password" @required($sedangMembuat)>
        <div class="form-text">Minimal 8 karakter.</div>
    </div>

    <div class="col-md-6">
        <label for="password_confirmation" class="form-label">Konfirmasi kata sandi</label>
        <input id="password_confirmation" type="password" name="password_confirmation"
               class="form-control" autocomplete="new-password" @required($sedangMembuat)>
    </div>

    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active"
                   value="1" @checked(old('is_active', $pengguna->is_active))>
            <label class="form-check-label" for="is_active">Akun aktif (dapat masuk ke sistem)</label>
        </div>
    </div>

    <div class="col-12 d-flex gap-2 mt-2">
        <button class="btn btn-primary">
            <i class="bi bi-save me-1"></i>{{ $sedangMembuat ? 'Buat Akun' : 'Simpan Perubahan' }}
        </button>
        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
