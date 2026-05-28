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
        Schema::table('pengaduan_dukungans', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['user_id']);
            
            // Drop unique index
            $table->dropUnique(['user_id', 'pengaduan_id']);
            
            // Make user_id nullable
            $table->foreignId('user_id')->nullable()->change();
            
            // Add ip_address column
            $table->string('ip_address')->nullable()->after('pengaduan_id');
            
            // Re-add foreign key
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Re-create user_id + pengaduan_id uniqueness
            $table->unique(['user_id', 'pengaduan_id']);
            
            // Create ip_address + pengaduan_id uniqueness
            $table->unique(['ip_address', 'pengaduan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->dropUnique(['ip_address', 'pengaduan_id']);
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->dropUnique(['user_id', 'pengaduan_id']);
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->dropColumn('ip_address');
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable(false)->change();
            });
        } catch (\Exception $e) {}
        
        try {
            Schema::table('pengaduan_dukungans', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['user_id', 'pengaduan_id']);
            });
        } catch (\Exception $e) {}
    }
};
