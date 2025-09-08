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
                if (!Schema::hasColumn('pos', 'no_surat_jalan')) {
                    $table->string('no_surat_jalan', 191)->nullable()->after('id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (Schema::hasColumn('pos', 'no_surat_jalan')) {
                    $table->dropColumn('no_surat_jalan');
                }
            });
        }
    }
};
