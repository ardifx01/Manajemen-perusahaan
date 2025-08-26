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
            if (!Schema::hasColumn('pos', 'alamat_1')) {
                $table->string('alamat_1')->nullable()->after('customer');
            }
            if (!Schema::hasColumn('pos', 'alamat_2')) {
                $table->string('alamat_2')->nullable()->after('alamat_1');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'alamat_2')) {
                $table->dropColumn('alamat_2');
            }
            if (Schema::hasColumn('pos', 'alamat_1')) {
                $table->dropColumn('alamat_1');
            }
        });
    }
};
