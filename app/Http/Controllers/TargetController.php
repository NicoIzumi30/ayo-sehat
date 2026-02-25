<?php
namespace App\Http\Controllers;

use App\Models\Target;
use App\Models\TargetOlahraga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TargetController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    $target = $user->target;
    $workouts = $user->targetOlahraga()->where('is_active', true)->orderBy('urutan')->get();

    return view('target.index', compact('user', 'target', 'workouts'));
  }

  public function update(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'target_berat' => 'required|numeric|min:30|max:200',
      'target_lingkar_pinggang' => 'required|numeric|min:30|max:200',
      'target_langkah_harian' => 'required|integer|min:1000|max:100000',
    ]);

    Target::updateOrCreate(
      ['user_id' => $user->id],
      [
        'target_berat' => $request->target_berat,
        'target_lingkar_pinggang' => $request->target_lingkar_pinggang,
        'target_langkah_harian' => $request->target_langkah_harian,
      ]
    );

    // Update workout targets
    $workoutTargets = $request->input('target_workout', []);
    foreach ($workoutTargets as $id => $value) {
      TargetOlahraga::where('id', $id)->where('user_id', $user->id)
        ->update(['target_value' => $value]);
    }

    return redirect('/target')->with('success', 'Target berhasil diperbarui! 🎯');
  }
}
