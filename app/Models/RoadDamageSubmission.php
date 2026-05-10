<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadDamageSubmission extends Model
{
    use HasFactory;

    // Daftar kolom yang diizinkan untuk diisi secara otomatis (Mass Assignment)
    protected $fillable = [
        'user_id',
        'latitude',       // Kolom baru untuk koordinat garis lintang
        'longitude',      // Kolom baru untuk koordinat garis bujur
        'address',        // Tetap dibiarkan fillable untuk diisi default/AI nantinya
        'damage_type',    // Tetap dibiarkan fillable untuk diisi default/AI nantinya
        'submission_date',
        'image_path',
        'notes',          // Jika kamu menambahkan kolom notes sebelumnya
    ];

    // Relasi ke model User
    // Ini memberitahu sistem bahwa 1 Laporan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}