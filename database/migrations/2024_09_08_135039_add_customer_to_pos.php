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
        // Pastikan tabel pos ada sebelum menambahkan kolom
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (!Schema::hasColumn('pos', 'customer')) {
                    $table->string('customer', 255)->default('PT. Default Customer')->after('no_po');
                }
                if (!Schema::hasColumn('pos', 'no_invoice')) {
                    $table->string('no_invoice', 255)->nullable()->after('customer');
                }
                if (!Schema::hasColumn('pos', 'pengirim')) {
                    $table->string('pengirim', 255)->nullable()->after('no_invoice');
                }
                if (!Schema::hasColumn('pos', 'alamat_1')) {
                    $table->text('alamat_1')->nullable()->after('pengirim');
                }
                if (!Schema::hasColumn('pos', 'alamat_2')) {
                    $table->text('alamat_2')->nullable()->after('alamat_1');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            $table->dropColumn(['customer', 'no_invoice', 'pengirim', 'alamat_1', 'alamat_2']);
        });
    }
};
