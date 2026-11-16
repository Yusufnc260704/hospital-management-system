<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poli extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'poli';

    protected $fillable = [
        'nama',
        'deskripsi',
    ];

    public function dokter(): HasMany
    {
        return $this->hasMany(Dokter::class);
    }

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class);
    }
}
