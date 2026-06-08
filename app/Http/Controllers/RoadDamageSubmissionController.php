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
        $validatedData = $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'submission_date' => 'required|date',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:10240', 
        ]);

        $isDuplicate = RoadDamageSubmission::where('user_id', auth()->id())
            ->where('latitude', $validatedData['latitude'])
            ->where('longitude', $validatedData['longitude'])
            ->where('created_at', '>=', Carbon::now()->subMinute())
            ->exists(); 

        if ($isDuplicate) {
            return redirect()->back()->with('error', 'Laporan di koordinat ini sudah terkirim. Mohon tunggu sebentar untuk mengirim laporan baru.');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $safeName = str_replace(' ', '_', $image->getClientOriginalName());
            $imageName = time() . '_' . $safeName;
            
            $image->storeAs('submissions', $imageName, 'public');
            $validatedData['image_path'] = 'submissions/' . $imageName;
        }

        $absolutePath = storage_path('app/public/' . $validatedData['image_path']);
        $damageType = 'Unknown'; 
        
        try {
            $pythonApiUrl = env('PYTHON_API_URL', 'http://127.0.0.1:5000/predict');
            $response = Http::attach(
                'image', file_get_contents($absolutePath), $imageName
            )->post($pythonApiUrl);

            if ($response->successful()) {
                $damageType = $response->json()['damage_type'];
            }
        } catch (\Exception $e) {
            $damageType = 'AI Server Offline';
        }

        RoadDamageSubmission::create([
            'user_id' => auth()->id(),
            'latitude' => $validatedData['latitude'],
            'longitude' => $validatedData['longitude'],
            'submission_date' => $validatedData['submission_date'],
            'image_path' => $validatedData['image_path'],
            'ai_detected_type' => $damageType, 
            'damage_type' => $damageType,      
            'address' => $request->input('address', 'Alamat tidak ditemukan'),
            'status' => 'pending',
        ]);

        return redirect('/')->with('success', 'Laporan Berhasil : Menunggu Verifikasi Admin');
    }

    public function submitRoadFixed(Request $request, $id)
    {
        $request->validate([
            'fixed_image' => 'required|image|mimes:jpeg,png,jpg|max:10240'
        ]);

        $report = RoadDamageSubmission::findOrFail($id);

        // ===============================================================
        // UPDATE KEAMANAN: HANYA PEMILIK LAPORAN (ATAU ADMIN) YANG BISA UPLOAD BUKTI
        // ===============================================================
        if ($report->user_id !== auth()->id() && auth()->user()->email !== 'admin@gmail.com') {
            return redirect()->back()->with('error', 'Akses ditolak. Anda tidak memiliki izin untuk mengubah laporan orang lain.');
        }

        if ($request->hasFile('fixed_image')) {
            if ($report->fixed_image_path) {
                Storage::disk('public')->delete($report->fixed_image_path);
            }

            $path = $request->file('fixed_image')->store('fixed_roads', 'public');
            
            $report->fixed_image_path = $path;
            $report->status = 'pending_fixed'; 
            $report->save();
        }

        return redirect()->back()->with('success', 'Bukti foto perbaikan berhasil dikirim! Menunggu validasi Admin.');
    }
    
    // =========================================================================
    // FITUR HISTORY LAPORAN USER
    // =========================================================================
    public function history()
    {
        // Ambil semua laporan yang dibuat oleh user yang sedang login
        $reports = RoadDamageSubmission::where('user_id', auth()->id())->latest()->get();
        
        return view('user.history', compact('reports'));
    }
}