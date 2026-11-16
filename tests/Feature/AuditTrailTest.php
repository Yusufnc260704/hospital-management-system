<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditTrailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @param  array<string, mixed>  $ubah
     * @return array<string, mixed>
     */
    private function dataPasien(array $ubah = []): array
    {
        return array_merge([
            'nama' => 'Budi Santoso',
            'nik' => '3273010101900001',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1990-01-01',
            'alamat' => 'Jl. Merdeka No. 1',
            'no_telp' => '081234567890',
        ], $ubah);
    }

    public function test_pembuatan_pasien_tercatat_di_audit_trail(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/pasien', $this->dataPasien())
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', AuditLog::DIBUAT)
            ->where('auditable_type', Pasien::class)
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame($admin->id, $log->user_id);
        $this->assertSame('Budi Santoso', $log->new_values['nama']);
        $this->assertNull($log->old_values);
    }

    public function test_perubahan_pasien_mencatat_nilai_lama_dan_baru(): void
    {
        $pasien = Pasien::factory()->create(['nama' => 'Nama Lama']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put("/pasien/{$pasien->id}", $this->dataPasien([
                'nama' => 'Nama Baru',
                'nik' => $pasien->nik,
            ]))
            ->assertRedirect();

        $log = AuditLog::query()
            ->where('action', AuditLog::DIUBAH)
            ->where('auditable_type', Pasien::class)
            ->where('auditable_id', $pasien->id)
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('Nama Lama', $log->old_values['nama']);
        $this->assertSame('Nama Baru', $log->new_values['nama']);
    }

    public function test_penghapusan_pasien_tercatat(): void
    {
        $pasien = Pasien::factory()->create(['nama' => 'Akan Dihapus']);
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/pasien/{$pasien->id}")
            ->assertRedirectToRoute('pasien.index');

        $log = AuditLog::query()
            ->where('action', AuditLog::DIHAPUS)
            ->where('auditable_type', Pasien::class)
            ->where('auditable_id', $pasien->id)
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('Akan Dihapus', $log->old_values['nama']);
        $this->assertNull($log->new_values);
    }

    public function test_password_pengguna_tidak_disimpan_di_audit_trail(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/pengguna', [
                'name' => 'Petugas Baru',
                'email' => 'petugas.baru@rsdrjth.test',
                'role' => 'registrasi',
                'is_active' => '1',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertRedirectToRoute('pengguna.index');

        $log = AuditLog::query()
            ->where('action', AuditLog::DIBUAT)
            ->where('auditable_type', User::class)
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertArrayNotHasKey('password', $log->new_values);
        $this->assertArrayNotHasKey('remember_token', $log->new_values);
        $this->assertSame('petugas.baru@rsdrjth.test', $log->new_values['email']);
    }

    public function test_login_tidak_membuat_audit_trail_perubahan_pengguna(): void
    {
        $user = User::factory()->create(['email' => 'admin@rsdrjth.test']);

        $this->post('/login', [
            'email' => 'admin@rsdrjth.test',
            'password' => 'password',
        ])->assertRedirectToRoute('dashboard');

        $this->assertDatabaseMissing('audit_logs', [
            'action' => AuditLog::DIUBAH,
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
        ]);
    }

    public function test_hanya_admin_yang_bisa_membuka_halaman_audit_trail(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/audit-log')
            ->assertOk()
            ->assertViewIs('audit-log.index');

        $this->actingAs(User::factory()->registrasi()->create())
            ->get('/audit-log')
            ->assertForbidden();
    }
}
