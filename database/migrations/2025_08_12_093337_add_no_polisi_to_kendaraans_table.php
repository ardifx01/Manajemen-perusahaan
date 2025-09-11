<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('kendaraans') && !Schema::hasColumn('kendaraans', 'no_polisi')) {
            Schema::table('kendaraans', function (Blueprint $table) {
                // Posisi kolom: jika ada 'nama' gunakan itu, jika ada 'nama_kendaraan' gunakan itu, jika tidak, tambah tanpa after()
                if (Schema::hasColumn('kendaraans', 'nama')) {
                    $table->string('no_polisi')->nullable()->after('nama');
                } elseif (Schema::hasColumn('kendaraans', 'nama_kendaraan')) {
                    $table->string('no_polisi')->nullable()->after('nama_kendaraan');
                } else {
                    $table->string('no_polisi')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('kendaraans') && Schema::hasColumn('kendaraans', 'no_polisi')) {
            Schema::table('kendaraans', function (Blueprint $table) {
                $table->dropColumn('no_polisi');
            });
        }
    }
};
