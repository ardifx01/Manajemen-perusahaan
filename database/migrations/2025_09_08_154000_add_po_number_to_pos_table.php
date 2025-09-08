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
                if (!Schema::hasColumn('pos', 'po_number')) {
                    $table->string('po_number', 191)->nullable()->after('no_po');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pos')) {
            Schema::table('pos', function (Blueprint $table) {
                if (Schema::hasColumn('pos', 'po_number')) {
                    $table->dropColumn('po_number');
                }
            });
        }
    }
};
