<?php

namespace Tests\Feature;

use App\Enums\StatusKunjungan;
use App\Models\Dokter;
use App\Models\Kunjungan;
use App\Models\RekamMedis;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registrasi_bisa_mengelola_pasien_tetapi_tidak_manajemen_pengguna(): void
    {
        $user = User::factory()->registrasi()->create();

        $this->actingAs($user)->get('/pasien')->assertOk();
        $this->actingAs($user)->get('/pasien/create')->assertOk();
        $this->actingAs($user)->get('/pengguna')->assertForbidden();
        $this->actingAs($user)->get('/log-login')->assertForbidden();
    }

    public function test_dokter_hanya_bisa_melihat_pasien_dan_mengisi_rekam_medis(): void
    {
        $user = User::factory()->dokter()->create();

        $this->actingAs($user)->get('/pasien')->assertOk();
        $this->actingAs($user)->get('/pasien/create')->assertForbidden();
        $this->actingAs($user)->get('/rekam-medis')->assertOk();
        $this->actingAs($user)->get('/rekam-medis/create')->assertOk();
        $this->actingAs($user)->get('/obat/create')->assertForbidden();
        $this->actingAs($user)->get('/poli')->assertForbidden();
    }

    public function test_apoteker_tidak_bisa_mengakses_pendaftaran_dan_rekam_medis_kelola(): void
    {
        $user = User::factory()->apoteker()->create();

        $this->actingAs($user)->get('/obat')->assertOk();
        $this->actingAs($user)->get('/obat/create')->assertOk();
        $this->actingAs($user)->get('/kunjungan')->assertForbidden();
        $this->actingAs($user)->get('/rekam-medis')->assertOk();
        $this->actingAs($user)->get('/rekam-medis/create')->assertForbidden();
    }

    public function test_admin_bisa_membuka_seluruh_modul_administrasi(): void
    {
        $user = User::factory()->admin()->create();

        foreach (['/dashboard', '/pasien', '/poli', '/dokter', '/kunjungan', '/rekam-medis', '/obat', '/pengguna', '/log-login'] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
    }

    public function test_dokter_hanya_bisa_melihat_data_pasien_milikinya(): void
    {
        $dokterA = Dokter::factory()->create();
        $dokterB = Dokter::factory()->create();

        $user = User::factory()->dokter()->create(['dokter_id' => $dokterA->id]);

        $milikA = Kunjungan::factory()->create([
            'dokter_id' => $dokterA->id,
            'poli_id' => $dokterA->poli_id,
            'status' => StatusKunjungan::Diproses,
        ]);
        $milikB = Kunjungan::factory()->create([
            'dokter_id' => $dokterB->id,
            'poli_id' => $dokterB->poli_id,
            'status' => StatusKunjungan::Diproses,
        ]);

        $rekamA = RekamMedis::factory()->create(['kunjungan_id' => $milikA->id]);
        $rekamB = RekamMedis::factory()->create(['kunjungan_id' => $milikB->id]);

        $this->actingAs($user)->get('/kunjungan')
            ->assertOk()
            ->assertSee($milikA->pasien->nama)
            ->assertDontSee($milikB->pasien->nama);

        $this->actingAs($user)->get("/kunjungan/{$milikA->id}")->assertOk();
        $this->actingAs($user)->get("/kunjungan/{$milikB->id}")->assertForbidden();
        $this->actingAs($user)->get("/rekam-medis/{$rekamA->id}")->assertOk();
        $this->actingAs($user)->get("/rekam-medis/{$rekamB->id}")->assertForbidden();

        $this->actingAs($user)
            ->post('/rekam-medis', ['kunjungan_id' => $milikB->id, 'diagnosa' => 'Tes'])
            ->assertForbidden();
    }

    public function test_dashboard_menampilkan_tampilan_sesuai_peran(): void
    {
        $this->actingAs(User::factory()->admin()->create())
            ->get('/dashboard')->assertViewIs('dashboard.admin');

        $this->actingAs(User::factory()->registrasi()->create())
            ->get('/dashboard')->assertViewIs('dashboard.registrasi');

        $this->actingAs(User::factory()->dokter()->create())
            ->get('/dashboard')->assertViewIs('dashboard.dokter');

        $this->actingAs(User::factory()->apoteker()->create())
            ->get('/dashboard')->assertViewIs('dashboard.apoteker');
    }
}
