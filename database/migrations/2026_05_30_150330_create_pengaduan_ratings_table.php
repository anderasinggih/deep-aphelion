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
        Schema::create('pengaduan_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengaduan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->integer('rating_pelayanan');
            $table->integer('rating_respon');
            $table->integer('rating_kompetensi');
            $table->integer('rating_fasilitas');
            $table->integer('rating'); // average of 4 metrics
            $table->text('rating_komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan_ratings');
    }
};
