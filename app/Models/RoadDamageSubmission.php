<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoadDamageSubmission extends Model
{
    use HasFactory;

    // Tambahkan kode ini agar Laravel mengizinkan penyimpanan data
    protected $fillable = [
        'user_id',
        'address',
        'damage_type',
        'submission_date',
        'image_path',
        'notes', // Jika kamu menambahkan kolom notes sebelumnya
    ];

    // (Opsional) Relasi ke model User
    // Ini memberitahu sistem bahwa 1 Laporan dimiliki oleh 1 User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}