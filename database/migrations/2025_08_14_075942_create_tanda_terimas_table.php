<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tanda_terimas', function (Blueprint $table) {
            $table->id();
            $table->string('no_tanda_terima')->unique();
            $table->string('no_po');
            $table->string('no_surat_jalan')->nullable();
            $table->string('customer');
            $table->string('alamat_1')->nullable();
            $table->string('alamat_2')->nullable();
            $table->date('tanggal_terima');
            $table->unsignedBigInteger('produk_id');
            $table->integer('qty_dikirim');
            $table->integer('qty_diterima');
            $table->enum('qty_jenis', ['PCS', 'SET']);
            $table->enum('kondisi_barang', ['Baik', 'Rusak', 'Sebagian Rusak']);
            $table->enum('status', ['Lengkap', 'Sebagian', 'Pending']);
            $table->string('penerima_nama');
            $table->string('penerima_jabatan')->nullable();
            $table->text('catatan')->nullable();
            $table->string('foto_bukti')->nullable();
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tanda_terimas');
    }
};
