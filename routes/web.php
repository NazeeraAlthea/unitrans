<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use App\Http\Controllers\SPKController;
use App\Http\Controllers\TransportasiController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\BobotKriteriaController;
use App\Http\Controllers\NilaiAlternatifController;
use App\Http\Controllers\PerhitunganController;

use App\Http\Controllers\CoprasController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::get('/form', [SPKController::class, 'showForm'])->name('spk.form');
Route::get('/transportasi', [TransportasiController::class, 'index']);
Route::get('/kriteria', [KriteriaController::class, 'index']);
Route::post('/bobot-kriteria', [BobotKriteriaController::class, 'store']);
Route::post('/nilai-alternatif', [NilaiAlternatifController::class, 'store']);
Route::post('/perhitungan', [PerhitunganController::class, 'store']);

Route::post('/hitung-copras', [CoprasController::class, 'hitung']);


require __DIR__.'/auth.php';
