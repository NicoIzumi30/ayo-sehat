<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('meal_logs', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->date('tanggal');
      $table->enum('tipe_input', ['manual', 'foto'])->default('manual');
      $table->string('nama_makanan');
      $table->string('metode_masak')->nullable();
      $table->json('bahan_bahan')->nullable(); // [{nama, jumlah, satuan}]
      $table->string('foto_makanan')->nullable();
      $table->decimal('total_kalori', 8, 1)->nullable();
      $table->decimal('protein', 8, 1)->nullable();
      $table->decimal('karbohidrat', 8, 1)->nullable();
      $table->decimal('lemak', 8, 1)->nullable();
      $table->decimal('serat', 8, 1)->nullable();
      $table->text('ai_explanation')->nullable();
      $table->json('ai_raw_response')->nullable();
      $table->enum('status', ['draft', 'confirmed'])->default('draft');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('meal_logs');
  }
};
