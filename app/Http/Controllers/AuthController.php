<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Target;
use App\Models\HabitMaster;
use App\Models\TargetOlahraga;

class AuthController extends Controller
{
  public function showLogin()
  {
    return view('auth.login');
  }

  public function login(Request $request)
  {
    $credentials = $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
      $request->session()->regenerate();
      return redirect()->intended('/');
    }

    return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
  }

  public function showRegister()
  {
    return view('auth.register');
  }

  public function register(Request $request)
  {
    $request->validate([
      'nama_lengkap' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|min:6|confirmed',
    ]);

    $user = User::create([
      'name' => $request->nama_lengkap,
      'nama_lengkap' => $request->nama_lengkap,
      'email' => $request->email,
      'password' => Hash::make($request->password),
    ]);

    // Create default targets
    Target::create([
      'user_id' => $user->id,
      'target_berat' => 80.0,
      'target_lingkar_pinggang' => 85.0,
      'target_langkah_harian' => 10000,
    ]);

    // Create default habits
    $habits = [
      'Skip Minuman Manis',
      'Nggak Ngemil Sembarangan',
      'Karbo Terkontrol',
      'Tidur Cukup',
      'Air Putih 2 Liter',
      'No Sugar After Dinner'
    ];
    foreach ($habits as $i => $habit) {
      HabitMaster::create(['user_id' => $user->id, 'nama_habit' => $habit, 'urutan' => $i + 1]);
    }

    // Create default workout targets
    $workouts = [
      ['Incline Push Up', 15, 'x'],
      ['Bodyweight Squat', 20, 'x'],
      ['Sit Up', 20, 'x'],
      ['Jalan Kaki', 30, 'mnt'],
      ['Hip Flexor Raises', 15, 'x'],
      ['Knee Lift & Press', 15, 'x'],
      ['Lari Dumbbell', 15, 'mnt'],
    ];
    foreach ($workouts as $i => $w) {
      TargetOlahraga::create([
        'user_id' => $user->id,
        'nama_olahraga' => $w[0],
        'target_value' => $w[1],
        'satuan' => $w[2],
        'urutan' => $i + 1,
      ]);
    }

    Auth::login($user);
    return redirect('/');
  }

  public function logout(Request $request)
  {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
  }
}
