<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('expenses')) {
            Schema::create('expenses', function (Blueprint $table) {
                $table->id();
                $table->date('tanggal');
                $table->string('jenis');
                $table->string('deskripsi')->nullable();
                $table->bigInteger('amount');
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(['tanggal','jenis']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
