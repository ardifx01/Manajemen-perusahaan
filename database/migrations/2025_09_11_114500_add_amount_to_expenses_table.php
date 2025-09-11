<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('expenses') && !Schema::hasColumn('expenses', 'amount')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->decimal('amount', 15, 2)->default(0)->after('jumlah');
            });
            // Sync nilai amount dari kolom jumlah jika ada
            if (Schema::hasColumn('expenses', 'jumlah')) {
                DB::statement('UPDATE expenses SET amount = jumlah');
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expenses') && Schema::hasColumn('expenses', 'amount')) {
            Schema::table('expenses', function (Blueprint $table) {
                $table->dropColumn('amount');
            });
        }
    }
};
