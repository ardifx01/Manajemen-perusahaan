<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('salaries')) {
            Schema::create('salaries', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('employee_id');
                $table->unsignedTinyInteger('bulan'); // 1-12
                $table->unsignedSmallInteger('tahun');
                $table->decimal('gaji_pokok', 15, 2)->default(0);
                $table->decimal('tunjangan', 15, 2)->default(0);
                $table->decimal('potongan', 15, 2)->default(0);
                $table->decimal('total_gaji', 15, 2)->default(0);
                $table->string('status', 50)->default('paid');
                $table->timestamps();

                $table->index(['employee_id', 'bulan', 'tahun']);
                // FK optional (jika tabel employees belum ada lengkap, lewati supaya tidak gagal)
                // $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
