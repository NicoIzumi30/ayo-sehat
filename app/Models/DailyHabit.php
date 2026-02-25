<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyHabit extends Model
{
  protected $fillable = ['daily_record_id', 'habit_master_id', 'is_checked'];
  protected $casts = ['is_checked' => 'boolean'];
  public $timestamps = false;
  const CREATED_AT = 'created_at';

  public function record()
  {
    return $this->belongsTo(DailyRecord::class, 'daily_record_id');
  }
  public function habitMaster()
  {
    return $this->belongsTo(HabitMaster::class, 'habit_master_id');
  }
}
