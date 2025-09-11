<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('kendaraans')) {
            Schema::create('kendaraans', function (Blueprint $table) {
                $table->id();
                $table->string('nama_kendaraan');
                $table->string('no_polisi')->nullable();
                $table->string('jenis')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
