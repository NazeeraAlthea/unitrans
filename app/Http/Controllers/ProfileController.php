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
        $mahasiswa = Mahasiswa::find(session('mahasiswa_id'));
        if (!$mahasiswa) {
            return redirect('/login');
        }

        // Ambil seluruh riwayat perhitungan milik mahasiswa
        $riwayat = $mahasiswa->perhitungan()->orderByDesc('id_perhitungan')->get();

        // Ambil juara 1 tiap riwayat
        foreach ($riwayat as $item) {
            // Ambil dari hasil_json
            $hasil = json_decode($item->hasil_json, true);
            // Pastikan data valid & urutkan, lalu ambil transportasi ranking 1
            $juara = null;
            if (isset($hasil['ranking']) && count($hasil['ranking'])) {
                // Ranking 1 = pertama dari array ranking yang sudah diurutkan
                $juara = $hasil['ranking'][0]['nama'] ?? '-';
            }
            $item->juara_1 = $juara;
        }

        // Kirim ke view
        return view('profile.profile', compact('mahasiswa', 'riwayat'));
    }

    public function delete($id_perhitungan)
    {
        $userId = session('mahasiswa_id');
        // Pastikan hanya user yang punya riwayat ini yang bisa hapus!
        $perhitungan = \App\Models\Perhitungan::where('id_perhitungan', $id_perhitungan)
            ->where('id_mahasiswa', $userId)
            ->first();

        if (!$perhitungan) {
            return redirect()->route('profile')->with('error', 'Riwayat tidak ditemukan atau bukan milik Anda.');
        }

        $perhitungan->delete(); // Akan menghapus otomatis ke tabel terkait jika FK cascade!

        return redirect()->route('profile')->with('success', 'Riwayat rekomendasi berhasil dihapus!');
    }
}
