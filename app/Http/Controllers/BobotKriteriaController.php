<?php

// app/Http/Controllers/BobotKriteriaController.php
namespace App\Http\Controllers;

use App\Models\BobotKriteria;
use Illuminate\Http\Request;

class BobotKriteriaController extends Controller
{
    public function store(Request $request)
    {
        // validasi dsb bisa ditambah
        $bobot = new BobotKriteria();
        $bobot->id_kriteria = $request->id_kriteria;
        $bobot->bobot = $request->bobot;
        $bobot->save();

        return response()->json($bobot);
    }
}

