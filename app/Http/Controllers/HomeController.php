<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke(Request $request): View
    {
        $polis = Poli::query()
            ->withCount('dokter')
            ->orderBy('nama')
            ->get();

        return view('landing', [
            'polis' => $polis,
            'totalDokter' => (int) $polis->sum('dokter_count'),
            'totalPoli' => $polis->count(),
        ]);
    }
}
