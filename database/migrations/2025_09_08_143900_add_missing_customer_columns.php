<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                // Tambahkan kolom jika belum ada, tanpa mengandalkan after() supaya aman
                if (!Schema::hasColumn('customers', 'address_1')) {
                    $table->string('address_1')->nullable();
                }
                if (!Schema::hasColumn('customers', 'address_2')) {
                    $table->string('address_2')->nullable();
                }
                if (!Schema::hasColumn('customers', 'delivery_note_number')) {
                    $table->string('delivery_note_number')->nullable();
                }
                if (!Schema::hasColumn('customers', 'invoice_number')) {
                    $table->string('invoice_number')->nullable();
                }
                if (!Schema::hasColumn('customers', 'payment_terms_days')) {
                    $table->integer('payment_terms_days')->default(30);
                }
                if (!Schema::hasColumn('customers', 'nama_customer')) {
                    $table->string('nama_customer')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (Schema::hasColumn('customers', 'nama_customer')) {
                    $table->dropColumn('nama_customer');
                }
                if (Schema::hasColumn('customers', 'invoice_number')) {
                    $table->dropColumn('invoice_number');
                }
                if (Schema::hasColumn('customers', 'delivery_note_number')) {
                    $table->dropColumn('delivery_note_number');
                }
                if (Schema::hasColumn('customers', 'address_2')) {
                    $table->dropColumn('address_2');
                }
                if (Schema::hasColumn('customers', 'address_1')) {
                    $table->dropColumn('address_1');
                }
                if (Schema::hasColumn('customers', 'payment_terms_days')) {
                    $table->dropColumn('payment_terms_days');
                }
            });
        }
    }
};
