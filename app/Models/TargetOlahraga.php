<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TargetOlahraga extends Model
{
  protected $table = 'target_olahraga';
  protected $fillable = ['user_id', 'nama_olahraga', 'target_value', 'satuan', 'urutan', 'is_active'];
  protected $casts = ['is_active' => 'boolean'];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
