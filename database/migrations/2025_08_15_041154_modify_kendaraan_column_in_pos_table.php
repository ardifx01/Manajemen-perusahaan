<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyKendaraanColumnInPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos', function (Blueprint $table) {
            // Ubah kolom kendaraan menjadi nullable dan string
            $table->string('kendaraan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos', function (Blueprint $table) {
            // Kembalikan ke struktur semula (sesuaikan dengan struktur asli)
            $table->string('kendaraan')->nullable(false)->change();
        });
    }
}