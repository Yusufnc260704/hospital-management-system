<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Permission;
use App\Enums\Role;
use App\Models\Concerns\LogsAudit;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'dokter_id', 'is_active', 'last_login_at', 'last_login_ip'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, LogsAudit, Notifiable;

    protected $auditLabelField = 'name';

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class);
    }

    public function roleEnum(): ?Role
    {
        return Role::tryFrom((string) $this->role);
    }

    public function hasRole(Role|string ...$roles): bool
    {
        $domain = array_map(
            fn (Role|string $role): string => $role instanceof Role ? $role->value : $role,
            $roles,
        );

        return in_array((string) $this->role, $domain, true);
    }

    public function hasPermission(Permission|string $permission): bool
    {
        $enum = $permission instanceof Permission ? $permission : Permission::tryFrom($permission);

        if ($enum === null) {
            return false;
        }

        return $this->roleEnum()?->punya($enum) ?? false;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(Role::Admin);
    }

    public function labelRole(): string
    {
        return $this->roleEnum()?->label() ?? ucfirst((string) $this->role);
    }
}
