<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jatuh_tempos', function (Blueprint $table) {
            if (!Schema::hasColumn('jatuh_tempos', 'status_approval')) {
                $table->string('status_approval', 20)->default('Pending')->after('status_pembayaran');
            }
            if (!Schema::hasColumn('jatuh_tempos', 'hari_terlambat')) {
                // Guard: ensure column exists for older schemas
                $table->unsignedInteger('hari_terlambat')->default(0)->after('status_approval');
            }
            // Helpful indexes for filtering
            $table->index(['tanggal_jatuh_tempo']);
            $table->index(['status_approval']);
        });
    }

    public function down(): void
    {
        Schema::table('jatuh_tempos', function (Blueprint $table) {
            if (Schema::hasColumn('jatuh_tempos', 'status_approval')) {
                $table->dropColumn('status_approval');
            }
            // Keep hari_terlambat if it was pre-existing
            if (Schema::hasColumn('jatuh_tempos', 'hari_terlambat')) {
                // do not drop; comment out to preserve data
                // $table->dropColumn('hari_terlambat');
            }
            // Drop indexes if they exist
            try { $table->dropIndex(['tanggal_jatuh_tempo']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['status_approval']); } catch (\Throwable $e) {}
        });
    }
};
