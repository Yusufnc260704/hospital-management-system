<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Dokter;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function index(Request $request): View
    {
        $penggunas = User::query()
            ->with('dokter')
            ->when(
                $request->filled('q'),
                fn ($query) => $query->whereAny(
                    ['name', 'email'],
                    'like',
                    '%'.$request->string('q')->trim().'%',
                ),
            )
            ->when(
                $request->filled('role'),
                fn ($query) => $query->where('role', $request->input('role')),
            )
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('pengguna.index', [
            'penggunas' => $penggunas,
            'q' => $request->input('q', ''),
            'roleTerpilih' => (string) $request->input('role', ''),
        ]);
    }

    public function create(): View
    {
        return view('pengguna.create', [
            'pengguna' => new User(['role' => Role::Registrasi->value, 'is_active' => true]),
            'dokters' => Dokter::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validasi($request);

        $pengguna = User::create($validated);

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun '.$pengguna->name.' berhasil dibuat.');
    }

    public function edit(User $pengguna): View
    {
        return view('pengguna.edit', [
            'pengguna' => $pengguna,
            'dokters' => Dokter::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, User $pengguna): RedirectResponse
    {
        $validated = $this->validasi($request, $pengguna);

        $bentrok = $this->cegahKehilanganAdmin($request, $pengguna, $validated);

        if ($bentrok !== null) {
            return redirect()->back()->withInput()->with('gagal', $bentrok);
        }

        if (blank($validated['password'] ?? null)) {
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun '.$pengguna->name.' diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        if ($pengguna->is($request->user())) {
            return redirect()->route('pengguna.index')
                ->with('gagal', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($pengguna->isAdmin() && ! $this->masihAdaAdminLain($pengguna)) {
            return redirect()->route('pengguna.index')
                ->with('gagal', 'Akun administrator terakhir tidak boleh dihapus. Nonaktifkan atau buat admin lain terlebih dahulu.');
        }

        $nama = $pengguna->name;
        $pengguna->delete();

        return redirect()
            ->route('pengguna.index')
            ->with('sukses', 'Akun '.$nama.' dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validasi(Request $request, ?User $pengguna = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($pengguna?->id),
            ],
            'role' => ['required', Rule::enum(Role::class)],
            'dokter_id' => [
                'nullable',
                'required_if:role,'.Role::Dokter->value,
                Rule::exists('dokter', 'id'),
            ],
            'is_active' => ['nullable', 'boolean'],
            'password' => [$pengguna === null ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'dokter_id.required_if' => 'Akun dengan peran Dokter wajib ditautkan ke data dokter.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $validated['email'] = Str::lower($validated['email']);
        $validated['is_active'] = $request->boolean('is_active');
        $validated['dokter_id'] = $validated['role'] === Role::Dokter->value
            ? ($validated['dokter_id'] ?? null)
            : null;

        return $validated;
    }

    /**
     * @param  array<string, mixed>  $validated
     */
    private function cegahKehilanganAdmin(Request $request, User $pengguna, array $validated): ?string
    {
        $menurunkanDiri = $pengguna->is($request->user())
            && ($validated['role'] !== Role::Admin->value || ! $validated['is_active']);

        if ($menurunkanDiri) {
            return 'Anda tidak dapat menurunkan peran atau menonaktifkan akun sendiri.';
        }

        $hilangAdmin = $pengguna->isAdmin()
            && ($validated['role'] !== Role::Admin->value || ! $validated['is_active'])
            && ! $this->masihAdaAdminLain($pengguna);

        if ($hilangAdmin) {
            return 'Sistem harus memiliki minimal satu administrator aktif.';
        }

        return null;
    }

    private function masihAdaAdminLain(User $pengguna): bool
    {
        return User::query()
            ->where('role', Role::Admin->value)
            ->where('is_active', true)
            ->whereKeyNot($pengguna->getKey())
            ->exists();
    }
}
