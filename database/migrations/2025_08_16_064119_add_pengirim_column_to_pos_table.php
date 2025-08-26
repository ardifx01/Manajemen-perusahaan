<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pos', function (Blueprint $table) {
            if (!Schema::hasColumn('pos', 'pengirim')) {
                $table->string('pengirim')->nullable()->after('customer');
            }
        });
    }

    public function down()
    {
        Schema::table('pos', function (Blueprint $table) {
            if (Schema::hasColumn('pos', 'pengirim')) {
                $table->dropColumn('pengirim');
            }
        });
    }
};