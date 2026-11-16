<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_dibuka(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertViewIs('auth.login');
    }

    public function test_halaman_utama_menampilkan_profil_rumah_sakit(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertViewIs('landing');
        $response->assertSee('Rumah Sakit dr. Tjahyo');
        $response->assertSee(route('login'));
    }

    public function test_admin_bisa_login_dan_melihat_dashboard(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@rsdrjth.test',
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@rsdrjth.test',
            'password' => 'password',
        ]);

        $response->assertRedirectToRoute('dashboard');
        $this->assertAuthenticatedAs($user, 'web');

        $response = $this->get('/dashboard');
        $response->assertOk();
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->create([
            'email' => 'admin@rsdrjth.test',
        ]);

        $response = $this->post('/login', [
            'email' => 'admin@rsdrjth.test',
            'password' => 'salah-totally-wrong',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_admin_bisa_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirectToRoute('login');
    }

    public function test_akun_nonaktif_tidak_bisa_login(): void
    {
        $user = User::factory()->nonaktif()->create([
            'email' => 'nonaktif@rsdrjth.test',
        ]);

        $response = $this->post('/login', [
            'email' => 'nonaktif@rsdrjth.test',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');

        $this->assertDatabaseHas('login_logs', [
            'user_id' => $user->id,
            'email' => 'nonaktif@rsdrjth.test',
            'status' => 'gagal',
            'alasan' => 'Akun dinonaktifkan',
        ]);
    }

    public function test_login_berhasil_tercatat_di_log_dan_memperbarui_waktu_login(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@rsdrjth.test',
        ]);

        $this->post('/login', [
            'email' => 'admin@rsdrjth.test',
            'password' => 'password',
        ])->assertRedirectToRoute('dashboard');

        $this->assertDatabaseHas('login_logs', [
            'user_id' => $user->id,
            'email' => 'admin@rsdrjth.test',
            'status' => 'berhasil',
        ]);

        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_percobaan_login_dibatasi_setelah_lima_kali_gagal(): void
    {
        User::factory()->create([
            'email' => 'admin@rsdrjth.test',
        ]);

        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'admin@rsdrjth.test',
                'password' => 'password-salah',
            ])->assertSessionHasErrors('email');
        }

        $response = $this->post('/login', [
            'email' => 'admin@rsdrjth.test',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString(
            'Terlalu banyak percobaan',
            session('errors')->first('email'),
        );
    }
}
