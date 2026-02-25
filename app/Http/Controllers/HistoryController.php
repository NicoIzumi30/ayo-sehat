<?php
namespace App\Http\Controllers;

use App\Models\DailyRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    $month = $request->input('month', Carbon::now()->month);
    $year = $request->input('year', Carbon::now()->year);
    $today = Carbon::today();

    $records = DailyRecord::where('user_id', $user->id)
      ->whereMonth('tanggal', $month)
      ->whereYear('tanggal', $year)
      ->with(['habits.habitMaster', 'workouts.targetOlahraga'])
      ->orderBy('tanggal', 'desc')
      ->get();

    $currentMonth = Carbon::createFromDate($year, $month, 1);

    return view('history.index', compact('records', 'today', 'currentMonth'));
  }
}
