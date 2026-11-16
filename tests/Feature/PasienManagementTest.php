<?php

namespace Tests\Feature;

use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasienManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_menambah_pasien_dan_no_rm_diangenerate(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post('/pasien', [
            'nama' => 'Siti Maisaroh',
            'nik' => '3201010102850002',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '1985-01-02',
            'no_telp' => '081290001002',
        ])->assertRedirect();

        $pasien = Pasien::where('nik', '3201010102850002')->firstOrFail();

        $this->assertSame('RM-00001', $pasien->no_rm);
        $this->assertSame('Siti Maisaroh', $pasien->nama);
    }

    public function test_no_rm_lanjut_sementara_menuju_nomor_tertinggi(): void
    {
        Pasien::factory()->create();

        $this->actingAs(User::factory()->create())
            ->post('/pasien', [
                'nama' => 'Ahmad Fauzan',
                'nik' => '3201010103200003',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2020-03-01',
            ]);

        $this->assertSame('RM-00002', Pasien::where('nik', '3201010103200003')->firstOrFail()->no_rm);
    }

    public function test_admin_bisa_mengedit_data_pasien(): void
    {
        $pasien = Pasien::factory()->create();

        $this->actingAs(User::factory()->create())
            ->put("/pasien/{$pasien->id}", [
                'nama' => 'Nama Baru',
                'nik' => $pasien->nik,
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1990-06-15',
            ])->assertRedirect();

        $this->assertSame('Nama Baru', $pasien->fresh()->nama);
    }

    public function test_pasien_dengan_riwayat_kunjungan_tidak_bisa_dihapus(): void
    {
        $pasien = Pasien::factory()->has(Kunjungan::factory()->count(1))->create();

        $this->actingAs(User::factory()->create())
            ->delete("/pasien/{$pasien->id}")
            ->assertSessionHas('gagal');

        $this->assertDatabaseHas('pasien', ['id' => $pasien->id]);
    }
}
