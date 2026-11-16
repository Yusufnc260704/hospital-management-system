@php($obat ??= null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="nama" class="form-label">Nama obat</label>
        <input id="nama" name="nama" value="{{ old('nama', $obat?->nama) }}" class="form-control" placeholder="Paracetamol 500mg" required>
    </div>
    <div class="col-md-3">
        <label for="jenis" class="form-label">Jenis (opsional)</label>
        <input id="jenis" name="jenis" value="{{ old('jenis', $obat?->jenis) }}" class="form-control" placeholder="tablet / kapsul / sirup">
    </div>
    <div class="col-md-3">
        <label for="satuan" class="form-label">Satuan</label>
        <input id="satuan" name="satuan" value="{{ old('satuan', $obat?->satuan ?? 'tablet') }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label for="stok" class="form-label">Stok</label>
        <input id="stok" type="number" min="0" name="stok" value="{{ old('stok', $obat?->stok ?? 0) }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label for="stok_minimum" class="form-label">Stok minimum</label>
        <input id="stok_minimum" type="number" min="0" name="stok_minimum" value="{{ old('stok_minimum', $obat?->stok_minimum ?? 10) }}" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label for="harga" class="form-label">Harga (Rp)</label>
        <input id="harga" type="number" min="0" step="100" name="harga" value="{{ old('harga', $obat?->harga ?? 0) }}" class="form-control" required>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($obat) ? 'Simpan Perubahan' : 'Simpan' }}</button>
    <a href="{{ route('obat.index') }}" class="btn btn-outline-secondary">Batal</a>
</div>
