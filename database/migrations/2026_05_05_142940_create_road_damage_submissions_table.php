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
            $blueprint->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Melacak pengirim
            
            // Kolom Baru untuk Koordinat Peta
            $blueprint->decimal('latitude', 10, 8); 
            $blueprint->decimal('longitude', 11, 8); 
            
            // Kolom ini dibuat nullable (boleh kosong) karena akan diurus oleh AI dan sistem nanti
            $blueprint->string('address')->nullable(); 
            $blueprint->string('damage_type')->nullable(); 
            
            $blueprint->date('submission_date'); // Tanggal Kirim
            $blueprint->string('image_path'); // Jalur file foto
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