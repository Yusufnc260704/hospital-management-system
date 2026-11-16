@php($kunjungan ??= null)

<div class="row g-3">
    <div class="col-md-6">
        <label for="pasien_id" class="form-label">Pasien</label>
        <select id="pasien_id" name="pasien_id" class="form-select" required>
            <option value="" disabled>-- pilih pasien --</option>
            @foreach ($pasienOptions as $pasien)
                <option value="{{ $pasien->id }}" @selected(old('pasien_id', $kunjungan?->pasien_id) == $pasien->id)>
                    {{ $pasien->no_rm }} — {{ $pasien->nama }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="poli_id" class="form-label">Poli tujuan</label>
        <select id="poli_id" name="poli_id" class="form-select" required>
            <option value="" disabled>-- pilih poli --</option>
            @foreach ($poliOptions as $poli)
                <option value="{{ $poli->id }}" @selected(old('poli_id', $kunjungan?->poli_id) == $poli->id)>{{ $poli->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="dokter_id" class="form-label">Dokter</label>
        <select id="dokter_id" name="dokter_id" class="form-select" required>
            <option value="" disabled>-- pilih dokter --</option>
            @foreach ($poliOptions as $poli)
                @foreach ($poli->dokter as $dokter)
                    <option value="{{ $dokter->id }}" data-poli-id="{{ $poli->id }}"
                            @selected(old('dokter_id', $kunjungan?->dokter_id) == $dokter->id)>
                        {{ $dokter->nama }}
                    </option>
                @endforeach
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label for="tanggal_kunjungan" class="form-label">Tanggal kunjungan</label>
        <input id="tanggal_kunjungan" type="date" name="tanggal_kunjungan"
               value="{{ old('tanggal_kunjungan', $kunjungan?->tanggal_kunjungan?->format('Y-m-d') ?? now()->toDateString()) }}"
               class="form-control" required>
    </div>

    <div class="col-12">
        <label for="keluhan" class="form-label">Keluhan utama</label>
        <textarea id="keluhan" name="keluhan" rows="2" class="form-control" required>{{ old('keluhan', $kunjungan?->keluhan) }}</textarea>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-primary">{{ isset($kunjungan) ? 'Simpan Perubahan' : 'Daftarkan Kunjungan' }}</button>
    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">Batal</a>
</div>

@push('scripts')
<script>
    const poliSelect = document.getElementById('poli_id');
    const dokterSelect = document.getElementById('dokter_id');

    function sinkronkanDokter() {
        const poliTerpilih = poliSelect.value;
        const opsiDokter = [...dokterSelect.options].filter((option) => option.value !== '');

        let opsiAktifAda = false;

        opsiDokter.forEach((option) => {
            option.hidden = option.dataset.poliId !== poliTerpilih;
            opsiAktifAda = opsiAktifAda || (!option.hidden && option.selected);
        });

        if (!opsiAktifAda) {
            const pasangan = opsiDokter.find((option) => !option.hidden);

            if (pasangan) {
                dokterSelect.value = pasangan.value;
            }
        }
    }

    poliSelect.addEventListener('change', () => {
        sinkronkanDokter();
    });

    poliSelect.dispatchEvent(new Event('change'));
</script>
@endpush
