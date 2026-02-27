<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use HasFactory, Notifiable;

  protected $fillable = [
    'name',
    'nama_lengkap',
    'email',
    'password',
    'avatar_url',
    'berat_awal',
    'tanggal_mulai_diet',
  ];

  protected $hidden = [
    'password',
    'remember_token',
  ];

  protected function casts(): array
  {
    return [
      'email_verified_at' => 'datetime',
      'password' => 'hashed',
      'berat_awal' => 'decimal:1',
      'tanggal_mulai_diet' => 'date',
    ];
  }

  public function target()
  {
    return $this->hasOne(Target::class);
  }

  public function habitMasters()
  {
    return $this->hasMany(HabitMaster::class);
  }

  public function targetOlahraga()
  {
    return $this->hasMany(TargetOlahraga::class);
  }

  public function dailyRecords()
  {
    return $this->hasMany(DailyRecord::class);
  }

  public function mealLogs()
  {
    return $this->hasMany(MealLog::class);
  }

  public function getDisplayNameAttribute(): string
  {
    return $this->nama_lengkap ?: $this->name;
  }
}
