<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalorieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\TargetController;
use Illuminate\Support\Facades\Route;

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
  Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
  Route::post('/login', [AuthController::class, 'login']);
  Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
  Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
  Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

  // Dashboard
  Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

  // Add Record
  Route::get('/add', [RecordController::class, 'create'])->name('record.create');
  Route::post('/add', [RecordController::class, 'store'])->name('record.store');

  // History
  Route::get('/history', [HistoryController::class, 'index'])->name('history');

  // Targets
  Route::get('/target', [TargetController::class, 'index'])->name('target');
  Route::post('/target', [TargetController::class, 'update'])->name('target.update');

  // Profile
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
  Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

  // Calorie Analysis
  Route::get('/calorie', [CalorieController::class, 'index'])->name('calorie.index');
  Route::get('/calorie/manual', [CalorieController::class, 'createManual'])->name('calorie.manual');
  Route::post('/calorie/manual', [CalorieController::class, 'storeManual'])->name('calorie.storeManual');
  Route::get('/calorie/photo', [CalorieController::class, 'createPhoto'])->name('calorie.photo');
  Route::post('/calorie/photo', [CalorieController::class, 'analyzePhoto'])->name('calorie.analyzePhoto');
  Route::get('/calorie/{id}/review', [CalorieController::class, 'review'])->name('calorie.review');
  Route::post('/calorie/{id}/review', [CalorieController::class, 'updateReview'])->name('calorie.updateReview');
  Route::post('/calorie/{id}/analyze', [CalorieController::class, 'analyzeCalories'])->name('calorie.analyze');
  Route::get('/calorie/{id}/result', [CalorieController::class, 'result'])->name('calorie.result');
  Route::delete('/calorie/{id}', [CalorieController::class, 'destroy'])->name('calorie.destroy');
});
