<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\LoginLog;
use App\Models\Obat;
use App\Models\Pasien;
use App\Models\Poli;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        return match ($request->user()->roleEnum()) {
            Role::Admin => $this->admin(),
            Role::Dokter => $this->dokter($request),
            Role::Apoteker => $this->apoteker(),
            default => $this->registrasi(),
        };
    }

    private function admin(): View
    {
        return view('dashboard.admin', [
            'statistik' => [
                'totalPasien' => Pasien::count(),
                'kunjunganHariIni' => Kunjungan::hariIni()->count(),
                'menungguHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Menunggu)->count(),
                'diprosesHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Diproses)->count(),
                'selesaiHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Selesai)->count(),
                'totalDokter' => Dokter::count(),
                'totalPoli' => Poli::count(),
                'totalPengguna' => User::count(),
            ],
            'kunjunganHariIni' => Kunjungan::hariIni()
                ->with(['pasien', 'dokter.poli'])
                ->orderBy('status')
                ->latest('id')
                ->limit(8)
                ->get(),
            'poliKunjunganHariIni' => Poli::withCount(['kunjungan' => fn ($query) => $query->hariIni()])
                ->orderByDesc('kunjungan_count')
                ->limit(6)
                ->get(),
            'obatStokKritis' => Obat::whereColumn('stok', '<=', 'stok_minimum')
                ->orderBy('stok')
                ->limit(5)
                ->get(),
            'logTerbaru' => LoginLog::with('user')
                ->latest('created_at')
                ->latest('id')
                ->limit(6)
                ->get(),
        ]);
    }

    private function registrasi(): View
    {
        return view('dashboard.registrasi', [
            'statistik' => [
                'totalPasien' => Pasien::count(),
                'kunjunganHariIni' => Kunjungan::hariIni()->count(),
                'menungguHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Menunggu)->count(),
                'diprosesHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Diproses)->count(),
                'selesaiHariIni' => Kunjungan::hariIni()->status(StatusKunjungan::Selesai)->count(),
            ],
            'antrian' => Kunjungan::hariIni()
                ->with(['pasien', 'poli', 'dokter'])
                ->orderBy('status')
                ->latest('id')
                ->limit(10)
                ->get(),
            'pasienTerbaru' => Pasien::latest('id')->limit(5)->get(),
        ]);
    }

    private function dokter(Request $request): View
    {
        $dokter = $request->user()->dokter;

        $antrian = $dokter === null
            ? collect()
            : Kunjungan::hariIni()
                ->where('dokter_id', $dokter->id)
                ->with(['pasien', 'poli'])
                ->orderBy('status')
                ->latest('id')
                ->get();

        $rekamMedisTerbaru = RekamMedis::query()
            ->with(['kunjungan.pasien', 'kunjungan.dokter'])
            ->when(
                $dokter !== null,
                fn ($query) => $query->whereHas(
                    'kunjungan',
                    fn ($kunjungan) => $kunjungan->where('dokter_id', $dokter->id),
                ),
            )
            ->latest('id')
            ->limit(6)
            ->get();

        return view('dashboard.dokter', [
            'dokter' => $dokter,
            'statistik' => [
                'kunjunganHariIni' => $antrian->count(),
                'menungguHariIni' => $antrian->where('status', StatusKunjungan::Menunggu)->count(),
                'diprosesHariIni' => $antrian->where('status', StatusKunjungan::Diproses)->count(),
                'selesaiHariIni' => $antrian->where('status', StatusKunjungan::Selesai)->count(),
                'totalPasien' => $dokter === null
                    ? 0
                    : Kunjungan::where('dokter_id', $dokter->id)->distinct()->count('pasien_id'),
                'totalRekamMedis' => $rekamMedisTerbaru->count(),
            ],
            'antrian' => $antrian,
            'rekamMedisTerbaru' => $rekamMedisTerbaru,
        ]);
    }

    private function apoteker(): View
    {
        $obat = Obat::orderBy('nama')->get();
        $stokKritis = $obat->filter(fn (Obat $item): bool => $item->stok_kritis)->values();

        $resepTerbaru = RekamMedis::query()
            ->with(['kunjungan.pasien', 'kunjungan.dokter'])
            ->whereNotNull('resep')
            ->where('resep', '<>', '')
            ->latest('id')
            ->limit(8)
            ->get();

        return view('dashboard.apoteker', [
            'statistik' => [
                'totalObat' => $obat->count(),
                'stokKritis' => $stokKritis->count(),
                'nilaiStok' => $obat->sum(fn (Obat $item): int => $item->stok * $item->harga),
                'resepHariIni' => RekamMedis::whereDate('created_at', today())
                    ->whereNotNull('resep')
                    ->where('resep', '<>', '')
                    ->count(),
            ],
            'stokKritis' => $stokKritis,
            'resepTerbaru' => $resepTerbaru,
        ]);
    }
}
