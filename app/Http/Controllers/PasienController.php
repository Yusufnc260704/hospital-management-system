<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PasienController extends Controller
{
    public function index(Request $request): View
    {
        $pasiens = Pasien::query()
            ->withCount('kunjungan')
            ->when(
                $request->filled('q'),
                fn ($query) => $query->whereAny(['nama', 'no_rm', 'nik'], 'like', '%'.$request->string('q')->trim().'%'),
            )
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('pasien.index', [
            'pasiens' => $pasiens,
            'q' => $request->input('q', ''),
        ]);
    }

    public function create(): View
    {
        $noRmLanjut = 'RM-'.str_pad((string) ((int) Pasien::max('id') + 1), 5, '0', STR_PAD_LEFT);

        return view('pasien.create', ['noRmLanjut' => $noRmLanjut]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'digits:16', 'unique:pasien,nik'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $validated['no_rm'] = 'RM-'.str_pad((string) ((int) Pasien::max('id') + 1), 5, '0', STR_PAD_LEFT);

        $pasien = Pasien::create($validated);

        return redirect()
            ->route('pasien.show', $pasien)
            ->with('sukses', 'Pasien baru berhasil didaftarkan dengan No. RM '.$pasien->no_rm.'.');
    }

    public function show(Pasien $pasien): View
    {
        $kunjungans = $pasien->kunjungan()
            ->with(['poli', 'dokter'])
            ->paginate(10);

        return view('pasien.show', [
            'pasien' => $pasien,
            'kunjungans' => $kunjungans,
        ]);
    }

    public function edit(Pasien $pasien): View
    {
        return view('pasien.edit', ['pasien' => $pasien]);
    }

    public function update(Request $request, Pasien $pasien): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'nik' => ['required', 'digits:16', 'unique:pasien,nik,'.$pasien->id],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $pasien->update($validated);

        return redirect()
            ->route('pasien.show', $pasien)
            ->with('sukses', 'Data pasien diperbarui.');
    }

    public function destroy(Pasien $pasien): RedirectResponse
    {
        if ($pasien->kunjungan()->exists()) {
            return redirect()
                ->route('pasien.show', $pasien)
                ->with('gagal', 'Pasien masih memiliki riwayat kunjungan, sehingga tidak bisa dihapus.');
        }

        try {
            $pasien->delete();

            return redirect()
                ->route('pasien.index')
                ->with('sukses', 'Pasien dihapus.');
        } catch (QueryException) {
            return redirect()
                ->route('pasien.show', $pasien)
                ->with('gagal', 'Pasien tidak bisa dihapus karena masih dipakai oleh data lain.');
        }
    }
}
