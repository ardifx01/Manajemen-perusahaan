<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pos')) {
            // Jadikan kolom po_number NULLABLE untuk kompatibilitas schema lama
            try {
                DB::statement("ALTER TABLE `pos` MODIFY `po_number` VARCHAR(191) NULL");
            } catch (\Throwable $e) {
                // Abaikan jika DB sudah sesuai atau kolom belum ada
            }
        }
    }

    public function down(): void
    {
        // Tidak perlu memaksa NOT NULL kembali
    }
};
