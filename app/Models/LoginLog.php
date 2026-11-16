<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginLog extends Model
{
    public const BERHASIL = 'berhasil';

    public const GAGAL = 'gagal';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email',
        'role',
        'status',
        'alasan',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBerhasil(Builder $query): Builder
    {
        return $query->where('status', self::BERHASIL);
    }

    public function scopeGagal(Builder $query): Builder
    {
        return $query->where('status', self::GAGAL);
    }

    public function labelStatus(): string
    {
        return $this->status === self::BERHASIL ? 'Berhasil' : 'Gagal';
    }

    public function warnaStatus(): string
    {
        return $this->status === self::BERHASIL ? 'success' : 'danger';
    }
}
