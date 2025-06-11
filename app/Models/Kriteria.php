<?php

// app/Models/Kriteria.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';
    protected $primaryKey = 'id_kriteria';
    public $timestamps = false;

    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'id_kriteria', 'id_kriteria');
    }

    public function bobotKriteria()
    {
        return $this->hasMany(BobotKriteria::class, 'id_kriteria', 'id_kriteria');
    }
}
