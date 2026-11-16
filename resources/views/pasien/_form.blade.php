@php($pasien ??= null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="nama" class="form-label">Nama lengkap</label>
        <input id="nama" name="nama" value="{{ old('nama', $pasien?->nama) }}" class="form-control" required>
    </div>

    <div class="col-md-6">
        <label for="nik" class="form-label">NIK (16 digit)</label>
        <input id="nik" name="nik" value="{{ old('nik', $pasien?->nik) }}" class="form-control" inputmode="numeric" required>
    </div>

    <div class="col-md-4">
        <label class="form-label d-block">Jenis kelamin</label>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_l" value="L"
                   @checked(old('jenis_kelamin', $pasien?->jenis_kelamin) === 'L') required>
            <label class="form-check-label" for="jk_l">Laki-laki</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="jenis_kelamin" id="jk_p" value="P"
                   @checked(old('jenis_kelamin', $pasien?->jenis_kelamin) === 'P')>
            <label class="form-check-label" for="jk_p">Perempuan</label>
        </div>
    </div>

    <div class="col-md-4">
        <label for="tanggal_lahir" class="form-label">Tanggal lahir</label>
        <input id="tanggal_lahir" type="date" max="{{ now()->toDateString() }}" name="tanggal_lahir"
               value="{{ old('tanggal_lahir', $pasien?->tanggal_lahir?->format('Y-m-d')) }}" class="form-control" required>
    </div>

    <div class="col-md-4">
        <label for="no_telp" class="form-label">No. telepon (opsional)</label>
        <input id="no_telp" name="no_telp" value="{{ old('no_telp', $pasien?->no_telp) }}" class="form-control">
    </div>

    <div class="col-12">
        <label for="alamat" class="form-label">Alamat (opsional)</label>
        <textarea id="alamat" name="alamat" rows="2" class="form-control">{{ old('alamat', $pasien?->alamat) }}</textarea>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($pasien) ? 'Simpan Perubahan' : 'Daftarkan Pasien' }}</button>
    <a href="{{ isset($pasien) ? route('pasien.show', $pasien) : route('pasien.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
