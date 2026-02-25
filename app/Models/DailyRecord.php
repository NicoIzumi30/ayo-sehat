<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
  protected $fillable = ['user_id', 'tanggal', 'berat_badan', 'lingkar_pinggang', 'langkah_kaki', 'catatan'];
  protected $casts = ['tanggal' => 'date', 'berat_badan' => 'decimal:1', 'lingkar_pinggang' => 'decimal:1'];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
  public function habits()
  {
    return $this->hasMany(DailyHabit::class, 'daily_record_id');
  }
  public function workouts()
  {
    return $this->hasMany(DailyWorkout::class, 'daily_record_id');
  }

  public function habitsCheckedCount(): int
  {
    return $this->habits()->where('is_checked', true)->count();
  }

  public function habitsTotalCount(): int
  {
    return $this->habits()->count();
  }
}
