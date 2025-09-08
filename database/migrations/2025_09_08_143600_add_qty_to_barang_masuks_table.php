<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('barang_masuks')) {
            Schema::table('barang_masuks', function (Blueprint $table) {
                if (!Schema::hasColumn('barang_masuks', 'qty')) {
                    // Jika kolom produk_id ada, letakkan setelahnya; jika tidak, tambahkan tanpa after()
                    if (Schema::hasColumn('barang_masuks', 'produk_id')) {
                        $table->unsignedInteger('qty')->default(0)->after('produk_id');
                    } else {
                        $table->unsignedInteger('qty')->default(0);
                    }
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('barang_masuks')) {
            Schema::table('barang_masuks', function (Blueprint $table) {
                if (Schema::hasColumn('barang_masuks', 'qty')) {
                    $table->dropColumn('qty');
                }
            });
        }
    }
};
