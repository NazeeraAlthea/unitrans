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

    // Contoh relasi ke nilai/bobot
    public function nilaiAlternatif()
    {
        return $this->hasMany(NilaiAlternatif::class, 'id_mahasiswa', 'id');
    }
}
