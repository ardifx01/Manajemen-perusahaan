<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('kendaraans')) {
            Schema::create('kendaraans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kendaraan')->unique();
                $table->string('no_polisi')->nullable();
                $table->string('jenis_kendaraan')->nullable();
                $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('kendaraans');
    }
};