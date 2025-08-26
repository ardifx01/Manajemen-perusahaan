<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('jatuh_tempos', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('no_po');
            $table->string('customer');
            $table->date('tanggal_invoice');
            $table->date('tanggal_jatuh_tempo');
            $table->bigInteger('jumlah_tagihan');
            $table->bigInteger('jumlah_terbayar')->default(0);
            $table->bigInteger('sisa_tagihan');
            $table->enum('status_pembayaran', ['Belum Bayar', 'Sebagian', 'Lunas'])->default('Belum Bayar');
            $table->integer('hari_terlambat')->default(0);
            $table->bigInteger('denda')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->date('last_reminder_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('jatuh_tempos');
    }
};
