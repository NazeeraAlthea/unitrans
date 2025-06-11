<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_transportasi_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportasiTable extends Migration
{
    public function up()
    {
        Schema::create('transportasi', function (Blueprint $table) {
            $table->increments('id_transportasi');
            $table->string('nama_transportasi', 50)->nullable();
            $table->string('icon', 10)->nullable();
            $table->string('mode', 20)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transportasi');
    }
}
