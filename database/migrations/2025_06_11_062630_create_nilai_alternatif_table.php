<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaiAlternatifTable extends Migration
{
    public function up()
    {
        Schema::create('nilai_alternatif', function (Blueprint $table) {
            $table->increments('id_nilai');
            $table->unsignedInteger('id_transportasi')->nullable();
            $table->unsignedInteger('id_kriteria')->nullable();
            $table->decimal('nilai', 10, 2)->nullable();
            $table->unsignedInteger('id_perhitungan')->nullable();

            $table->foreign('id_transportasi')->references('id_transportasi')->on('transportasi')->onDelete('cascade');
            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
            $table->foreign('id_perhitungan')->references('id_perhitungan')->on('perhitungan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('nilai_alternatif');
    }
}

