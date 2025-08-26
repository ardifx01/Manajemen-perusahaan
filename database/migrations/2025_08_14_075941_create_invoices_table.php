<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->string('no_po');
            $table->string('customer');
            $table->string('alamat_1')->nullable();
            $table->string('alamat_2')->nullable();
            $table->date('tanggal_invoice');
            $table->date('tanggal_jatuh_tempo');
            $table->unsignedBigInteger('produk_id');
            $table->integer('qty');
            $table->enum('qty_jenis', ['PCS', 'SET']);
            $table->integer('harga');
            $table->integer('total');
            $table->integer('pajak')->nullable();
            $table->integer('grand_total');
            $table->enum('status', ['Draft', 'Sent', 'Paid', 'Overdue'])->default('Draft');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
