<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('customers', 'code_number')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->string('code_number')->nullable()->after('nama_customer');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('customers', 'code_number')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('code_number');
            });
        }
    }
};
