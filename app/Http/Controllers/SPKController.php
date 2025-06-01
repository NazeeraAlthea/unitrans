<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transportasi;

class SPKController extends Controller
{
    // form perhitungan
    public function showForm()
    {
        $transportasi = Transportasi::all();
        return view('spk.form', compact('transportasi'));
    }


}
