<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanInvalidKendaraanDataInPosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Backup data yang akan diubah untuk log
        DB::statement("CREATE TEMPORARY TABLE temp_invalid_kendaraan AS SELECT id, kendaraan FROM pos WHERE kendaraan IS NULL OR TRIM(kendaraan) = '' OR TRIM(kendaraan) = '0' OR (kendaraan IS NOT NULL AND kendaraan NOT REGEXP '^[0-9]+$')");
        
        // Bersihkan data kendaraan yang invalid
        DB::statement("UPDATE pos SET kendaraan = NULL WHERE kendaraan IS NULL OR TRIM(kendaraan) = '' OR TRIM(kendaraan) = '0' OR (kendaraan IS NOT NULL AND kendaraan NOT REGEXP '^[0-9]+$')");
        
        // Log jumlah record yang diubah
        $affectedRows = DB::select("SELECT COUNT(*) as count FROM temp_invalid_kendaraan")[0]->count;
        \Log::info("Migration: Cleaned {$affectedRows} invalid kendaraan records in pos table");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Tidak ada rollback untuk data cleaning karena data sudah invalid
        \Log::info("Migration rollback: Cannot restore invalid kendaraan data");
    }
}