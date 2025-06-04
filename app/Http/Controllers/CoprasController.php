<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Models\NilaiAlternatif;
use App\Models\BobotKriteria;
use App\Models\Perhitungan;
use App\Models\Kriteria;
use App\Models\Transportasi;



class CoprasController extends Controller
{
    public function hitung(Request $request)
    {
        $alternatif = $request->input('alternatif');
        $bobot = $request->input('bobot');
        Log::info('Data Alternatif:', $request->input('alternatif'));
        Log::info('Data Bobot:', $request->input('bobot'));

        $hasil = $this->copras($alternatif, $bobot);

        $perhitungan = Perhitungan::create([
            'hasil_json'     => json_encode($hasil),
            'tanggal_hitung' => now(),
        ]);

        $listKriteria = Kriteria::all()->map(function ($k) {
            return ['id_kriteria' => $k->id_kriteria, 'nama_kriteria' => $k->nama_kriteria];
        })->toArray();

        foreach ($alternatif as $alt) {
            foreach ($listKriteria as $kriteria) {
                $field = $kriteria['nama_kriteria'];
                // mapping waktu <-> waktuMinute
                if ($field === 'waktu' && isset($alt['waktuMinute'])) {
                    $value = $alt['waktuMinute'];
                } else {
                    $value = $alt[$field];
                }
                NilaiAlternatif::create([
                    'id_mahasiswa'    => null,
                    'id_transportasi' => $alt['id_transportasi'],
                    'id_kriteria'     => $kriteria['id_kriteria'],
                    'nilai'           => $value,
                    'id_perhitungan'  => $perhitungan->id
                ]);
            }
        }

        foreach ($bobot as $idx => $bobotNilai) {
            $kriteria = $listKriteria[$idx];
            BobotKriteria::create([
                'id_perhitungan' => $perhitungan->id,
                'id_kriteria'    => $kriteria['id_kriteria'],
                'bobot'          => $bobotNilai,
            ]);
        }

        // Ambil kriteria lengkap
        $kriteriaList = Kriteria::all()->keyBy('id_kriteria');
        // Ambil transportasi lengkap
        $transportasiList = Transportasi::all()->keyBy('id_transportasi');

        // Data bobot dengan nama kriteria
        $bobot_kriteria = BobotKriteria::where('id_perhitungan', $perhitungan->id)
            ->get()
            ->map(function ($bk) use ($kriteriaList) {
                return [
                    'nama_kriteria' => $kriteriaList[$bk->id_kriteria]->nama_kriteria ?? $bk->id_kriteria,
                    'bobot' => $bk->bobot,
                ];
            });

        // Data nilai alternatif dengan nama
        $nilai_alternatif = NilaiAlternatif::where('id_perhitungan', $perhitungan->id)
            ->get()
            ->map(function ($na) use ($transportasiList, $kriteriaList) {
                return [
                    'nama_transportasi' => $transportasiList[$na->id_transportasi]->nama_transportasi ?? $na->id_transportasi,
                    'nama_kriteria'     => $kriteriaList[$na->id_kriteria]->nama_kriteria ?? $na->id_kriteria,
                    'nilai'             => $na->nilai,
                ];
            });


        return response()->json([
            'hasil'            => $hasil,
            'id_perhitungan'   => $perhitungan->id,
            'bobot_kriteria'   => $bobot_kriteria,
            'nilai_alternatif' => $nilai_alternatif,
            'perhitungan'      => $perhitungan,
        ]);
    }



    private function copras($alternatif, $bobot)
    {
        $n = count($alternatif);
        $jenisKriteria = ["cost", "cost", "benefit", "benefit", "benefit"];

        // 1. Matriks Nilai Alternatif
        $matriks = [];
        foreach ($alternatif as $alt) {
            $matriks[] = [
                max(1, $alt['harga'] ?? 1),
                max(1, $alt['waktuMinute'] ?? 1),
                max(1, $alt['keamanan'] ?? 1),
                max(1, $alt['kenyamanan'] ?? 1),
                max(1, $alt['aksesbilitas'] ?? 1),
            ];
        }

        // 2. Normalisasi
        $norm = [];
        for ($j = 0; $j < 5; $j++) {
            $kolom = array_column($matriks, $j);
            if ($jenisKriteria[$j] === "benefit") {
                $sum = array_sum($kolom) ?: 1;
                foreach ($kolom as $val) {
                    $norm[$j][] = $val / $sum;
                }
            } else {
                $min = min(array_filter($kolom, function ($x) {
                    return $x > 0;
                })) ?: 1;
                foreach ($kolom as $val) {
                    $norm[$j][] = $min / ($val ?: 1);
                }
            }
        }

        // 3. Transpose Normalisasi
        $normTrans = [];
        for ($i = 0; $i < $n; $i++) {
            $normTrans[$i] = array_column($norm, $i);
        }

        // 4. Matriks Berbobot
        $normBobot = [];
        foreach ($normTrans as $row) {
            $normBobot[] = array_map(function ($val, $idx) use ($bobot) {
                return $val * $bobot[$idx];
            }, $row, array_keys($row));
        }

        // 5. Hitung S+ dan S-
        $benefitIdx = [2, 3, 4];
        $costIdx = [0, 1];
        $Splus = [];
        $Smin = [];
        foreach ($normBobot as $row) {
            $Splus[] = array_sum(array_intersect_key($row, array_flip($benefitIdx)));
            $Smin[] = array_sum(array_intersect_key($row, array_flip($costIdx)));
        }

        $SminTotal = array_sum($Smin);
        $SminMin = min(array_filter($Smin, function ($x) {
            return $x > 0;
        })) ?: 1;

        // 6. Qi
        $Qi = [];
        foreach ($Splus as $i => $splus) {
            $costPart = $Smin[$i] > 0 ? $SminMin * $SminTotal / $Smin[$i] : 0;
            $Qi[] = $splus + $costPart;
        }

        // 7. Ranking
        $Qmax = max($Qi) ?: 1;
        $Ui = array_map(function ($q) use ($Qmax) {
            return $q / $Qmax * 100;
        }, $Qi);

        // Gabungkan semua proses ke array hasil
        return [
            'matriks' => $matriks,
            'normalisasi' => $normTrans,
            'berbobot' => $normBobot,
            'Splus' => $Splus,
            'Smin' => $Smin,
            'Qi' => $Qi,
            'Ui' => $Ui,
            'ranking' => array_map(function ($alt, $i) use ($Qi, $Ui) {
                return [
                    'nama' => $alt['nama_transportasi'],
                    'Qi' => $Qi[$i],
                    'Ui' => $Ui[$i],
                ];
            }, $alternatif, array_keys($alternatif))
        ];
    }
}
