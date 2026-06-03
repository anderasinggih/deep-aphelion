<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PrintController extends Controller
{
    public function resi($id)
    {
        $pengaduan = \App\Models\Pengaduan::with(['user', 'kategori'])->findOrFail($id);
        
        // Ensure only administrative roles or the owner/guest owner can print resi
        $isAdmin = auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin', 'petugas']);
        $isOwner = auth()->check() && auth()->id() === $pengaduan->user_id;
        $isGuestOwner = !auth()->check() && is_null($pengaduan->user_id);

        if (!$isAdmin && !$isOwner && !$isGuestOwner) {
            abort(403, 'Unauthorized action.');
        }
        $settings = \App\Models\Setting::all()->pluck('value', 'key');
        $ttd = [
            'jabatan' => $settings['ttd_jabatan'] ?? 'Admin Sistem Kembaran Ngadu',
            'nama' => $settings['ttd_nama'] ?? '',
            'file' => $settings['ttd_file'] ?? null,
        ];

        return view('print.resi', compact('pengaduan', 'ttd', 'settings'));
    }

    public function laporan(Request $request)
    {
        // Administrative roles only
        if (!auth()->check() || !in_array(auth()->user()->role, ['superadmin', 'admin', 'petugas'])) {
            abort(403, 'Unauthorized action.');
        }

        $query = \App\Models\Pengaduan::with(['user', 'kategori']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $pengaduans = $query->orderBy('created_at', 'desc')->get();

        $filterInfo = [
            'status'     => $request->status,
            'start_date' => $request->start_date,
            'end_date'   => $request->end_date,
        ];
        
        $settings = \App\Models\Setting::all()->pluck('value', 'key');

        return view('print.rekap-laporan', compact('pengaduans', 'filterInfo', 'settings'));
    }
}
