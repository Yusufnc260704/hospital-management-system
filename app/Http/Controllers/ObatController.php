<?php

namespace App\Http\Controllers;

use App\Models\Obat;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ObatController extends Controller
{
    public function index(Request $request): View
    {
        $obats = Obat::query()
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where(fn ($sub) => $sub
                    ->where('nama', 'like', '%'.$request->string('q')->trim().'%')
                    ->orWhere('jenis', 'like', '%'.$request->string('q')->trim().'%')),
            )
            ->when($request->input('stok') === 'kritis', fn ($query) => $query->whereColumn('stok', '<=', 'stok_minimum'))
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('obat.index', [
            'obats' => $obats,
            'q' => $request->input('q', ''),
            'stok' => $request->input('stok', ''),
        ]);
    }

    public function create(): View
    {
        return view('obat.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'jenis' => ['nullable', 'string', 'max:100'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);

        Obat::create($validated);

        return redirect()
            ->route('obat.index')
            ->with('sukses', 'Obat baru berhasil ditambahkan.');
    }

    public function edit(Obat $obat): View
    {
        return view('obat.edit', ['obat' => $obat]);
    }

    public function update(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:150'],
            'jenis' => ['nullable', 'string', 'max:100'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'integer', 'min:0'],
            'stok_minimum' => ['required', 'integer', 'min:0'],
            'harga' => ['required', 'integer', 'min:0'],
        ]);

        $obat->update($validated);

        return redirect()
            ->route('obat.index')
            ->with('sukses', 'Data obat diperbarui.');
    }

    /**
     * Menambah stok obat masuk.
     */
    public function restok(Request $request, Obat $obat): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:100000'],
        ]);

        $obat->increment('stok', $validated['jumlah']);

        return redirect()
            ->back()
            ->with('sukses', 'Stok '.$obat->nama.' ditambah sebanyak '.$validated['jumlah'].' '.$obat->satuan.'.');
    }

    public function destroy(Obat $obat): RedirectResponse
    {
        try {
            $obat->delete();

            return redirect()
                ->route('obat.index')
                ->with('sukses', 'Obat dihapus.');
        } catch (QueryException) {
            return redirect()
                ->route('obat.index')
                ->with('gagal', 'Obat tidak bisa dihapus.');
        }
    }
}
