<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // ✅ Add this line
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});


// 🧱 ADMIN SECTION
// 🔐 Custom Admin Login
Route::get('/login', function () {
    return view('admin.auth.login');
})->middleware('guest')->name('login');

// 🔓 Logout (handled by Breeze controller)
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// 🧱 Admin Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard'); // your main admin page
    })->name('admin.dashboard');

    // 👇 Add alias so Breeze redirect works
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');
});
// 👤 Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🧩 Breeze authentication routes (login, register, etc.)
require __DIR__ . '/auth.php';

// ✅ override Breeze login view
Route::get('/login', function () {
    return view('admin.auth.login');
})->middleware('guest')->name('login');
