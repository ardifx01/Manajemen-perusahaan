<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom payment_terms_days ke tabel customers yang sudah ada
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'payment_terms_days')) {
                $table->integer('payment_terms_days')->default(30)->after('address_2');
            }
        });
    }

    public function down()
    {
        if (Schema::hasTable('customers')) {
            Schema::table('customers', function (Blueprint $table) {
                if (Schema::hasColumn('customers', 'payment_terms_days')) {
                    $table->dropColumn('payment_terms_days');
                }
            });
        }
    }
};
