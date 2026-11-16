<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PoliController extends Controller
{
    public function index(): View
    {
        $polis = Poli::query()
            ->withCount('dokter')
            ->withCount('kunjungan')
            ->orderBy('nama')
            ->paginate(10);

        return view('poli.index', ['polis' => $polis]);
    }

    public function create(): View
    {
        return view('poli.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:poli,nama'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ]);

        Poli::create($validated);

        return redirect()
            ->route('poli.index')
            ->with('sukses', 'Poli baru berhasil ditambahkan.');
    }

    public function edit(Poli $poli): View
    {
        return view('poli.edit', ['poli' => $poli]);
    }

    public function update(Request $request, Poli $poli): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100', 'unique:poli,nama,'.$poli->id],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ]);

        $poli->update($validated);

        return redirect()
            ->route('poli.index')
            ->with('sukses', 'Data poli diperbarui.');
    }

    public function destroy(Poli $poli): RedirectResponse
    {
        try {
            $poli->delete();

            return redirect()
                ->route('poli.index')
                ->with('sukses', 'Poli dihapus.');
        } catch (QueryException) {
            return redirect()
                ->route('poli.index')
                ->with('gagal', 'Poli tidak bisa dihapus karena masih memiliki dokter atau kunjungan.');
        }
    }
}
