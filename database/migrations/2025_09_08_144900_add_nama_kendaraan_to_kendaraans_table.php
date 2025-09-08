<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('kendaraans')) {
            Schema::table('kendaraans', function (Blueprint $table) {
                // Tambahkan kolom jika belum ada
                if (!Schema::hasColumn('kendaraans', 'nama_kendaraan')) {
                    $table->string('nama_kendaraan')->nullable();
                }
                if (!Schema::hasColumn('kendaraans', 'nama')) {
                    $table->string('nama')->nullable();
                }
                if (!Schema::hasColumn('kendaraans', 'no_polisi')) {
                    $table->string('no_polisi', 50)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('kendaraans')) {
            Schema::table('kendaraans', function (Blueprint $table) {
                if (Schema::hasColumn('kendaraans', 'nama_kendaraan')) {
                    $table->dropColumn('nama_kendaraan');
                }
            });
        }
    }
};
