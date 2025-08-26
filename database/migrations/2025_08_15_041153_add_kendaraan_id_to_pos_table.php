<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKendaraanIdToPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pos', function (Blueprint $table) {
            // Tambah kolom kendaraan_id sebagai foreign key yang proper
            $table->unsignedBigInteger('kendaraan_id')->nullable()->after('kendaraan');
            
            // Buat foreign key constraint
            $table->foreign('kendaraan_id')->references('id')->on('kendaraans')->onDelete('set null');
            
            // Index untuk performa
            $table->index('kendaraan_id');
        });

        // Migrate data dari kolom kendaraan ke kendaraan_id
        DB::statement("
            UPDATE pos 
            SET kendaraan_id = CASE 
                WHEN kendaraan IS NOT NULL AND kendaraan REGEXP '^[0-9]+$' AND kendaraan > 0 
                THEN kendaraan 
                ELSE NULL 
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pos', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['kendaraan_id']);
            
            // Drop kolom kendaraan_id
            $table->dropColumn('kendaraan_id');
        });
    }
}