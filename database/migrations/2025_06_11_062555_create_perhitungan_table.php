<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerhitunganTable extends Migration
{
    public function up()
    {
        Schema::create('perhitungan', function (Blueprint $table) {
            $table->increments('id_perhitungan');
            $table->unsignedInteger('id_mahasiswa')->nullable();
            $table->timestamp('waktu_perhitungan')->useCurrent();
            $table->text('hasil_json')->nullable();

            $table->foreign('id_mahasiswa')->references('id')->on('mahasiswa')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('perhitungan');
    }
}

