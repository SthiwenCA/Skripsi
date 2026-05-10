<?php

namespace App\Http\Controllers;

use App\Models\RoadDamageSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // WAJIB ADA untuk menembak API Python
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

        // Jalur absolut file untuk dikirim ke Python
        $absolutePath = storage_path('app/public/' . $validatedData['image_path']);

        // 3. KIRIM KE AI (PYTHON) - Pastikan server Python nyala di port 5000
        $damageType = 'Unknown'; // Default jika AI gagal
        
        try {
            $response = Http::attach(
                'image', file_get_contents($absolutePath), $imageName
            )->post('http://127.0.0.1:5000/predict');

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
            'damage_type' => $damageType, 
            'address' => $request->input('address', 'Alamat tidak ditemukan'), // Mengambil alamat asli dari peta
        ]);

        return redirect('/')->with('success', 'Laporan berhasil dianalisis oleh AI: ' . $damageType);
    }
}