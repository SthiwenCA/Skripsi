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

    // Fitur Setujui Laporan (Approve)
    public function approve($id)
    {
        if (auth()->guest() || auth()->user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        $report = RoadDamageSubmission::findOrFail($id);
        $report->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Laporan berhasil disetujui dan langsung tayang di peta publik!');
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