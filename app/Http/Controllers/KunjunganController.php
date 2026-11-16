<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Poli;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KunjunganController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'tanggal' => ['nullable', 'date'],
            'status' => ['nullable', 'in:menunggu,diproses,selesai'],
        ]);

        $kunjungans = Kunjungan::query()
            ->with(['pasien', 'poli', 'dokter', 'rekamMedis'])
            ->when(
                $request->user()->hasRole(Role::Dokter),
                fn ($query) => $query->where('dokter_id', $request->user()->dokter_id ?? 0),
            )
            ->when(
                $request->filled('tanggal'),
                fn ($query) => $query->whereDate('tanggal_kunjungan', $validated['tanggal']),
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $validated['status']),
            )
            ->orderByDesc('tanggal_kunjungan')
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('kunjungan.index', [
            'kunjungans' => $kunjungans,
            'tanggal' => $validated['tanggal'] ?? '',
            'statusTerpilih' => filled($validated['status'] ?? null) ? StatusKunjungan::from($validated['status']) : null,
        ]);
    }

    public function create(): View
    {
        return view('kunjungan.create', [
            'pasienOptions' => Pasien::orderBy('nama')->get(['id', 'no_rm', 'nama']),
            'poliOptions' => Poli::with('dokter')->orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'pasien_id' => ['required', 'exists:pasien,id'],
            'poli_id' => ['required', 'exists:poli,id'],
            'dokter_id' => [
                'required',
                'exists:dokter,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if ((int) $request->input('poli_id') !== (int) Dokter::find($value)?->poli_id) {
                        $fail('Dokter yang dipilih tidak praktik di poli yang dipilih.');
                    }
                },
            ],
            'tanggal_kunjungan' => ['required', 'date'],
            'keluhan' => ['required', 'string', 'max:1000'],
        ]);

        $kunjungan = Kunjungan::create([
            'pasien_id' => $validated['pasien_id'],
            'poli_id' => $validated['poli_id'],
            'dokter_id' => $validated['dokter_id'],
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'keluhan' => $validated['keluhan'],
            'status' => StatusKunjungan::Menunggu,
        ]);

        return redirect()
            ->route('kunjungan.show', $kunjungan)
            ->with('sukses', 'Pendaftaran kunjungan berhasil dibuat.');
    }

    public function show(Request $request, Kunjungan $kunjungan): View
    {
        $this->pastikanAksesDokter($request, $kunjungan);

        $kunjungan->load(['pasien', 'poli', 'dokter.poli', 'rekamMedis']);

        return view('kunjungan.show', ['kunjungan' => $kunjungan]);
    }

    public function edit(Kunjungan $kunjungan): View
    {
        $kunjungan->load(['pasien', 'dokter']);

        return view('kunjungan.edit', [
            'kunjungan' => $kunjungan,
            'pasienOptions' => Pasien::orderBy('nama')->get(['id', 'no_rm', 'nama']),
            'poliOptions' => Poli::with('dokter')->orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        $validated = $request->validate([
            'pasien_id' => ['required', 'exists:pasien,id'],
            'poli_id' => ['required', 'exists:poli,id'],
            'dokter_id' => [
                'required',
                'exists:dokter,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($request): void {
                    if ((int) $request->input('poli_id') !== (int) Dokter::find($value)?->poli_id) {
                        $fail('Dokter yang dipilih tidak praktik di poli yang dipilih.');
                    }
                },
            ],
            'tanggal_kunjungan' => ['required', 'date'],
            'keluhan' => ['required', 'string', 'max:1000'],
        ]);

        $kunjungan->update([
            'pasien_id' => $validated['pasien_id'],
            'poli_id' => $validated['poli_id'],
            'dokter_id' => $validated['dokter_id'],
            'tanggal_kunjungan' => $validated['tanggal_kunjungan'],
            'keluhan' => $validated['keluhan'],
        ]);

        return redirect()
            ->route('kunjungan.show', $kunjungan)
            ->with('sukses', 'Data kunjungan diperbarui.');
    }

    /**
     * Memulai pelayanan pasien (status menjadi diproses).
     */
    public function proses(Request $request, Kunjungan $kunjungan): RedirectResponse
    {
        $this->pastikanAksesDokter($request, $kunjungan);

        if ($kunjungan->status === StatusKunjungan::Selesai) {
            return redirect()->back()->with('gagal', 'Kunjungan ini sudah selesai.');
        }

        $kunjungan->update(['status' => StatusKunjungan::Diproses]);

        return redirect()->back()->with('sukses', 'Kunjungan mulai diproses.');
    }

    /**
     * Menandai kunjungan sebagai selesai.
     */
    public function selesai(Kunjungan $kunjungan): RedirectResponse
    {
        $kunjungan->update(['status' => StatusKunjungan::Selesai]);

        return redirect()
            ->back()
            ->with('sukses', 'Kunjungan ditandai selesai.');
    }

    public function destroy(Kunjungan $kunjungan): RedirectResponse
    {
        if ($kunjungan->rekamMedis()->exists()) {
            return redirect()
                ->back()
                ->with('gagal', 'Kunjungan sudah memiliki rekam medis, hapus rekam medisnya terlebih dahulu.');
        }

        $kunjungan->delete();

        return redirect()
            ->route('kunjungan.index')
            ->with('sukses', 'Kunjungan dihapus.');
    }

    /**
     * Dokter hanya boleh mengakses kunjungan pasien yang ditangani sendiri.
     */
    private function pastikanAksesDokter(Request $request, Kunjungan $kunjungan): void
    {
        $pengguna = $request->user();

        if (! $pengguna->hasRole(Role::Dokter)) {
            return;
        }

        abort_unless(
            $pengguna->dokter_id !== null && (int) $kunjungan->dokter_id === (int) $pengguna->dokter_id,
            403,
            'Anda hanya dapat mengakses kunjungan pasien Anda sendiri.',
        );
    }
}
