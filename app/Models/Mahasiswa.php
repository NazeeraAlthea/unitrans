<?php

// app/Models/Mahasiswa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'email', 'password', 'nama'
    ];

    public function perhitungan()
    {
        return $this->hasMany(Perhitungan::class, 'id_mahasiswa', 'id');
    }
}
