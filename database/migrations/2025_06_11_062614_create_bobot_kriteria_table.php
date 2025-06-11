<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBobotKriteriaTable extends Migration
{
    public function up()
    {
        Schema::create('bobot_kriteria', function (Blueprint $table) {
            $table->increments('id_bobot');
            $table->unsignedInteger('id_kriteria')->nullable();
            $table->unsignedInteger('id_perhitungan')->nullable();
            $table->decimal('bobot', 5, 3)->nullable();

            $table->foreign('id_kriteria')->references('id_kriteria')->on('kriteria')->onDelete('cascade');
            $table->foreign('id_perhitungan')->references('id_perhitungan')->on('perhitungan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bobot_kriteria');
    }
}
