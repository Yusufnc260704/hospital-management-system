<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $logs = AuditLog::query()
            ->when(
                $request->filled('q'),
                function (Builder $query) use ($request): void {
                    $cari = '%'.$request->string('q')->trim().'%';

                    $query->where(function (Builder $sub) use ($cari): void {
                        $sub->where('user_name', 'like', $cari)
                            ->orWhere('user_email', 'like', $cari)
                            ->orWhere('label', 'like', $cari)
                            ->orWhere('ip_address', 'like', $cari);
                    });
                },
            )
            ->when(
                $request->filled('action'),
                fn (Builder $query) => $query->where('action', $request->input('action')),
            )
            ->when(
                $request->filled('model'),
                fn (Builder $query) => $query->where('auditable_type', $request->input('model')),
            )
            ->latest('created_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return view('audit-log.index', [
            'logs' => $logs,
            'q' => $request->input('q', ''),
            'aksiTerpilih' => (string) $request->input('action', ''),
            'modelTerpilih' => (string) $request->input('model', ''),
            'modelOptions' => AuditLog::modelOptions(),
            'ringkasan' => [
                'dibuat' => AuditLog::hariIni()->where('action', AuditLog::DIBUAT)->count(),
                'diubah' => AuditLog::hariIni()->where('action', AuditLog::DIUBAH)->count(),
                'dihapus' => AuditLog::hariIni()->where('action', AuditLog::DIHAPUS)->count(),
            ],
        ]);
    }
}
