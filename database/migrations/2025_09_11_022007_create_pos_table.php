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
        // Hanya buat tabel jika belum ada (idempotent)
        if (!Schema::hasTable('pos')) {
            Schema::create('pos', function (Blueprint $table) {
                $table->id();
                $table->string('no_surat_jalan');
                $table->string('no_po');
                $table->string('customer');
                $table->date('tanggal_po');
                $table->string('produk');
                $table->integer('qty');
                $table->integer('harga');
                $table->integer('total');
                $table->string('no_invoice')->nullable();
                $table->string('pengirim')->nullable();
                $table->text('alamat_1')->nullable();
                $table->text('alamat_2')->nullable();
                $table->string('kendaraan')->nullable();
                $table->string('nopol')->nullable();
                $table->string('qty_jenis')->nullable();
                $table->unsignedBigInteger('produk_id')->nullable();
                $table->unsignedBigInteger('kendaraan_id')->nullable();
                $table->unsignedBigInteger('customer_id')->nullable();
                $table->timestamps();
                
                // Foreign keys
                $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
                $table->foreign('kendaraan_id')->references('id')->on('kendaraans')->onDelete('set null');
                $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
