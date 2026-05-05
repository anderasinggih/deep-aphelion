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
            $table->date('tanggal_kejadian')->nullable()->after('deskripsi');
            $table->enum('prioritas', ['rendah', 'sedang', 'tinggi'])->default('sedang')->after('tanggal_kejadian');
            $table->text('harapan_pelapor')->nullable()->after('prioritas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kejadian', 'prioritas', 'harapan_pelapor']);
        });
    }
};
