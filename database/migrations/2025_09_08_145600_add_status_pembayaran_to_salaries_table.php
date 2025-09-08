<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('salaries')) {
            Schema::table('salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('salaries', 'status_pembayaran')) {
                    $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar'])->default('belum_dibayar')->after('total_gaji');
                }
                if (!Schema::hasColumn('salaries', 'tanggal_bayar')) {
                    $table->date('tanggal_bayar')->nullable()->after('status_pembayaran');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('salaries')) {
            Schema::table('salaries', function (Blueprint $table) {
                if (Schema::hasColumn('salaries', 'tanggal_bayar')) {
                    $table->dropColumn('tanggal_bayar');
                }
                if (Schema::hasColumn('salaries', 'status_pembayaran')) {
                    $table->dropColumn('status_pembayaran');
                }
            });
        }
    }
};
