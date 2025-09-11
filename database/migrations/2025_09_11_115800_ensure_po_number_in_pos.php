<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('pos') && !Schema::hasColumn('pos', 'po_number')) {
            Schema::table('pos', function (Blueprint $table) {
                $table->integer('po_number')->nullable()->after('no_po');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('pos') && Schema::hasColumn('pos', 'po_number')) {
            Schema::table('pos', function (Blueprint $table) {
                $table->dropColumn('po_number');
            });
        }
    }
};
