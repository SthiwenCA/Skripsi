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
        // 1. Validasi Input
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'submission_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', 
        ]);

        // ===============================================================
        // 1.5. CEK DUPLIKASI (ANTI SPAM-CLICK)
        // Mengecek apakah user ini baru saja mengirim laporan di koordinat 
        // yang sama dalam 1 menit terakhir.
        // ===============================================================
        $isDuplicate = RoadDamageSubmission::where('user_id', auth()->id())
            ->where('latitude', $validatedData['latitude'])
            ->where('longitude', $validatedData['longitude'])
            ->where('created_at', '>=', Carbon::now()->subMinute())
            ->exists(); // Gunakan exists() agar query lebih ringan

        if ($isDuplicate) {
            // Jika terdeteksi duplikat, kembalikan ke halaman sebelumnya dengan pesan error
            // Request dihentikan di sini, sehingga AI dan Storage tidak terbebani
            return redirect()->back()->with('error', 'Laporan di koordinat ini sudah terkirim. Mohon tunggu sebentar untuk mengirim laporan baru.');
        }

        // 2. Simpan Gambar ke Storage
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            $safeName = str_replace(' ', '_', $image->getClientOriginalName());
            $imageName = time() . '_' . $safeName;
            
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
            // ==========================================
            // DEBUGGING MODE: ON
            // Kita matikan sementara teks "Offline" ini
            $damageType = 'AI Server Offline';
            
            // Kita paksa aplikasi berhenti dan memunculkan error aslinya ke layar
            // dd([
            //     'Pesan_Error_Asli' => $e->getMessage(),
            //     'Target_URL_API' => $pythonApiUrl,
            //     'Lokasi_File_Gambar' => $absolutePath
            // ]);
            // ==========================================
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