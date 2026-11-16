<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('role', 30)->default('registrasi')->change();
            $table->foreignId('dokter_id')->nullable()->after('role')->constrained('dokter')->nullOnDelete();
            $table->boolean('is_active')->default(true)->after('dokter_id')->index();
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
        });

        DB::table('users')
            ->where('role', 'petugas')
            ->update(['role' => 'registrasi']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['dokter_id']);
            $table->dropColumn(['dokter_id', 'is_active', 'last_login_at', 'last_login_ip']);
            $table->string('role', 30)->default('petugas')->change();
        });
    }
};
