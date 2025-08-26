<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            $table->dropColumn('produk'); // Hapus kolom produk
        });
    }

    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            $table->string('produk'); // Kalau rollback, kolom produk dibuat lagi
        });
    }
};
