<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokter', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('poli_id')->constrained('poli')->restrictOnDelete();
            $table->string('nama', 150);
            $table->string('spesialisasi', 100)->nullable();
            $table->string('no_telp', 20)->nullable();
            $table->timestamps();

            $table->index('nama');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokter');
    }
};
