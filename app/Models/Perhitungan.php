<?php

// app/Models/Perhitungan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    protected $table = 'perhitungan';
    protected $primaryKey = 'id_perhitungan';
    public $timestamps = false;

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }
}
