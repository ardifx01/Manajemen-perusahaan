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
        Schema::table('employees', function (Blueprint $table) {
            if (!Schema::hasColumn('employees', 'gaji_pokok')) {
                $table->decimal('gaji_pokok', 15, 2)->default(5000000)->after('nama_karyawan');
            }
            if (!Schema::hasColumn('employees', 'status')) {
                $table->string('status', 50)->default('aktif')->after('gaji_pokok');
            }
            if (!Schema::hasColumn('employees', 'tanggal_masuk')) {
                $table->date('tanggal_masuk')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['gaji_pokok', 'status', 'tanggal_masuk']);
        });
    }
};
