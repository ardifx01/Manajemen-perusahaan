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
        Schema::table('pos', function (Blueprint $table) {
            if (!Schema::hasColumn('pos', 'qty_jenis')) {
                // Jika kolom qty ada, letakkan setelah qty; jika tidak, tambahkan tanpa after()
                if (Schema::hasColumn('pos', 'qty')) {
                    $table->string('qty_jenis', 10)->nullable()->after('qty');
                } else {
                    $table->string('qty_jenis', 10)->nullable();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'qty_jenis')) {
                $table->dropColumn('qty_jenis');
            }
        });
    }
};
