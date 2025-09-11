<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // expenses: ensure columns exist
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'tanggal')) {
                    $table->date('tanggal')->after('id');
                }
                if (!Schema::hasColumn('expenses', 'jenis')) {
                    $table->string('jenis')->after('tanggal');
                }
                if (!Schema::hasColumn('expenses', 'deskripsi')) {
                    $table->string('deskripsi')->nullable()->after('jenis');
                }
                // Legacy optional description column
                if (!Schema::hasColumn('expenses', 'description')) {
                    $table->text('description')->nullable()->after('deskripsi');
                }
                if (!Schema::hasColumn('expenses', 'amount')) {
                    $table->bigInteger('amount')->default(0)->after('description');
                }
                if (!Schema::hasColumn('expenses', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('amount');
                }
            });
            // Ensure index on (tanggal, jenis)
            try {
                \DB::statement('CREATE INDEX IF NOT EXISTS expenses_tanggal_jenis_index ON expenses (tanggal, jenis)');
            } catch (\Throwable $e) { /* ignore for MySQL older versions */ }
        }

        // employees: ensure name column exists for legacy compatibility
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                if (!Schema::hasColumn('employees', 'name')) {
                    $table->string('name')->nullable()->after('status');
                }
            });
        }

        // salaries: ensure status_pembayaran exists
        if (Schema::hasTable('salaries')) {
            Schema::table('salaries', function (Blueprint $table) {
                if (!Schema::hasColumn('salaries', 'status_pembayaran')) {
                    $table->string('status_pembayaran')->default('Pending')->after('total_gaji');
                }
            });
        }
    }

    public function down(): void
    {
        // We won't drop columns in down to avoid data loss
    }
};
