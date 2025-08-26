<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (!Schema::hasColumn('pos', 'no_invoice')) {
                $table->string('no_invoice')->nullable()->after('no_po');
            }
        });
    }

    public function down(): void
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'no_invoice')) {
                $table->dropColumn('no_invoice');
            }
        });
    }
};
