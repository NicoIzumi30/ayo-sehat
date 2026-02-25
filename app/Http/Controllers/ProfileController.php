<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
  public function index()
  {
    $user = Auth::user();
    return view('profile.index', compact('user'));
  }

  public function update(Request $request)
  {
    $user = Auth::user();

    $request->validate([
      'nama_lengkap' => 'required|string|max:255',
      'email' => 'required|email|unique:users,email,' . $user->id,
      'password_baru' => 'nullable|min:6',
    ]);

    $user->update([
      'name' => $request->nama_lengkap,
      'nama_lengkap' => $request->nama_lengkap,
      'email' => $request->email,
    ]);

    if ($request->filled('password_baru')) {
      $user->update(['password' => Hash::make($request->password_baru)]);
    }

    return redirect('/profile')->with('success', 'Profil berhasil diperbarui! ✨');
  }
}
