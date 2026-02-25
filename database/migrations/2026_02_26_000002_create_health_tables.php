<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('targets', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
      $table->decimal('target_berat', 5, 1)->default(80.0);
      $table->decimal('target_lingkar_pinggang', 5, 1)->default(85.0);
      $table->unsignedInteger('target_langkah_harian')->default(10000);
      $table->timestamps();
    });

    Schema::create('habit_master', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('nama_habit', 150);
      $table->boolean('is_active')->default(true);
      $table->unsignedInteger('urutan')->default(0);
      $table->timestamps();
      $table->index('user_id');
    });

    Schema::create('target_olahraga', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('nama_olahraga', 150);
      $table->unsignedInteger('target_value')->default(0);
      $table->enum('satuan', ['x', 'mnt'])->default('x');
      $table->unsignedInteger('urutan')->default(0);
      $table->boolean('is_active')->default(true);
      $table->timestamps();
      $table->index('user_id');
    });

    Schema::create('daily_records', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->date('tanggal');
      $table->decimal('berat_badan', 5, 1)->nullable();
      $table->decimal('lingkar_pinggang', 5, 1)->nullable();
      $table->unsignedInteger('langkah_kaki')->nullable();
      $table->text('catatan')->nullable();
      $table->timestamps();
      $table->unique(['user_id', 'tanggal']);
      $table->index('tanggal');
    });

    Schema::create('daily_habits', function (Blueprint $table) {
      $table->id();
      $table->foreignId('daily_record_id')->constrained('daily_records')->cascadeOnDelete();
      $table->foreignId('habit_master_id')->constrained('habit_master')->cascadeOnDelete();
      $table->boolean('is_checked')->default(false);
      $table->timestamp('created_at')->nullable();
      $table->unique(['daily_record_id', 'habit_master_id']);
    });

    Schema::create('daily_workouts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('daily_record_id')->constrained('daily_records')->cascadeOnDelete();
      $table->foreignId('target_olahraga_id')->constrained('target_olahraga')->cascadeOnDelete();
      $table->unsignedInteger('value')->default(0);
      $table->timestamp('created_at')->nullable();
      $table->unique(['daily_record_id', 'target_olahraga_id']);
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('daily_workouts');
    Schema::dropIfExists('daily_habits');
    Schema::dropIfExists('daily_records');
    Schema::dropIfExists('target_olahraga');
    Schema::dropIfExists('habit_master');
    Schema::dropIfExists('targets');
  }
};
