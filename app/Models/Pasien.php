<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'pasien';

    protected $fillable = [
        'no_rm',
        'nama',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telp',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function kunjungan(): HasMany
    {
        return $this->hasMany(Kunjungan::class)->orderByDesc('tanggal_kunjungan');
    }

    protected function umur(): Attribute
    {
        return Attribute::make(
            get: fn (): int => Carbon::parse($this->tanggal_lahir)->age,
        );
    }
}
