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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat_jalan', 100);
            $table->string('no_po', 100);
            $table->string('customer', 255);
            $table->text('alamat')->nullable();
            $table->date('tanggal_po');
            $table->unsignedBigInteger('produk_id');
            $table->integer('qty');
            $table->string('qty_jenis', 10); // PCS, SET, etc
            $table->bigInteger('harga');
            $table->bigInteger('total');
            $table->string('kendaraan', 100)->nullable();
            $table->string('no_polisi', 20)->nullable();
            $table->string('driver', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
            
            // Indexes for better performance
            $table->index('tanggal_po');
            $table->index('customer');
            $table->index('no_po');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};