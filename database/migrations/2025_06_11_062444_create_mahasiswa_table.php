<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_mahasiswa_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMahasiswaTable extends Migration
{
    public function up()
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('password', 255)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->string('nama', 100)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mahasiswa');
    }
}
