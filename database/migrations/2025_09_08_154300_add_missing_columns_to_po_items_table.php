<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('po_items')) {
            Schema::table('po_items', function (Blueprint $table) {
                if (!Schema::hasColumn('po_items', 'po_id')) {
                    $table->unsignedBigInteger('po_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('po_items', 'produk_id')) {
                    $table->unsignedBigInteger('produk_id')->nullable()->after('po_id');
                }
                if (!Schema::hasColumn('po_items', 'qty')) {
                    $table->integer('qty')->default(0)->after('produk_id');
                }
                if (!Schema::hasColumn('po_items', 'qty_jenis')) {
                    $table->string('qty_jenis', 50)->nullable()->after('qty');
                }
                if (!Schema::hasColumn('po_items', 'harga')) {
                    $table->decimal('harga', 15, 2)->default(0)->after('qty_jenis');
                }
                if (!Schema::hasColumn('po_items', 'total')) {
                    $table->decimal('total', 15, 2)->default(0)->after('harga');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('po_items')) {
            Schema::table('po_items', function (Blueprint $table) {
                foreach (['total','harga','qty_jenis','qty','produk_id','po_id'] as $col) {
                    if (Schema::hasColumn('po_items', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
