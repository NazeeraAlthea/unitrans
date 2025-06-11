<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        DB::table('kriteria')->insert([
            ['nama_kriteria' => 'harga', 'tipe_kriteria' => 'cost'],
            ['nama_kriteria' => 'waktu', 'tipe_kriteria' => 'cost'],
            ['nama_kriteria' => 'keamanan', 'tipe_kriteria' => 'benefit'],
            ['nama_kriteria' => 'kenyamanan', 'tipe_kriteria' => 'benefit'],
            ['nama_kriteria' => 'aksesbilitas', 'tipe_kriteria' => 'benefit'],
        ]);
    }
}
