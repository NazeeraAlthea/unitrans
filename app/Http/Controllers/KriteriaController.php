<?php

// app/Http/Controllers/KriteriaController.php
namespace App\Http\Controllers;

use App\Models\Kriteria;

class KriteriaController extends Controller
{
    public function index()
    {
        return response()->json(Kriteria::all());
    }
}

