<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('produks')) {
            Schema::table('produks', function (Blueprint $table) {
                // Pastikan semua kolom penting ada
                if (!Schema::hasColumn('produks', 'kode_produk')) {
                    $table->string('kode_produk')->nullable();
                }
                if (!Schema::hasColumn('produks', 'nama_produk')) {
                    $table->string('nama_produk');
                }
                if (!Schema::hasColumn('produks', 'harga')) {
                    $table->decimal('harga', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('produks', 'harga_pcs')) {
                    $table->decimal('harga_pcs', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('produks', 'harga_set')) {
                    $table->decimal('harga_set', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('produks', 'satuan')) {
                    $table->string('satuan')->nullable();
                }
                if (!Schema::hasColumn('produks', 'deskripsi')) {
                    $table->text('deskripsi')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('produks')) {
            Schema::table('produks', function (Blueprint $table) {
                // Hapus kolom tambahan yang kita tambahkan jika ada
                foreach (['deskripsi','satuan','harga_set','harga_pcs','harga','nama_produk','kode_produk'] as $col) {
                    if (Schema::hasColumn('produks', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
