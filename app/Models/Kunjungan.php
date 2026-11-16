<?php

namespace App\Models;

use App\Enums\StatusKunjungan;
use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kunjungan extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'kunjungan';

    protected $fillable = [
        'pasien_id',
        'poli_id',
        'dokter_id',
        'tanggal_kunjungan',
        'keluhan',
        'status',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'status' => StatusKunjungan::class,
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    public function poli(): BelongsTo
    {
        return $this->belongsTo(Poli::class);
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function rekamMedis(): HasOne
    {
        return $this->hasOne(RekamMedis::class);
    }

    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('tanggal_kunjungan', today());
    }

    public function scopeStatus(Builder $query, StatusKunjungan $status): Builder
    {
        return $query->where('status', $status);
    }
}
