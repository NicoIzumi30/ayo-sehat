<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealLog extends Model
{
  protected $fillable = [
    'user_id',
    'tanggal',
    'tipe_input',
    'nama_makanan',
    'metode_masak',
    'bahan_bahan',
    'foto_makanan',
    'total_kalori',
    'protein',
    'karbohidrat',
    'lemak',
    'serat',
    'ai_explanation',
    'ai_raw_response',
    'status',
  ];

  protected $casts = [
    'tanggal' => 'date',
    'bahan_bahan' => 'array',
    'ai_raw_response' => 'array',
    'total_kalori' => 'decimal:1',
    'protein' => 'decimal:1',
    'karbohidrat' => 'decimal:1',
    'lemak' => 'decimal:1',
    'serat' => 'decimal:1',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
