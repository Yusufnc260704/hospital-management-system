<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenggunaManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_membuat_akun_baru(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/pengguna', [
                'name' => 'Petugas Baru',
                'email' => 'petugas@rsdrjth.test',
                'role' => 'registrasi',
                'is_active' => '1',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertRedirectToRoute('pengguna.index');

        $this->assertDatabaseHas('users', [
            'email' => 'petugas@rsdrjth.test',
            'role' => 'registrasi',
            'is_active' => true,
        ]);
    }

    public function test_akun_dokter_wajib_ditautkan_ke_data_dokter(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post('/pengguna', [
                'name' => 'Dokter Baru',
                'email' => 'dokter.baru@rsdrjth.test',
                'role' => 'dokter',
                'is_active' => '1',
                'password' => 'rahasia123',
                'password_confirmation' => 'rahasia123',
            ])
            ->assertSessionHasErrors('dokter_id');
    }

    public function test_admin_tidak_bisa_menghapus_akun_sendiri(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->delete("/pengguna/{$admin->id}")
            ->assertSessionHas('gagal');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_administrator_terakhir_tidak_bisa_dinonaktifkan(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->put("/pengguna/{$admin->id}", [
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => 'admin',
            ])
            ->assertSessionHas('gagal');

        $this->assertTrue($admin->fresh()->is_active);
    }

    public function test_registrasi_tidak_bisa_mengakses_manajemen_pengguna(): void
    {
        $user = User::factory()->registrasi()->create();

        $this->actingAs($user)->get('/pengguna')->assertForbidden();
        $this->actingAs($user)->post('/pengguna', [])->assertForbidden();
    }
}
