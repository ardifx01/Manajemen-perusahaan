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
                $table->unsignedBigInteger('produk_id')->nullable();
                $table->integer('qty')->default(0);
                $table->string('qty_jenis', 50)->default('PCS');
                $table->decimal('harga', 15, 2)->default(0);
                $table->decimal('total', 15, 2)->default(0);
                $table->timestamps();

                $table->foreign('po_id')->references('id')->on('pos')->onDelete('cascade');
                $table->foreign('produk_id')->references('id')->on('produks')->onDelete('set null');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('po_items');
    }
};
