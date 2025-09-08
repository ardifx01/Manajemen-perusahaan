<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (!Schema::hasColumn('customers', 'invoice_number')) {
                // Jika kolom delivery_note_number ada, letakkan setelahnya; jika tidak, tambahkan tanpa after()
                if (Schema::hasColumn('customers', 'delivery_note_number')) {
                    $table->string('invoice_number')->nullable()->after('delivery_note_number');
                } else {
                    $table->string('invoice_number')->nullable();
                }
            }
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            if (Schema::hasColumn('customers', 'invoice_number')) {
                $table->dropColumn('invoice_number');
            }
        });
    }
};
