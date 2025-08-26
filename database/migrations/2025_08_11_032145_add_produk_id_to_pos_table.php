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
        Schema::table('pos', function (Blueprint $table) {
            // Tambahkan kolom produk_id jika belum ada
            if (!Schema::hasColumn('pos', 'produk_id')) {
                $table->unsignedBigInteger('produk_id')->nullable()->after('tanggal_po');
                $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'produk_id')) {
                $table->dropForeign(['produk_id']);
                $table->dropColumn('produk_id');
            }
        });
    }
};
