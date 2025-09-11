<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('produks')) {
            Schema::table('produks', function (Blueprint $table) {
                if (!Schema::hasColumn('produks', 'name')) {
                    $table->string('name')->nullable()->after('nama_produk');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('produks') && Schema::hasColumn('produks', 'name')) {
            Schema::table('produks', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
