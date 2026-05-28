<?php

namespace App\Http\Controllers;

use App\Models\RoadDamageSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    // Menampilkan Halaman List Admin (Menggantikan Dashboard Peta Lama)
    public function index()
    {
        if (auth()->guest() || auth()->user()->email !== 'admin@gmail.com') {
            abort(403, 'Akses Ditolak.');
        }

        // Ambil data laporan yang masih pending
        $pendingReports = RoadDamageSubmission::where('status', 'pending')->latest()->get();
        
        // Ambil semua data laporan untuk tab Delete Disaster
        $allReports = RoadDamageSubmission::latest()->get();

        return view('admin.dashboard', compact('pendingReports', 'allReports'));
    }

    // =========================================================================
    // FITUR SETUJUI LAPORAN (APPROVE) - UPDATE TANGKAP DAMAGE TYPE
    // =========================================================================
    public function approve(Request $request, $id)
    {
        if (auth()->guest() || auth()->user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $report = RoadDamageSubmission::findOrFail($id);
        
        // 1. Update status laporan menjadi disetujui
        $report->status = 'approved';

        // 2. Jika Admin mengubah tipe kerusakan di dropdown, tangkap dan update datanya
        if ($request->has('damage_type') && !empty($request->damage_type)) {
            $report->damage_type = $request->damage_type;
        }

        // 3. Simpan perubahan ke database
        $report->save();

        return redirect()->back()->with('success', 'Laporan berhasil disetujui dan tipe kerusakan telah diupdate!');
    }

    // Fitur Delete Laporan (Delete Disaster)
    public function destroy($id)
    {
        if (auth()->guest() || auth()->user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $report = RoadDamageSubmission::findOrFail($id);
        
        if (Storage::disk('public')->exists($report->image_path)) {
            Storage::disk('public')->delete($report->image_path);
        }

        $report->delete();

        return redirect()->back()->with('success', 'Data laporan berhasil dihapus dari sistem.');
    }
}