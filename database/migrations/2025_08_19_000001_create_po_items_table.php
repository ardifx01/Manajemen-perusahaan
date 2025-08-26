<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('po_items')) {
            Schema::create('po_items', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('po_id');
                $table->unsignedBigInteger('produk_id');
                $table->integer('qty');
                $table->string('qty_jenis', 10)->default('PCS');
                $table->integer('harga')->default(0);
                $table->integer('total')->default(0);
                $table->timestamps();

                $table->foreign('po_id')->references('id')->on('pos')->onDelete('cascade');
                $table->foreign('produk_id')->references('id')->on('produks')->onDelete('restrict');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('po_items');
    }
};
