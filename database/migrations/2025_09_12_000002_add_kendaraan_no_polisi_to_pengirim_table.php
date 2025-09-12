<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengirim', function (Blueprint $table) {
            if (!Schema::hasColumn('pengirim', 'kendaraan')) {
                $table->string('kendaraan')->nullable()->after('nama');
            }
            if (!Schema::hasColumn('pengirim', 'no_polisi')) {
                $table->string('no_polisi')->nullable()->after('kendaraan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pengirim', function (Blueprint $table) {
            if (Schema::hasColumn('pengirim', 'no_polisi')) {
                $table->dropColumn('no_polisi');
            }
            if (Schema::hasColumn('pengirim', 'kendaraan')) {
                $table->dropColumn('kendaraan');
            }
        });
    }
};
