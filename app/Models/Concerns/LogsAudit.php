<?php

namespace App\Models\Concerns;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * Mencatat setiap perubahan data (buat/ubah/hapus) ke tabel audit_logs.
 *
 * Model dapat menyesuaikan:
 * - $auditLabelField  : kolom yang dipakai sebagai label (default: nama)
 * - $auditDikecualikan: daftar kolom yang tidak perlu dicatat
 */
trait LogsAudit
{
    public static function bootLogsAudit(): void
    {
        static::created(fn (Model $model) => $model->catatAudit(AuditLog::DIBUAT));
        static::updated(fn (Model $model) => $model->catatAudit(AuditLog::DIUBAH));
        static::deleted(fn (Model $model) => $model->catatAudit(AuditLog::DIHAPUS));
    }

    protected function catatAudit(string $aksi): void
    {
        // Seeder / perintah artisan tidak perlu mengotori audit trail.
        // Saat tes tetap dicatat agar perilakunya bisa diuji.
        if (app()->runningInConsole() && ! app()->runningUnitTests()) {
            return;
        }

        $dikecualikan = array_unique(array_merge([
            'created_at',
            'updated_at',
            'deleted_at',
            'password',
            'remember_token',
            'last_login_at',
            'last_login_ip',
        ], $this->auditDikecualikan ?? []));

        if ($aksi === AuditLog::DIUBAH) {
            $perubahan = Arr::except($this->getChanges(), $dikecualikan);

            if ($perubahan === []) {
                return;
            }

            $lama = [];
            foreach (array_keys($perubahan) as $kunci) {
                $lama[$kunci] = $this->getOriginal($kunci);
            }
            $baru = $perubahan;
        } elseif ($aksi === AuditLog::DIBUAT) {
            $lama = null;
            $baru = Arr::except($this->getAttributes(), $dikecualikan);
        } else {
            $lama = Arr::except($this->getOriginal(), $dikecualikan);
            $baru = null;
        }

        $pengguna = auth()->user();
        $permintaan = request();

        AuditLog::create([
            'user_id' => $pengguna?->getKey(),
            'user_name' => $pengguna?->name,
            'user_email' => $pengguna?->email,
            'user_role' => $pengguna?->role,
            'action' => $aksi,
            'auditable_type' => $this->getMorphClass(),
            'auditable_id' => $this->getKey(),
            'label' => $this->labelAudit(),
            'old_values' => $lama,
            'new_values' => $baru,
            'ip_address' => $permintaan?->ip(),
            'user_agent' => Str::limit((string) $permintaan?->userAgent(), 500),
            'created_at' => now(),
        ]);
    }

    public function labelAudit(): string
    {
        $field = $this->auditLabelField ?? 'nama';
        $nilai = $field === null ? null : $this->getAttribute($field);
        $label = AuditLog::labelModel(static::class);

        return $nilai !== null && $nilai !== ''
            ? $label.': '.$nilai
            : $label.' #'.$this->getKey();
    }
}
