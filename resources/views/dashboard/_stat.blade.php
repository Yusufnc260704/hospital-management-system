{{-- Kartu statistik ringkas. Parameter: label, nilai, ikon, latar, warnaIkon, catatan (opsional). --}}
<div class="{{ $col ?? 'col-6 col-xl-3' }}">
    <div class="card card-stat h-100 shadow-sm">
        <div class="card-body d-flex justify-content-between align-items-center gap-2">
            <div style="min-width: 0;">
                <div class="label">{{ $label }}</div>
                <div class="angka {{ $kelas ?? '' }}">{{ $nilai }}</div>
                @isset($catatan)
                    <div class="small text-secondary mt-1">{{ $catatan }}</div>
                @endisset
            </div>
            <div class="ikon" style="background: {{ $latar ?? '#EAF8F6' }}; color: {{ $warnaIkon ?? '#0E9384' }};">
                <i class="bi {{ $ikon }}"></i>
            </div>
        </div>
    </div>
</div>
