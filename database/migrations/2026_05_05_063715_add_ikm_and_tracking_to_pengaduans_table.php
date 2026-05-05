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
            $table->string('kode_tracking')->unique()->nullable()->after('id');
            $table->integer('rating')->nullable()->after('status');
            $table->text('rating_komentar')->nullable()->after('rating');
        });

        // Backfill existing records with a tracking code
        $pengaduans = \App\Models\Pengaduan::all();
        foreach ($pengaduans as $p) {
            if (empty($p->kode_tracking)) {
                $p->kode_tracking = 'KMB-' . $p->created_at->format('Ym') . '-' . str_pad($p->id, 4, '0', STR_PAD_LEFT);
                $p->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['kode_tracking', 'rating', 'rating_komentar']);
        });
    }
};
