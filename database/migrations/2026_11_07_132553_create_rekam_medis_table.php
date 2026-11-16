<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('kunjungan_id')->unique()->constrained('kunjungan')->restrictOnDelete();
            $table->text('diagnosa');
            $table->text('tindakan')->nullable();
            $table->text('resep')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
