<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HabitMaster extends Model
{
  protected $table = 'habit_master';
  protected $fillable = ['user_id', 'nama_habit', 'is_active', 'urutan'];
  protected $casts = ['is_active' => 'boolean'];
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
