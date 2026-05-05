<?php

namespace App\Http\Controllers;

use App\Models\RoadDamageSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoadDamageSubmissionController extends Controller
{
    /**
     * Tampilkan formulir untuk pelaporan kerusakan baru.
     */
    public function create()
    {
        // Mendapatkan tanggal hari ini secara real-time untuk formulir
        $todayDate = Carbon::now()->toDateString();
        
        return view('submissions.create', compact('todayDate'));
    }

    /**
     * Simpan pelaporan kerusakan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'address' => 'required|string|max:255',
            'damage_type' => 'required|string',
            'submission_date' => 'required|date',
            // Validasi jenis gambar: png, jpg, jpeg. Ukuran maksimal 2MB.
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'notes' => 'nullable|string',
        ], [
            // Pesan kesalahan kustom (opsional)
            'image.mimes' => 'Hanya file gambar dengan tipe png, jpg, atau jpeg yang diperbolehkan.',
            'image.max' => 'Ukuran gambar maksimal adalah 2MB.',
        ]);

        // Menyimpan gambar ke penyimpanan
        // Kita simpan di folder 'public/submissions'
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('public/submissions', $imageName);
            // Jalur yang akan disimpan di database
            $validatedData['image_path'] = 'submissions/' . $imageName; 
        }

        // Hapus input file dari data yang divalidasi karena kita tidak menyimpannya langsung
        unset($validatedData['image']);

        // Tambahkan user_id jika user sedang login
        if (auth()->check()) {
            $validatedData['user_id'] = auth()->id();
        }

        // Membuat entri baru di database
        RoadDamageSubmission::create($validatedData);

        return redirect()->route('dashboard')->with('success', 'Laporan kerusakan jalan berhasil dikirim!');
    }
}