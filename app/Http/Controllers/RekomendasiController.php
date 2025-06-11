<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekomendasiController extends Controller
{
    public function show($id_perhitungan)
    {

        $userId = session('mahasiswa_id');

        // Cek: Apakah perhitungan ini milik user yang login?
        $perhitungan = DB::table('perhitungan')
            ->where('id_perhitungan', $id_perhitungan)
            ->where('id_mahasiswa', $userId)
            ->first();

        if (!$perhitungan) {
            return redirect()->route('home')->with('error', 'Anda tidak punya akses ke hasil ini!');
        }



        // 1. Ambil data transportasi dan kriteria
        $transportasi = DB::table('transportasi')->get()->keyBy('id_transportasi');
        $kriteria = DB::table('kriteria')->orderBy('id_kriteria')->get()->keyBy('id_kriteria');

        // 2. Bobot & nilai alternatif untuk perhitungan ini
        $bobot = DB::table('bobot_kriteria')
            ->where('id_perhitungan', $id_perhitungan)
            ->orderBy('id_kriteria')
            ->get()
            ->keyBy('id_kriteria');

        $nilai_alternatif = DB::table('nilai_alternatif')
            ->where('id_perhitungan', $id_perhitungan)
            ->get();

        // 3. Matriks Nilai Alternatif: [id_transportasi][id_kriteria] = nilai
        $nilai = [];
        foreach ($nilai_alternatif as $na) {
            $nilai[$na->id_transportasi][$na->id_kriteria] = $na->nilai;
        }

        // 4. Bentuk list transportasi untuk looping
        $transportasiList = [];
        foreach ($transportasi as $id_tr => $tr) {
            $row = [
                'id_transportasi' => $id_tr,
                'nama' => $tr->nama_transportasi,
                'icon' => $tr->icon,
                'detail' => [],
            ];
            foreach ($kriteria as $id_kr => $kr) {
                $row['detail'][$kr->nama_kriteria] = $nilai[$id_tr][$id_kr] ?? 0;
            }
            $transportasiList[] = $row;
        }

        // 5. Matriks alternatif [alternatif][kriteria]
        $matriks = [];
        foreach ($transportasiList as $tr) {
            $matriks[] = [
                $tr['detail']['harga'] ?? 1,
                $tr['detail']['waktu'] ?? 1,
                $tr['detail']['keamanan'] ?? 1,
                $tr['detail']['kenyamanan'] ?? 1,
                $tr['detail']['aksesbilitas'] ?? 1,
            ];
        }

        // 6. Jenis kriteria & bobot array
        $jenisKriteria = [];
        $bobotArr = [];
        $kriteriaArr = [];
        foreach ($kriteria as $kr) {
            $jenisKriteria[] = $kr->tipe_kriteria; // cost/benefit
            $bobotArr[] = isset($bobot[$kr->id_kriteria]) ? $bobot[$kr->id_kriteria]->bobot : 0;
            $kriteriaArr[] = $kr->nama_kriteria;
        }

        // 7. Normalisasi (AMAN min())
        $n = count($matriks);
        $kolom = [];
        for ($j = 0; $j < count($kriteria); $j++) {
            $kolom[$j] = array_column($matriks, $j);
        }

        $norm = [];
        for ($j = 0; $j < count($kriteria); $j++) {
            if ($jenisKriteria[$j] === 'benefit') {
                $sum = array_sum($kolom[$j]);
                $norm[$j] = [];
                foreach ($kolom[$j] as $val) {
                    $norm[$j][] = $sum == 0 ? 0 : $val / $sum;
                }
            } else { // cost
                $filtered = array_filter($kolom[$j], function ($x) {
                    return $x > 0;
                });
                $min = !empty($filtered) ? min($filtered) : 1;
                $norm[$j] = [];
                foreach ($kolom[$j] as $val) {
                    $norm[$j][] = ($val == 0 ? 0 : $min / $val);
                }
            }
        }

        // 8. Transpose hasil normalisasi ke [alternatif][kriteria]
        $normTrans = [];
        for ($i = 0; $i < $n; $i++) {
            $normTrans[$i] = [];
            for ($j = 0; $j < count($kriteria); $j++) {
                $normTrans[$i][$j] = $norm[$j][$i];
            }
        }

        // 9. Matriks berbobot
        $normBobot = [];
        foreach ($normTrans as $row) {
            $normBobot[] = array_map(function ($val, $b) {
                return $val * $b;
            }, $row, $bobotArr);
        }

        // 10. Index benefit/cost
        $benefitIdx = [];
        $costIdx = [];
        foreach ($kriteria as $idx => $kr) {
            if ($kr->tipe_kriteria == "benefit") $benefitIdx[] = $idx;
            else $costIdx[] = $idx;
        }

        // 11. S+ dan S- (AMAN min())
        $Splus = [];
        $Smin = [];
        foreach ($normBobot as $row) {
            $Splus[] = array_sum(array_intersect_key($row, array_flip($benefitIdx)));
            $Smin[] = array_sum(array_intersect_key($row, array_flip($costIdx)));
        }
        $SminTotal = array_sum($Smin);
        $filteredSmin = array_filter($Smin, fn($x) => $x > 0);
        $SminMin = !empty($filteredSmin) ? min($filteredSmin) : 1;

        // 12. Qi dan Ui
        $Qi = [];
        for ($i = 0; $i < $n; $i++) {
            $costPart = $Smin[$i] > 0 ? ($SminMin * $SminTotal) / $Smin[$i] : 0;
            $Qi[$i] = $Splus[$i] + $costPart;
        }
        $Qmax = max($Qi) ?: 1;
        $Ui = [];
        for ($i = 0; $i < $n; $i++) {
            $Ui[$i] = $Qmax == 0 ? 0 : ($Qi[$i] / $Qmax) * 100;
        }

        // 13. Gabung hasil ranking
        $ranking = [];
        foreach ($transportasiList as $i => $tr) {
            $ranking[] = [
                'nama' => $tr['nama'],
                'icon' => $tr['icon'],
                'detail' => $tr['detail'],
                'skor' => $Ui[$i],
            ];
        }
        usort($ranking, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // 14. Untuk tabel bobot
        $bobotView = [];
        foreach ($kriteria as $kr) {
            $bobotView[] = [
                'nama_kriteria' => $kr->nama_kriteria,
                'bobot' => isset($bobot[$kr->id_kriteria]) ? $bobot[$kr->id_kriteria]->bobot : 0,
            ];
        }

        // 15. Untuk tabel nilai alternatif (kriteria per transportasi)
        $nilaiAlternatifView = [];
        foreach ($transportasiList as $tr) {
            $item = ['nama' => $tr['nama']];
            foreach ($kriteriaArr as $idx => $k) {
                $item[$k] = $tr['detail'][$k];
            }
            $nilaiAlternatifView[] = $item;
        }

        // 16. Kirim semua ke view
        return view('spk.hasil-rekomendasi', [
            'ranking'        => $ranking,
            'bobot'          => $bobotView,
            'matriks'        => $matriks,
            'nilaiAlternatif' => $nilaiAlternatifView,
            'normalisasi'    => $normTrans,
            'berbobot'       => $normBobot,
            'Splus'          => $Splus,
            'Smin'           => $Smin,
            'Qi'             => $Qi,
            'Ui'             => $Ui,
            'transportasi'   => $transportasiList,
            'kriteria'       => $kriteria->values(), // supaya bisa foreach tanpa key
            'kriteriaArr'    => $kriteriaArr,
        ]);
    }
}
