<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\BobotKriteriaController;
use App\Http\Controllers\NilaiAlternatifController;
use App\Http\Controllers\PerhitunganController;
use App\Http\Controllers\RekomendasiController;

use App\Http\Controllers\CoprasController;

use App\Http\Controllers\AuthMahasiswaController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('home');
})->name('home');

// Route::view('dashboard', 'dashboard')
//     ->middleware(['auth', 'verified'])
//     ->name('dashboard');

// Route::middleware(['auth'])->group(function () {
//     Route::redirect('settings', 'settings/profile');

//     Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
//     Volt::route('settings/password', 'settings.password')->name('settings.password');
//     Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
// });

// form copras
Route::get('/form', [SPKController::class, 'showForm'])->name('spk.form');
Route::get('/hasil', [SPKController::class, 'showHasil'])->name('spk.hasil');

Route::get('/transportasi', [TransportasiController::class, 'index']);
Route::get('/kriteria', [KriteriaController::class, 'index']);
Route::post('/bobot-kriteria', [BobotKriteriaController::class, 'store']);
Route::post('/nilai-alternatif', [NilaiAlternatifController::class, 'store']);
Route::post('/perhitungan', [PerhitunganController::class, 'store']);

// hitung copras
Route::post('/hitung-copras', [CoprasController::class, 'hitung']);
Route::get('/hasil-rekomendasi/{id_perhitungan}', [RekomendasiController::class, 'show'])->name('hasil-rekomendasi');

// login
Route::get('/login-mahasiswa', [AuthMahasiswaController::class, 'showLogin'])->name('login-mahasiswa');
Route::post('/login-mahasiswa', [AuthMahasiswaController::class, 'login']);
Route::get('/logout-mahasiswa', [AuthMahasiswaController::class, 'logout'])->name('logout-mahasiswa');

// register
Route::get('/register-mahasiswa', [AuthMahasiswaController::class, 'showRegister'])->name('register-mahasiswa');
Route::post('/register-mahasiswa', [AuthMahasiswaController::class, 'register']);

// profile
Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
Route::delete('/riwayat/{id_perhitungan}/delete', [\App\Http\Controllers\ProfileController::class, 'delete'])->name('delete-history');
Route::post('/update-nama-mahasiswa', [\App\Http\Controllers\ProfileController::class, 'updateNama'])->name('update-nama-mahasiswa');

// google
Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__.'/auth.php';
