<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (!Schema::hasColumn('pos', 'no_po')) {
                    $table->string('no_po', 191)->nullable()->after('no_surat_jalan');
                }
                if (!Schema::hasColumn('pos', 'no_invoice')) {
                    $table->string('no_invoice', 191)->nullable()->after('no_po');
                }
                if (!Schema::hasColumn('pos', 'customer_id')) {
                    $table->unsignedBigInteger('customer_id')->nullable()->after('no_invoice');
                }
                if (!Schema::hasColumn('pos', 'customer')) {
                    $table->string('customer', 191)->nullable()->after('customer_id');
                }
                if (!Schema::hasColumn('pos', 'tanggal_po')) {
                    $table->date('tanggal_po')->nullable()->after('customer');
                }
                if (!Schema::hasColumn('pos', 'produk_id')) {
                    $table->unsignedBigInteger('produk_id')->nullable()->after('tanggal_po');
                }
                if (!Schema::hasColumn('pos', 'qty')) {
                    $table->integer('qty')->default(0)->after('produk_id');
                }
                if (!Schema::hasColumn('pos', 'qty_jenis')) {
                    $table->string('qty_jenis', 50)->nullable()->after('qty');
                }
                if (!Schema::hasColumn('pos', 'harga')) {
                    $table->decimal('harga', 15, 2)->default(0)->after('qty_jenis');
                }
                if (!Schema::hasColumn('pos', 'total')) {
                    $table->decimal('total', 15, 2)->default(0)->after('harga');
                }
                if (!Schema::hasColumn('pos', 'kendaraan')) {
                    $table->string('kendaraan', 191)->nullable()->after('total');
                }
                if (!Schema::hasColumn('pos', 'no_polisi')) {
                    $table->string('no_polisi', 100)->nullable()->after('kendaraan');
                }
                if (!Schema::hasColumn('pos', 'alamat_1')) {
                    $table->string('alamat_1', 191)->nullable()->after('no_polisi');
                }
                if (!Schema::hasColumn('pos', 'alamat_2')) {
                    $table->string('alamat_2', 191)->nullable()->after('alamat_1');
                }
                if (!Schema::hasColumn('pos', 'pengirim')) {
                    $table->string('pengirim', 191)->nullable()->after('alamat_2');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                foreach ([
                    'pengirim','alamat_2','alamat_1','no_polisi','kendaraan','total','harga','qty_jenis','qty','produk_id','tanggal_po','customer','customer_id','no_invoice','no_po'
                ] as $col) {
                    if (Schema::hasColumn('pos', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
