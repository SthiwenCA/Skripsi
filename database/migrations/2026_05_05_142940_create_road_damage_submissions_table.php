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
        Schema::create('road_damage_submissions', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Jika ingin melacak pengirim
            $blueprint->string('address'); // Untuk Alamat
            $blueprint->string('damage_type'); // Untuk Tipe Kerusakan (misalnya, Cracks, Pothole, Deformation)
            $blueprint->date('submission_date'); // Untuk Tanggal Kirim
            $blueprint->string('image_path'); // Untuk jalur file foto
            $blueprint->text('notes')->nullable(); // Opsional: Catatan tambahan
            $blueprint->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('road_damage_submissions');
    }
};