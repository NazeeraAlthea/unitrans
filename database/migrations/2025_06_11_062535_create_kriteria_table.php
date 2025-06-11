<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('kriteria', function (Blueprint $table) {
            $table->increments('id_kriteria');
            $table->string('nama_kriteria', 30)->nullable();
            $table->string('tipe_kriteria', 10)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kriteria');
    }
}

