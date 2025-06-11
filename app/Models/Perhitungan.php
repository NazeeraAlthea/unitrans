<?php

// app/Models/Perhitungan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perhitungan extends Model
{
    protected $table = 'perhitungan';
    protected $primaryKey = 'id_perhitungan';
    public $timestamps = false;
    protected $fillable = ['id_mahasiswa', 'hasil_json', 'waktu_perhitungan'];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }

    public function bobotKriteria()
    {
        return $this->hasMany(BobotKriteria::class, 'id_perhitungan', 'id_perhitungan');
    }
    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'id_perhitungan', 'id_perhitungan');
    }
}

