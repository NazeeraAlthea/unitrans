<?php
// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil data mahasiswa yang login (pakai session manual)
        $mahasiswa = Mahasiswa::find(session('mahasiswa_id'));
        if (!$mahasiswa) {
            return redirect('/login');
        }

        // Ambil seluruh riwayat perhitungan milik mahasiswa
        $riwayat = $mahasiswa->perhitungan()->orderByDesc('id_perhitungan')->get();

        // Kirim ke view
        return view('profile.profile', compact('mahasiswa', 'riwayat'));
    }
}
