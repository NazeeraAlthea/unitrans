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
Route::get('/logout-mahasiswa', [AuthMahasiswaController::class, 'logout'])->name('logout');

// register
Route::get('/register-mahasiswa', [AuthMahasiswaController::class, 'showRegister'])->name('register-mahasiswa');
Route::post('/register-mahasiswa', [AuthMahasiswaController::class, 'register']);


require __DIR__.'/auth.php';
