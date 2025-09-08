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
                // Tambahkan kolom yang dipakai aplikasi jika belum ada
                if (!Schema::hasColumn('salaries', 'gaji_pokok')) {
                    $table->decimal('gaji_pokok', 15, 2)->default(0)->after('tahun');
                }
                if (!Schema::hasColumn('salaries', 'tunjangan')) {
                    $table->decimal('tunjangan', 15, 2)->default(0)->after('gaji_pokok');
                }
                if (!Schema::hasColumn('salaries', 'bonus')) {
                    $table->decimal('bonus', 15, 2)->default(0)->after('tunjangan');
                }
                if (!Schema::hasColumn('salaries', 'lembur')) {
                    $table->decimal('lembur', 15, 2)->default(0)->after('bonus');
                }
                if (!Schema::hasColumn('salaries', 'potongan_pajak')) {
                    $table->decimal('potongan_pajak', 15, 2)->default(0)->after('lembur');
                }
                if (!Schema::hasColumn('salaries', 'potongan_bpjs')) {
                    $table->decimal('potongan_bpjs', 15, 2)->default(0)->after('potongan_pajak');
                }
                if (!Schema::hasColumn('salaries', 'potongan_lain')) {
                    $table->decimal('potongan_lain', 15, 2)->default(0)->after('potongan_bpjs');
                }
                if (!Schema::hasColumn('salaries', 'total_gaji')) {
                    $table->decimal('total_gaji', 15, 2)->default(0)->after('potongan_lain');
                }
                if (!Schema::hasColumn('salaries', 'status_pembayaran')) {
                    $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar'])->default('belum_dibayar')->after('total_gaji');
                }
                if (!Schema::hasColumn('salaries', 'tanggal_bayar')) {
                    $table->date('tanggal_bayar')->nullable()->after('status_pembayaran');
                }
                if (!Schema::hasColumn('salaries', 'keterangan')) {
                    $table->text('keterangan')->nullable()->after('tanggal_bayar');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('salaries')) {
            Schema::table('salaries', function (Blueprint $table) {
                foreach ([
                    'keterangan',
                    'tanggal_bayar',
                    'status_pembayaran',
                    'total_gaji',
                    'potongan_lain',
                    'potongan_bpjs',
                    'potongan_pajak',
                    'lembur',
                    'bonus',
                    'tunjangan',
                    'gaji_pokok',
                ] as $col) {
                    if (Schema::hasColumn('salaries', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
