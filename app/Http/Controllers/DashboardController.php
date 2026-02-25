<?php
namespace App\Http\Controllers;

use App\Models\DailyRecord;
use App\Models\DailyHabit;
use App\Models\DailyWorkout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $today = Carbon::today();
    $target = $user->target;

    // Today's record
    $todayRecord = DailyRecord::where('user_id', $user->id)->where('tanggal', $today)->first();

    // Habits today
    $habitsToday = collect();
    $habitsChecked = 0;
    $habitsTotal = 0;
    if ($todayRecord) {
      $habitsToday = DailyHabit::where('daily_record_id', $todayRecord->id)
        ->with('habitMaster')->get();
      $habitsChecked = $habitsToday->where('is_checked', true)->count();
      $habitsTotal = $habitsToday->count();
    }

    // Workouts today
    $workoutsToday = collect();
    if ($todayRecord) {
      $workoutsToday = DailyWorkout::where('daily_record_id', $todayRecord->id)
        ->with('targetOlahraga')->get();
    }

    // Workout targets for progress
    $targetWorkouts = $user->targetOlahraga()->where('is_active', true)->orderBy('urutan')->get();

    // Weight trend (last 7 days)
    $weightTrend = DailyRecord::where('user_id', $user->id)
      ->whereNotNull('berat_badan')
      ->orderBy('tanggal', 'desc')
      ->limit(7)
      ->get()
      ->reverse()
      ->values();

    // Calculate streak
    $streak = $this->calculateStreak($user->id);

    return view('dashboard', compact(
      'user',
      'today',
      'target',
      'todayRecord',
      'habitsToday',
      'habitsChecked',
      'habitsTotal',
      'workoutsToday',
      'targetWorkouts',
      'weightTrend',
      'streak'
    ));
  }

  private function calculateStreak(int $userId): int
  {
    $records = DailyRecord::where('user_id', $userId)
      ->orderBy('tanggal', 'desc')
      ->pluck('tanggal');

    if ($records->isEmpty())
      return 0;

    $streak = 0;
    $expectedDate = Carbon::today();

    foreach ($records as $date) {
      $recordDate = Carbon::parse($date);
      if ($recordDate->eq($expectedDate)) {
        $streak++;
        $expectedDate = $expectedDate->subDay();
      } elseif ($recordDate->lt($expectedDate)) {
        break;
      }
    }

    return $streak;
  }
}
