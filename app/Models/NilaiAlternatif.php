<?php

// app/Models/NilaiAlternatif.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiAlternatif extends Model
{
    protected $table = 'nilai_alternatif';
    protected $primaryKey = 'id_nilai';
    public $timestamps = false;
    protected $fillable = [
        'id_perhitungan',
        'id_transportasi',
        'id_kriteria',
        'nilai'
    ];

    public function transportasi()
    {
        return $this->belongsTo(Transportasi::class, 'id_transportasi', 'id_transportasi');
    }
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
    public function perhitungan()
    {
        return $this->belongsTo(Perhitungan::class, 'id_perhitungan', 'id_perhitungan');
    }
}

