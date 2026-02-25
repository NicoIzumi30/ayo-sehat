<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DailyWorkout extends Model
{
  protected $fillable = ['daily_record_id', 'target_olahraga_id', 'value'];
  public $timestamps = false;
  const CREATED_AT = 'created_at';

  public function record()
  {
    return $this->belongsTo(DailyRecord::class, 'daily_record_id');
  }
  public function targetOlahraga()
  {
    return $this->belongsTo(TargetOlahraga::class, 'target_olahraga_id');
  }
}
