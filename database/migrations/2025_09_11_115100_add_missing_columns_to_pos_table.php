<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (!Schema::hasColumn('pos', 'pengirim')) {
                    $table->string('pengirim')->nullable()->after('customer');
                }
                if (!Schema::hasColumn('pos', 'alamat_1')) {
                    $table->text('alamat_1')->nullable()->after('pengirim');
                }
                if (!Schema::hasColumn('pos', 'alamat_2')) {
                    $table->text('alamat_2')->nullable()->after('alamat_1');
                }
                if (!Schema::hasColumn('pos', 'qty_jenis')) {
                    $table->string('qty_jenis', 50)->nullable()->after('qty');
                }
                if (Schema::hasColumn('pos', 'nopol') && !Schema::hasColumn('pos', 'no_polisi')) {
                    $table->renameColumn('nopol', 'no_polisi');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (Schema::hasColumn('pos', 'pengirim')) {
                    $table->dropColumn('pengirim');
                }
                if (Schema::hasColumn('pos', 'alamat_1')) {
                    $table->dropColumn('alamat_1');
                }
                if (Schema::hasColumn('pos', 'alamat_2')) {
                    $table->dropColumn('alamat_2');
                }
                if (Schema::hasColumn('pos', 'qty_jenis')) {
                    $table->dropColumn('qty_jenis');
                }
            });
        }
    }
};
