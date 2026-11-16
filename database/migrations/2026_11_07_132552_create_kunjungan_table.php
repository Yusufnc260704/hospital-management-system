<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kunjungan', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien')->restrictOnDelete();
            $table->foreignId('poli_id')->constrained('poli')->restrictOnDelete();
            $table->foreignId('dokter_id')->constrained('dokter')->restrictOnDelete();
            $table->date('tanggal_kunjungan')->index();
            $table->text('keluhan');
            $table->string('status', 20)->index()->default('menunggu')->comment('menunggu, diproses, selesai');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
    }
};
