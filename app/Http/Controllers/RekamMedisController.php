<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\StatusKunjungan;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RekamMedisController extends Controller
{
    public function index(Request $request): View
    {
        $rekamMedis = RekamMedis::query()
            ->with(['kunjungan.pasien', 'kunjungan.dokter'])
            ->when(
                $request->user()->hasRole(Role::Dokter),
                fn ($query) => $query->whereHas(
                    'kunjungan',
                    fn ($kunjungan) => $kunjungan->where('dokter_id', $request->user()->dokter_id ?? 0),
                ),
            )
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(function ($outer) use ($request): void {
                    $q = '%'.$request->string('q')->trim().'%';
                    $outer->where('diagnosa', 'like', $q);
                    $outer->orWhereHas('kunjungan', fn ($kunjungan) => $kunjungan
                        ->whereHas('pasien', fn ($pasien) => $pasien->where('nama', 'like', $q))
                        ->orWhereHas('pasien', fn ($pasien) => $pasien->where('no_rm', 'like', $q)));
                }),
            )
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('rekam_medis.index', [
            'rekamMedis' => $rekamMedis,
            'q' => $request->input('q', ''),
        ]);
    }

    public function create(Request $request): View
    {
        $kunjunganOptions = Kunjungan::query()
            ->whereIn('status', [StatusKunjungan::Diproses])
            ->when(
                $request->user()->hasRole(Role::Dokter),
                fn ($query) => $query->where('dokter_id', $request->user()->dokter_id ?? 0),
            )
            ->with(['pasien', 'dokter'])
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        return view('rekam_medis.create', [
            'kunjunganOptions' => $kunjunganOptions,
            'kunjunganTerpilih' => $request->integer('kunjungan_id') ?: old('kunjungan_id'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $kunjunganTerpilih = Kunjungan::find($request->integer('kunjungan_id'));

        if ($kunjunganTerpilih !== null) {
            $this->pastikanAksesDokter($request, $kunjunganTerpilih);
        }

        $validated = $request->validate([
            'kunjungan_id' => [
                'required',
                'exists:kunjungan,id',
                'unique:rekam_medis,kunjungan_id',
            ],
            'diagnosa' => ['required', 'string', 'max:1000'],
            'tindakan' => ['nullable', 'string', 'max:1000'],
            'resep' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $rekamMedis = DB::transaction(function () use ($validated): RekamMedis {
            $rekamMedis = RekamMedis::create($validated);

            Kunjungan::find($validated['kunjungan_id'])?->update(['status' => StatusKunjungan::Selesai]);

            return $rekamMedis;
        });

        return redirect()
            ->route('rekam-medis.show', $rekamMedis)
            ->with('sukses', 'Rekam medis berhasil disimpan dan kunjungan diselesaikan.');
    }

    public function show(Request $request, RekamMedis $rekamMedis): View
    {
        $this->pastikanAksesDokter($request, $rekamMedis->kunjungan);

        $rekamMedis->load(['kunjungan.pasien', 'kunjungan.dokter.poli', 'kunjungan.poli']);

        return view('rekam_medis.show', ['rekamMedis' => $rekamMedis]);
    }

    public function edit(Request $request, RekamMedis $rekamMedis): View
    {
        $this->pastikanAksesDokter($request, $rekamMedis->kunjungan);

        $rekamMedis->load('kunjungan.pasien');

        return view('rekam_medis.edit', ['rekamMedis' => $rekamMedis]);
    }

    public function update(Request $request, RekamMedis $rekamMedis): RedirectResponse
    {
        $this->pastikanAksesDokter($request, $rekamMedis->kunjungan);

        $validated = $request->validate([
            'diagnosa' => ['required', 'string', 'max:1000'],
            'tindakan' => ['nullable', 'string', 'max:1000'],
            'resep' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $rekamMedis->update($validated);

        return redirect()
            ->route('rekam-medis.show', $rekamMedis)
            ->with('sukses', 'Data rekam medis diperbarui.');
    }

    public function destroy(Request $request, RekamMedis $rekamMedis): RedirectResponse
    {
        $kunjungan = $rekamMedis->kunjungan;

        $this->pastikanAksesDokter($request, $kunjungan);

        DB::transaction(function () use ($rekamMedis, $kunjungan): void {
            $rekamMedis->delete();
            $kunjungan?->update(['status' => StatusKunjungan::Diproses]);
        });

        return redirect()
            ->route('rekam-medis.index')
            ->with('sukses', 'Rekam medis dihapus, kunjungan dikembalikan ke status diproses.');
    }

    /**
     * Dokter hanya boleh mengakses rekam medis pasien yang ditangani sendiri.
     */
    private function pastikanAksesDokter(Request $request, ?Kunjungan $kunjungan): void
    {
        $pengguna = $request->user();

        if (! $pengguna->hasRole(Role::Dokter)) {
            return;
        }

        abort_unless(
            $kunjungan !== null
                && $pengguna->dokter_id !== null
                && (int) $kunjungan->dokter_id === (int) $pengguna->dokter_id,
            403,
            'Anda hanya dapat mengakses rekam medis pasien Anda sendiri.',
        );
    }
}
