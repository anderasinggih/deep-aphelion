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
        Schema::table('pengaduan_komentars', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->foreignId('user_id')->nullable()->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('pengaduan_komentars', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('pengaduan_komentars', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        } catch (\Exception $e) {}

        try {
            Schema::table('pengaduan_komentars', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        } catch (\Exception $e) {}
    }
};
