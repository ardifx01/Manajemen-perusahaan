<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('phone', 50)->nullable();
                $table->text('address_1')->nullable();
                $table->text('address_2')->nullable();
                $table->integer('payment_terms_days')->default(30);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Do not drop in down to avoid data loss unexpectedly
    }
};
