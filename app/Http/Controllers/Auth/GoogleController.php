<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Mahasiswa; // Ganti dengan User jika pakai model User Laravel
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Cek apakah user sudah ada di database
        $user = Mahasiswa::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Jika belum ada, buat user baru
            $user = Mahasiswa::create([
                'nama' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                // password kosong atau acak, karena pakai google login
                'password' => bcrypt(str()->random(16)),
            ]);
        }

        // Set session (login manual, sesuaikan dengan sistemmu)
        session([
            'mahasiswa_id' => $user->id,
            'mahasiswa_nama' => $user->nama,
        ]);

        // Atau jika pakai Auth Laravel:
        // Auth::login($user);

        // Redirect ke halaman yang diinginkan
        return redirect()->route('home');
    }
}
