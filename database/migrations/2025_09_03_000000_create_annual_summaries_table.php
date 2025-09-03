<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('annual_summaries', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique();

            // Aggregates per tahun
            $table->bigInteger('revenue_net_total')->default(0); // Net tanpa PPN
            $table->bigInteger('expense_salary_total')->default(0);
            $table->bigInteger('expense_other_total')->default(0);
            $table->bigInteger('expense_total')->default(0);

            $table->unsignedInteger('employee_count')->default(0);
            $table->unsignedInteger('invoice_count')->default(0);

            $table->bigInteger('barang_masuk_qty')->default(0);
            $table->bigInteger('barang_keluar_qty')->default(0);

            $table->json('meta')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('annual_summaries');
    }
};
