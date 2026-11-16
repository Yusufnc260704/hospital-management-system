<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasien', function (Blueprint $table): void {
            $table->id();
            $table->string('no_rm', 20)->unique();
            $table->string('nama', 150);
            $table->string('nik', 16)->unique();
            $table->char('jenis_kelamin', 1)->comment('L/Laki-laki, P/Perempuan');
            $table->date('tanggal_lahir');
            $table->string('alamat', 255)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->timestamps();

            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
