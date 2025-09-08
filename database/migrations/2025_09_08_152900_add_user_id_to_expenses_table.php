<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (!Schema::hasColumn('expenses', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable()->after('amount');
                    // Jika ingin menambahkan FK (opsional, aman tanpa):
                    // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('expenses')) {
            Schema::table('expenses', function (Blueprint $table) {
                if (Schema::hasColumn('expenses', 'user_id')) {
                    // $table->dropForeign(['user_id']); // jika FK diaktifkan
                    $table->dropColumn('user_id');
                }
            });
        }
    }
};
