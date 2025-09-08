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
        Schema::table('salaries', function (Blueprint $table) {
            if (!Schema::hasColumn('salaries', 'total_gaji')) {
                $table->decimal('total_gaji', 15, 2)->default(0)->after('employee_id');
            }
            // Optional safety: ensure bulan & tahun exist and proper type (int)
            if (!Schema::hasColumn('salaries', 'bulan')) {
                $table->unsignedTinyInteger('bulan')->default(1)->after('total_gaji');
            }
            if (!Schema::hasColumn('salaries', 'tahun')) {
                $table->unsignedSmallInteger('tahun')->default((int) date('Y'))->after('bulan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('salaries', function (Blueprint $table) {
            if (Schema::hasColumn('salaries', 'total_gaji')) {
                $table->dropColumn('total_gaji');
            }
            // Do not drop bulan/tahun in down for safety if they pre-existed
        });
    }
};
