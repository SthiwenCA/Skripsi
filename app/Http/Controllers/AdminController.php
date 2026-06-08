<?php

namespace App\Http\Controllers;

use App\Models\RoadDamageSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        if (auth()->guest() || auth()->user()->email !== 'admin@gmail.com') {
            abort(403);
        }

        // 1. Ambil data laporan kerusakan baru beserta data usernya
        $pendingReports = RoadDamageSubmission::with('user')->where('status', 'pending')->latest()->get();
        
        // 2. Ambil data pengajuan perbaikan jalan beserta data usernya
        $pendingFixedReports = RoadDamageSubmission::with('user')->where('status', 'pending_fixed')->latest()->get();
        
        // 3. Ambil semua data selain pending untuk Tab History & Delete beserta data usernya
        $allReports = RoadDamageSubmission::with('user')->whereIn('status', ['approved', 'fixed', 'denied'])->latest()->get();

        return view('admin.dashboard', compact('pendingReports', 'pendingFixedReports', 'allReports'));
    }

    public function approve(Request $request, $id)
    {
        $report = RoadDamageSubmission::findOrFail($id);
        $report->status = 'approved';
        if ($request->has('damage_type') && !empty($request->damage_type)) {
            $report->damage_type = $request->damage_type;
        }
        $report->save();

        return redirect()->back()->with('success', 'Laporan kerusakan disetujui dan ditayangkan di peta.');
    }

    public function approveFixed($id)
    {
        $report = RoadDamageSubmission::findOrFail($id);
        $report->status = 'fixed'; 
        $report->save();

        return redirect()->back()->with('success', 'Perbaikan jalan tervalidasi! Laporan resmi diarsipkan dan dilepas dari peta aktif.');
    }

    public function reject($id)
    {
        $report = RoadDamageSubmission::findOrFail($id);
        $report->status = 'denied';
        $report->save();

        return redirect()->back()->with('success', 'Laporan berhasil ditolak.');
    }

    // =========================================================================
    // FITUR DELETE PERMANEN (Mengatasi Error Undefined Method destroy)
    // =========================================================================
    public function destroy($id)
    {
        $report = RoadDamageSubmission::findOrFail($id);
        
        // Hapus foto awal dari server jika ada
        if ($report->image_path && Storage::disk('public')->exists($report->image_path)) {
            Storage::disk('public')->delete($report->image_path);
        }

        // Hapus foto bukti perbaikan dari server jika ada
        if ($report->fixed_image_path && Storage::disk('public')->exists($report->fixed_image_path)) {
            Storage::disk('public')->delete($report->fixed_image_path);
        }

        // Hapus baris data dari database
        $report->delete();

        return redirect()->back()->with('success', 'Laporan beserta fotonya berhasil dihapus secara permanen dari sistem.');
    }
}