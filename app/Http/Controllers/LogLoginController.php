<?php

namespace App\Http\Controllers;

use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogLoginController extends Controller
{
    public function index(Request $request): View
    {
        $logs = LoginLog::query()
            ->with('user')
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where('status', $request->input('status')),
            )
            ->when(
                $request->filled('q'),
                fn ($query) => $query->where('email', 'like', '%'.$request->string('q')->trim().'%'),
            )
            ->latest('created_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('log-login.index', [
            'logs' => $logs,
            'q' => $request->input('q', ''),
            'statusTerpilih' => (string) $request->input('status', ''),
            'ringkasan' => [
                'berhasil' => LoginLog::berhasil()->whereDate('created_at', today())->count(),
                'gagal' => LoginLog::gagal()->whereDate('created_at', today())->count(),
            ],
        ]);
    }
}
