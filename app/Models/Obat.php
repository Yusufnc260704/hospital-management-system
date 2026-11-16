<?php

namespace App\Models;

use App\Models\Concerns\LogsAudit;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obat extends Model
{
    use HasFactory, LogsAudit;

    protected $table = 'obat';

    protected $fillable = [
        'nama',
        'jenis',
        'satuan',
        'stok',
        'stok_minimum',
        'harga',
    ];

    protected $casts = [
        'stok' => 'integer',
        'stok_minimum' => 'integer',
        'harga' => 'integer',
    ];

    protected function stokKritis(): Attribute
    {
        return Attribute::make(
            get: fn (): bool => $this->stok <= $this->stok_minimum,
        );
    }
}
