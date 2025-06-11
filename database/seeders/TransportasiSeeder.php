<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransportasiSeeder extends Seeder
{
    public function run()
    {
        DB::table('transportasi')->insert([
            ['nama_transportasi' => 'Kereta', 'icon' => '🚆', 'mode' => 'TRANSIT'],
            ['nama_transportasi' => 'Bus', 'icon' => '🚌', 'mode' => 'TRANSIT'],
            ['nama_transportasi' => 'Ojek Online', 'icon' => '🛵', 'mode' => 'TWO_WHEELER'],
            ['nama_transportasi' => 'Motor Pribadi', 'icon' => '🏍️', 'mode' => 'TWO_WHEELER'],
            ['nama_transportasi' => 'Mobil Pribadi', 'icon' => '🚗', 'mode' => 'DRIVING'],
        ]);
    }
}
