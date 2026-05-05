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
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->string('foto_penyelesaian')->nullable()->after('foto_bukti');
            $table->text('pesan_penutup')->nullable()->after('foto_penyelesaian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['foto_penyelesaian', 'pesan_penutup']);
        });
    }
};
