<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::table('daily_records', function (Blueprint $table) {
      $table->string('foto_badan')->nullable()->after('langkah_kaki');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::table('daily_records', function (Blueprint $table) {
      $table->dropColumn('foto_badan');
    });
  }
};
