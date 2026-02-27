<?php

namespace App\Http\Controllers;

use App\Models\MealLog;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ImageService;

class CalorieController extends Controller
{
  /**
   * Halaman utama kalori — daftar log makanan hari ini
   */
  public function index()
  {
    $user = Auth::user();
    $today = Carbon::today();

    $todayMeals = MealLog::where('user_id', $user->id)
      ->where('tanggal', $today)
      ->where('status', 'confirmed')
      ->orderBy('created_at', 'desc')
      ->get();

    $totalKalori = $todayMeals->sum('total_kalori');
    $totalProtein = $todayMeals->sum('protein');
    $totalKarbo = $todayMeals->sum('karbohidrat');
    $totalLemak = $todayMeals->sum('lemak');

    return view('calorie.index', compact(
      'todayMeals',
      'totalKalori',
      'totalProtein',
      'totalKarbo',
      'totalLemak',
      'today'
    ));
  }

  /**
   * Form input manual
   */
  public function createManual()
  {
    return view('calorie.manual');
  }

  /**
   * Form input via foto
   */
  public function createPhoto()
  {
    return view('calorie.photo');
  }

  /**
   * Proses upload foto → analisis AI → redirect ke review
   */
  public function analyzePhoto(Request $request)
  {
    $request->validate([
      'foto_makanan' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
    ]);

    $user = Auth::user();
    $today = Carbon::today();

    // Simpan foto (compress dulu)
    $path = ImageService::compressAndStore($request->file('foto_makanan'), 'meal_photos', 200);
    $fullPath = Storage::disk('public')->path($path);

    // Analisis foto dengan Gemini
    $gemini = new GeminiService();
    $result = $gemini->analyzeFoodPhoto($fullPath);

    if (!$result || isset($result['error'])) {
      Storage::disk('public')->delete($path);
      $errorMsg = $result['error'] ?? 'Gagal menganalisis foto. Coba lagi atau gunakan input manual.';
      return back()->with('error', $errorMsg);
    }

    // Simpan sebagai draft
    $meal = MealLog::create([
      'user_id' => $user->id,
      'tanggal' => $today,
      'tipe_input' => 'foto',
      'nama_makanan' => $result['nama_makanan'] ?? 'Tidak diketahui',
      'metode_masak' => $result['metode_masak'] ?? '-',
      'bahan_bahan' => $result['bahan_bahan'] ?? [],
      'foto_makanan' => $path,
      'ai_raw_response' => $result,
      'status' => 'draft',
    ]);

    return redirect()->route('calorie.review', $meal->id);
  }

  /**
   * Proses input manual → simpan draft → redirect review
   */
  public function storeManual(Request $request)
  {
    $request->validate([
      'nama_makanan' => 'required|string|max:255',
      'metode_masak' => 'required|string|max:100',
      'bahan' => 'required|array|min:1',
      'bahan.*.nama' => 'required|string',
      'bahan.*.jumlah' => 'required|string',
      'bahan.*.satuan' => 'required|string',
    ]);

    $user = Auth::user();
    $today = Carbon::today();

    $meal = MealLog::create([
      'user_id' => $user->id,
      'tanggal' => $today,
      'tipe_input' => 'manual',
      'nama_makanan' => $request->nama_makanan,
      'metode_masak' => $request->metode_masak,
      'bahan_bahan' => $request->bahan,
      'status' => 'draft',
    ]);

    return redirect()->route('calorie.review', $meal->id);
  }

  /**
   * Review / Edit data makanan sebelum analisis kalori
   */
  public function review($id)
  {
    $meal = MealLog::where('user_id', Auth::id())->findOrFail($id);

    return view('calorie.review', compact('meal'));
  }

  /**
   * Update data review
   */
  public function updateReview(Request $request, $id)
  {
    $meal = MealLog::where('user_id', Auth::id())->findOrFail($id);

    $request->validate([
      'nama_makanan' => 'required|string|max:255',
      'metode_masak' => 'required|string|max:100',
      'bahan' => 'required|array|min:1',
      'bahan.*.nama' => 'required|string',
      'bahan.*.jumlah' => 'required|string',
      'bahan.*.satuan' => 'required|string',
    ]);

    $meal->update([
      'nama_makanan' => $request->nama_makanan,
      'metode_masak' => $request->metode_masak,
      'bahan_bahan' => $request->bahan,
    ]);

    return redirect()->route('calorie.review', $meal->id)->with('success', 'Data berhasil diperbarui!');
  }

  /**
   * Proses analisis kalori via AI
   */
  public function analyzeCalories($id)
  {
    $meal = MealLog::where('user_id', Auth::id())->findOrFail($id);

    $gemini = new GeminiService();
    $result = $gemini->analyzeCalories(
      $meal->nama_makanan,
      $meal->metode_masak,
      $meal->bahan_bahan ?? []
    );

    if (!$result || isset($result['error'])) {
      $errorMsg = $result['error'] ?? 'Gagal menganalisis kalori. Coba lagi nanti.';
      return back()->with('error', $errorMsg);
    }

    $meal->update([
      'total_kalori' => $result['total_kalori'] ?? 0,
      'protein' => $result['protein'] ?? 0,
      'karbohidrat' => $result['karbohidrat'] ?? 0,
      'lemak' => $result['lemak'] ?? 0,
      'serat' => $result['serat'] ?? 0,
      'ai_explanation' => $result['penjelasan'] ?? '',
      'ai_raw_response' => $result,
      'status' => 'confirmed',
    ]);

    return redirect()->route('calorie.result', $meal->id);
  }

  /**
   * Halaman hasil analisis
   */
  public function result($id)
  {
    $meal = MealLog::where('user_id', Auth::id())->findOrFail($id);

    return view('calorie.result', compact('meal'));
  }

  /**
   * Hapus meal log
   */
  public function destroy($id)
  {
    $meal = MealLog::where('user_id', Auth::id())->findOrFail($id);

    if ($meal->foto_makanan && Storage::disk('public')->exists($meal->foto_makanan)) {
      Storage::disk('public')->delete($meal->foto_makanan);
    }

    $meal->delete();

    return redirect()->route('calorie.index')->with('success', 'Log makanan berhasil dihapus.');
  }
}
