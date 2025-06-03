<?php

// app/Models/Perhitungan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    protected $table = 'perhitungan';
    public $timestamps = false;
    protected $fillable = ['id_mahasiswa', 'hasil_json', 'waktu_perhitungan'];
}
