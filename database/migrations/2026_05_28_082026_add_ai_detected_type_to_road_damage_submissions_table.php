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
        Schema::table('road_damage_submissions', function (Blueprint $table) {
            // Menambahkan kolom baru untuk mencatat histori tebakan asli dari AI
            // ditaruh tepat setelah kolom 'damage_type' agar rapi di phpMyAdmin
            $table->string('ai_detected_type')->nullable()->after('damage_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('road_damage_submissions', function (Blueprint $table) {
            // Menghapus kembali kolom jika migrasi ini di-rollback
            $table->dropColumn('ai_detected_type');
        });
    }
};