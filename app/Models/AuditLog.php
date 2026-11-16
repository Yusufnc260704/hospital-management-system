<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class AuditLog extends Model
{
    public const DIBUAT = 'created';

    public const DIUBAH = 'updated';

    public const DIHAPUS = 'deleted';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_email',
        'user_role',
        'action',
        'auditable_type',
        'auditable_id',
        'label',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * @return array<string, string>
     */
    public static function modelOptions(): array
    {
        return [
            Pasien::class => 'Pasien',
            Kunjungan::class => 'Kunjungan',
            RekamMedis::class => 'Rekam Medis',
            Obat::class => 'Obat',
            Poli::class => 'Poli',
            Dokter::class => 'Dokter',
            User::class => 'Pengguna',
        ];
    }

    public static function labelModel(?string $type): string
    {
        if ($type === null) {
            return '-';
        }

        return self::modelOptions()[$type] ?? Str::headline(class_basename($type));
    }

    public function labelAksi(): string
    {
        return match ($this->action) {
            self::DIBUAT => 'Dibuat',
            self::DIUBAH => 'Diubah',
            self::DIHAPUS => 'Dihapus',
            default => ucfirst((string) $this->action),
        };
    }

    public function warnaAksi(): string
    {
        return match ($this->action) {
            self::DIBUAT => 'success',
            self::DIUBAH => 'warning',
            self::DIHAPUS => 'danger',
            default => 'secondary',
        };
    }

    public function ikonAksi(): string
    {
        return match ($this->action) {
            self::DIBUAT => 'bi-plus-circle',
            self::DIUBAH => 'bi-pencil-square',
            self::DIHAPUS => 'bi-trash',
            default => 'bi-dot',
        };
    }

    /**
     * Ringkasan singkat perubahan untuk ditampilkan di tabel.
     */
    public function ringkasan(): string
    {
        if ($this->action === self::DIBUAT) {
            return 'Data baru dibuat';
        }

        if ($this->action === self::DIHAPUS) {
            return 'Data dihapus';
        }

        $kunci = array_keys($this->new_values ?? []);

        if ($kunci === []) {
            return 'Tidak ada perubahan field';
        }

        $tampil = array_slice($kunci, 0, 3);
        $teks = implode(', ', $tampil);

        return count($kunci) > 3
            ? count($kunci).' field diubah: '.$teks.', ...'
            : count($kunci).' field diubah: '.$teks;
    }
}
