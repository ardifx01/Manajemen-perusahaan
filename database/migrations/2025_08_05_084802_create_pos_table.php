<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pos');
    }
};
