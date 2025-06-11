<?php

// app/Models/Transportasi.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transportasi extends Model
{
    protected $table = 'transportasi';
    protected $primaryKey = 'id_transportasi';
    public $timestamps = false;

    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'id_transportasi', 'id_transportasi');
    }
}

