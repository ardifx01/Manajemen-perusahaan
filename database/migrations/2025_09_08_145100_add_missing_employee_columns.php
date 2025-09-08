<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                // Kolom inti yang dipakai aplikasi
                if (!Schema::hasColumn('employees', 'nama_karyawan')) {
                    $table->string('nama_karyawan')->nullable();
                }
                if (!Schema::hasColumn('employees', 'email')) {
                    $table->string('email')->nullable();
                }
                if (!Schema::hasColumn('employees', 'no_telepon')) {
                    $table->string('no_telepon')->nullable();
                }
                if (!Schema::hasColumn('employees', 'alamat')) {
                    $table->text('alamat')->nullable();
                }
                if (!Schema::hasColumn('employees', 'posisi')) {
                    $table->string('posisi')->nullable();
                }
                if (!Schema::hasColumn('employees', 'departemen')) {
                    $table->string('departemen')->nullable();
                }
                if (!Schema::hasColumn('employees', 'gaji_pokok')) {
                    $table->decimal('gaji_pokok', 15, 2)->default(0);
                }
                if (!Schema::hasColumn('employees', 'status')) {
                    $table->enum('status', ['aktif','tidak_aktif'])->default('aktif');
                }
                if (!Schema::hasColumn('employees', 'tanggal_masuk')) {
                    $table->date('tanggal_masuk')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employees')) {
            Schema::table('employees', function (Blueprint $table) {
                // Hanya drop kolom yang kita tambahkan jika ada
                foreach (['tanggal_masuk','status','gaji_pokok','departemen','posisi','alamat','no_telepon','email','nama_karyawan'] as $col) {
                    if (Schema::hasColumn('employees', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
