<?php

// app/Http/Controllers/TransportasiController.php
namespace App\Http\Controllers;

use App\Models\Transportasi;

class TransportasiController extends Controller
{
    // Ambil semua transportasi
    public function index()
    {
        return response()->json(Transportasi::all());
    }
}

