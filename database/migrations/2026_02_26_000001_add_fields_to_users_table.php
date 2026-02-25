<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nama_lengkap')->after('name')->nullable();
            $table->string('avatar_url', 500)->nullable()->after('password');
            $table->decimal('berat_awal', 5, 1)->nullable()->after('avatar_url');
            $table->date('tanggal_mulai_diet')->nullable()->after('berat_awal');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nama_lengkap', 'avatar_url', 'berat_awal', 'tanggal_mulai_diet']);
        });
    }
};
