<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
  protected $fillable = ['user_id', 'target_berat', 'target_lingkar_pinggang', 'target_langkah_harian'];
  protected $casts = ['target_berat' => 'decimal:1', 'target_lingkar_pinggang' => 'decimal:1'];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
