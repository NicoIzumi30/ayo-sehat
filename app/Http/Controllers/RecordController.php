<?php
namespace App\Http\Controllers;

use App\Models\DailyRecord;
use App\Models\DailyHabit;
use App\Models\DailyWorkout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
  public function create()
  {
    $user = Auth::user();
    $today = Carbon::today();
    $habits = $user->habitMasters()->where('is_active', true)->orderBy('urutan')->get();
    $workouts = $user->targetOlahraga()->where('is_active', true)->orderBy('urutan')->get();

    // Check if record exists for today (for editing)
    $existingRecord = DailyRecord::where('user_id', $user->id)->where('tanggal', $today)->first();
    $existingHabits = [];
    $existingWorkouts = [];

    if ($existingRecord) {
      $existingHabits = DailyHabit::where('daily_record_id', $existingRecord->id)
        ->pluck('is_checked', 'habit_master_id')->toArray();
      $existingWorkouts = DailyWorkout::where('daily_record_id', $existingRecord->id)
        ->pluck('value', 'target_olahraga_id')->toArray();
    }

    return view('record.create', compact(
      'today',
      'habits',
      'workouts',
      'existingRecord',
      'existingHabits',
      'existingWorkouts'
    ));
  }

  public function store(Request $request)
  {
    $user = Auth::user();
    $today = Carbon::today();

    $request->validate([
      'berat_badan' => 'nullable|numeric|min:30|max:300',
      'lingkar_pinggang' => 'nullable|numeric|min:30|max:200',
      'langkah_kaki' => 'nullable|integer|min:0|max:100000',
      'foto_badan' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    ]);

    // Create or update daily record
    $record = DailyRecord::updateOrCreate(
      ['user_id' => $user->id, 'tanggal' => $today],
      [
        'berat_badan' => $request->berat_badan,
        'lingkar_pinggang' => $request->lingkar_pinggang,
        'langkah_kaki' => $request->langkah_kaki,
      ]
    );

    // Handle foto_badan upload
    if ($request->hasFile('foto_badan')) {
      if ($record->foto_badan && \Illuminate\Support\Facades\Storage::disk('public')->exists($record->foto_badan)) {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($record->foto_badan);
      }
      $path = $request->file('foto_badan')->store('foto_badan', 'public');
      $record->update(['foto_badan' => $path]);
    }

    // Auto-set user's berat_awal and tanggal_mulai_diet on first record
    if (!$user->berat_awal && $request->berat_badan) {
      $user->update([
        'berat_awal' => $request->berat_badan,
        'tanggal_mulai_diet' => $today,
      ]);
    }

    // Save habits
    DailyHabit::where('daily_record_id', $record->id)->delete();
    $habits = $user->habitMasters()->where('is_active', true)->get();
    $checkedHabits = $request->input('habits', []);

    foreach ($habits as $habit) {
      DailyHabit::create([
        'daily_record_id' => $record->id,
        'habit_master_id' => $habit->id,
        'is_checked' => in_array($habit->id, $checkedHabits),
      ]);
    }

    // Save workouts
    DailyWorkout::where('daily_record_id', $record->id)->delete();
    $workoutValues = $request->input('workout', []);
    foreach ($workoutValues as $targetId => $value) {
      if ($value > 0) {
        DailyWorkout::create([
          'daily_record_id' => $record->id,
          'target_olahraga_id' => $targetId,
          'value' => $value,
        ]);
      }
    }

    return redirect('/')->with('success', 'Data hari ini berhasil disimpan! 🎉');
  }
}
