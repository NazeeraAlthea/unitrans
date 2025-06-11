<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

class AuthMahasiswaController extends Controller
{
    public function showLogin()
    {
        return view('auth.login'); // Ganti path view jika perlu
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required|captcha',
        ]);

        $mahasiswa = Mahasiswa::where('email', $request->email)->first();

        if ($mahasiswa) {
            if (Hash::check($request->password, $mahasiswa->password)) {
                // Simpan session manual
                session(['mahasiswa_id' => $mahasiswa->id, 'mahasiswa_nama' => $mahasiswa->nama]);
                return redirect()->intended('/'); // Ganti route dashboard jika perlu
            } else {
                // Password salah
                return back()->withErrors(['email' => 'Email atau password salah'])->withInput();
            }
        } else {
            // Akun tidak ada
            return back()->withErrors(['email' => 'Akun tidak ditemukan'])->withInput();
        }
    }


    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/');
    }

    public function showRegister()
    {
        return view('auth.register'); // Ganti path view jika file register beda
    }

    public function register(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|max:100',
            'email'    => 'required|email|unique:mahasiswa,email',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'
            ],
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.regex' => 'Password minimal 8 karakter, harus ada huruf besar, kecil, angka, dan simbol.'
        ]);

        $mahasiswa = new \App\Models\Mahasiswa;
        $mahasiswa->nama     = $request->nama;
        $mahasiswa->email    = $request->email;
        $mahasiswa->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $mahasiswa->save();

        // Login langsung setelah register (opsional)
        session(['mahasiswa_id' => $mahasiswa->id, 'mahasiswa_nama' => $mahasiswa->nama]);

        return redirect('/')->with('success', 'Registrasi berhasil!');
    }
}
