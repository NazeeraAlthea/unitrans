<?php

// app/Http/Controllers/PerhitunganController.php
namespace App\Http\Controllers;

use App\Models\Perhitungan;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function store(Request $request)
    {
        $perhitungan = new Perhitungan();
        $perhitungan->id_mahasiswa = $request->id_mahasiswa;
        $perhitungan->hasil_json = $request->hasil_json;
        $perhitungan->save();

        return response()->json($perhitungan);
    }
}

