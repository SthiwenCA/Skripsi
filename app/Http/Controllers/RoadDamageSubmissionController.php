<?php

namespace App\Http\Controllers;

use App\Models\RoadDamageSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class RoadDamageSubmissionController extends Controller
{
    public function create()
    {
        $todayDate = Carbon::now()->toDateString();
        return view('submissions.create', compact('todayDate'));
    }

    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'submission_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Simpan Gambar ke Storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Simpan ke storage/app/public/submissions
            $image->storeAs('submissions', $imageName, 'public');
            $validatedData['image_path'] = 'submissions/' . $imageName;
        }

        // Jalur absolut file untuk dikirim ke Python (Aman di VPS Linux)
        $absolutePath = storage_path('app/public/' . $validatedData['image_path']);

        // 3. KIRIM KE AI (PYTHON)
        $damageType = 'Unknown'; // Default jika AI gagal
        
        try {
            // Menerapkan env() agar URL API Python dinamis
            $pythonApiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000/predict');
            
            $response = Http::attach(
                'image', file_get_contents($absolutePath), $imageName
            )->post($pythonApiUrl);

            if ($response->successful()) {
                $damageType = $response->json()['damage_type'];
            }
        } catch (\Exception $e) {
            // Jika server Python mati, biarkan statusnya 'Pending' atau 'Error'
            $damageType = 'AI Server Offline';
        }

        // 4. Simpan ke Database
        RoadDamageSubmission::create([
            'user_id' => auth()->id(),
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'submission_date' => $validatedData['submission_date'],
            'image_path' => $validatedData['image_path'],
            
            // ===============================================================
            // UPDATE: MENYIMPAN HISTORI AI
            // ===============================================================
            'ai_detected_type' => $damageType, // Menyimpan tebakan asli/murni AI
            'damage_type' => $damageType,      // Tebakan awal (yang nanti bisa di-edit Admin)
            
            'address' => $request->input('address', 'Alamat tidak ditemukan'),
            'status' => 'pending',
        ]);

        return redirect('/')->with('success', 'Laporan Berhasil : Menunggu Verifikasi Admin');
    }
}