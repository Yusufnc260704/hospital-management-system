<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Poli;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DokterController extends Controller
{
    public function index(Request $request): View
    {
        $dokters = Dokter::query()
            ->with('poli')
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(fn ($sub) => $sub
                    ->where('nama', 'like', '%'.$request->string('q')->trim().'%')
                    ->orWhere('spesialisasi', 'like', '%'.$request->string('q')->trim().'%')),
            )
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('dokter.index', [
            'dokters' => $dokters,
            'q' => $request->input('q', ''),
        ]);
    }

    public function create(): View
    {
        return view('dokter.create', [
            'polis' => Poli::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'poli_id' => ['required', 'exists:poli,id'],
            'nama' => ['required', 'string', 'max:150'],
            'spesialisasi' => ['nullable', 'string', 'max:100'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        Dokter::create($validated);

        return redirect()
            ->route('dokter.index')
            ->with('sukses', 'Dokter baru berhasil ditambahkan.');
    }

    public function edit(Dokter $dokter): View
    {
        return view('dokter.edit', [
            'dokter' => $dokter,
            'polis' => Poli::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Dokter $dokter): RedirectResponse
    {
        $validated = $request->validate([
            'poli_id' => ['required', 'exists:poli,id'],
            'nama' => ['required', 'string', 'max:150'],
            'spesialisasi' => ['nullable', 'string', 'max:100'],
            'no_telp' => ['nullable', 'string', 'max:20'],
        ]);

        $dokter->update($validated);

        return redirect()
            ->route('dokter.index')
            ->with('sukses', 'Data dokter diperbarui.');
    }

    public function destroy(Dokter $dokter): RedirectResponse
    {
        try {
            $dokter->delete();

            return redirect()
                ->route('dokter.index')
                ->with('sukses', 'Dokter dihapus.');
        } catch (QueryException) {
            return redirect()
                ->route('dokter.index')
                ->with('gagal', 'Dokter tidak bisa dihapus karena masih memiliki data kunjungan.');
        }
    }
}
