<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Pastikan tabel settings ada
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->timestamps();
            });
        }

        // Tambahkan next_invoice_number jika belum ada
        if (!Schema::hasColumn('settings', 'next_invoice_number')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->integer('next_invoice_number')->default(1000)->after('value');
            });
        }

        // Inisialisasi nilai default jika belum ada
        if (\DB::table('settings')->where('key', 'next_invoice_number')->doesntExist()) {
            \DB::table('settings')->insert([
                'key' => 'next_invoice_number',
                'value' => '1000',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus kolom next_invoice_number
        if (Schema::hasColumn('settings', 'next_invoice_number')) {
            Schema::table('settings', function (Blueprint $table) {
                $table->dropColumn('next_invoice_number');
            });
        }
    }
};
