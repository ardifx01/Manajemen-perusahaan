<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pengirim')) {
            Schema::create('pengirim', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->timestamps();
            });
        }

        // Seed minimal data jika kosong
        $count = DB::table('pengirim')->count();
        if ($count === 0) {
            DB::table('pengirim')->insert([
                ['nama' => 'PT. Default Pengirim', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down(): void
    {
        // Jangan drop untuk mencegah kehilangan data
    }
};
