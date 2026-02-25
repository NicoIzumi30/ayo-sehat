<?php

use App\Http\Controllers\AuthController;
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
});
