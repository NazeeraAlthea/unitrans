<?php

// app/Http/Controllers/NilaiAlternatifController.php
namespace App\Http\Controllers;

use App\Models\NilaiAlternatif;
use Illuminate\Http\Request;

class NilaiAlternatifController extends Controller
{
    public function store(Request $request)
    {
        $nilai = new NilaiAlternatif();
        $nilai->id_transportasi = $request->id_transportasi;
        $nilai->id_kriteria = $request->id_kriteria;
        $nilai->nilai = $request->nilai;
        $nilai->save();

        return response()->json($nilai);
    }
}

