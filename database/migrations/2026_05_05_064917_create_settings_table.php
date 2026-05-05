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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert Default Settings
        \Illuminate\Support\Facades\DB::table('settings')->insert([
            [
                'key' => 'sop_waktu_pemrosesan',
                'value' => 'Laporan akan diverifikasi maksimal **3x24 Jam Kerja** sejak dikirimkan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sop_jam_operasional',
                'value' => 'Senin - Jumat <br> Pukul **08:00 - 15:00 WIB**.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sop_dasar_hukum',
                'value' => 'Sesuai UU Pelayanan Publik dan UU PDP. Identitas Anda dijamin aman.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sop_tindak_lanjut',
                'value' => 'Laporan yang valid akan langsung diteruskan ke instansi terkait untuk diselesaikan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
