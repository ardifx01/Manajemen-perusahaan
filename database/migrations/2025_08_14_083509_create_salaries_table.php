<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('salaries')) {
            Schema::create('salaries', function (Blueprint $table) {
                $table->id();
                $table->foreignId('employee_id')->constrained()->onDelete('cascade');
                $table->integer('bulan'); // 1-12
                $table->integer('tahun');
                $table->decimal('gaji_pokok', 15, 2);
                $table->decimal('tunjangan', 15, 2)->default(0);
                $table->decimal('bonus', 15, 2)->default(0);
                $table->decimal('lembur', 15, 2)->default(0);
                $table->decimal('potongan_pajak', 15, 2)->default(0);
                $table->decimal('potongan_bpjs', 15, 2)->default(0);
                $table->decimal('potongan_lain', 15, 2)->default(0);
                $table->decimal('total_gaji', 15, 2);
                $table->enum('status_pembayaran', ['belum_dibayar', 'dibayar'])->default('belum_dibayar');
                $table->date('tanggal_bayar')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();

                // Index untuk mencegah duplikasi gaji per karyawan per bulan/tahun
                $table->unique(['employee_id', 'bulan', 'tahun']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('salaries');
    }
};

