<?php

// app/Models/BobotKriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BobotKriteria extends Model
{
    protected $table = 'bobot_kriteria';
    protected $primaryKey = 'id_bobot';
    public $timestamps = false;
    protected $fillable = [
        'id_perhitungan',
        'id_kriteria',
        'bobot'
    ];


    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, 'id_kriteria', 'id_kriteria');
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }
}
