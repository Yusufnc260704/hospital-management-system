<?php

namespace App\Enums;

enum StatusKunjungan: string
{
    case Menunggu = 'menunggu';
    case Diproses = 'diproses';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            StatusKunjungan::Menunggu => 'Menunggu',
            StatusKunjungan::Diproses => 'Diproses',
            StatusKunjungan::Selesai => 'Selesai',
        };
    }
}
