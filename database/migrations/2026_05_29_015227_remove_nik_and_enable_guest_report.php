<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Hapus NIK dari tabel users
        if (Schema::hasColumn('users', 'nik')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('nik');
            });
        }

        // 2. Ubah user_id menjadi nullable di tabel pengaduans dan tambahkan kolom guest_name & guest_wa
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
            
            if (!Schema::hasColumn('pengaduans', 'guest_name')) {
                $table->string('guest_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('pengaduans', 'guest_wa')) {
                $table->string('guest_wa')->nullable()->after('guest_name');
            }
        });

        // 3. Tambahkan setting default untuk whatsapp_admin
        DB::table('settings')->updateOrInsert(
            ['key' => 'whatsapp_admin'],
            [
                'value' => '628123456789',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 4. Ubah user_id menjadi nullable di tabel pengaduan_histories
        Schema::table('pengaduan_histories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke semula (jika rollback)
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn(['guest_name', 'guest_wa']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('nik', 16)->nullable()->unique();
        });

        DB::table('settings')->where('key', 'whatsapp_admin')->delete();
    }
};
