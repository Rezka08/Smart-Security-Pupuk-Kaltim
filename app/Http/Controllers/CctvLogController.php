<?php

namespace App\Http\Controllers;

use App\Models\CctvLog;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class CctvLogController extends Controller
{
    public function index()
    {
        $logs = CctvLog::with('user')
            ->latest()
            ->paginate(20);

        return view('cctv-logs.index', compact('logs'));
    }

    public function create()
    {
        return view('cctv-logs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shift' => 'required|in:A,B,C,D',
            'log_time' => 'required|date',
            'camera_location' => 'required|string|max:255',
            'incident_description' => 'required|string',
            'action_taken' => 'required|string',
            'status' => 'required|in:Aman,Tidak Aman',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Validasi foto WAJIB jika status "Tidak Aman"
        if ($validated['status'] === 'Tidak Aman') {
            $request->validate([
                'evidence_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'evidence_photo.required' => 'Foto bukti wajib diupload untuk status Tidak Aman.',
            ]);
        }

        DB::beginTransaction();
        try {
            // Upload foto jika ada
            $photoPath = null;
            if ($request->hasFile('evidence_photo')) {
                $photoPath = $request->file('evidence_photo')->store('cctv-evidence', 'public');
            }

            // Simpan CCTV Log
            $cctvLog = CctvLog::create([
                'user_id' => auth()->id(),
                'shift' => $validated['shift'],
                'log_time' => $validated['log_time'],
                'camera_location' => $validated['camera_location'],
                'incident_description' => $validated['incident_description'],
                'action_taken' => $validated['action_taken'],
                'status' => $validated['status'],
                'evidence_photo_url' => $photoPath,
            ]);

            // AUTO-CREATE TICKET jika status "Tidak Aman"
            if ($validated['status'] === 'Tidak Aman') {
                MaintenanceTicket::create([
                    'ticket_code' => MaintenanceTicket::generateTicketCode(),
                    'reporter_id' => auth()->id(),
                    'source_cctv_log_id' => $cctvLog->id,
                    'issue_description' => 'Kondisi Tidak Aman di ' . $validated['camera_location'] . ': ' . $validated['incident_description'],
                    'status' => 'Open',
                ]);
            }

            DB::commit();

            return redirect()->route('cctv-logs.index')
                ->with('success', 'Log CCTV berhasil disimpan.' . 
                    ($validated['status'] === 'Tidak Aman' ? ' Tiket maintenance otomatis dibuat.' : ''));

        } catch (\Exception $e) {
            DB::rollBack();
            
            // Hapus foto jika sudah terupload
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(CctvLog $cctvLog)
    {
        // Security dapat edit log mereka sendiri
        if (auth()->user()->role !== 'admin' && $cctvLog->user_id !== auth()->id()) {
            abort(403);
        }

        return view('cctv-logs.edit', compact('cctvLog'));
    }

    public function update(Request $request, CctvLog $cctvLog)
    {
        // Security dapat edit log mereka sendiri
        if (auth()->user()->role !== 'admin' && $cctvLog->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'shift' => 'required|in:A,B,C,D',
            'log_time' => 'required|date',
            'camera_location' => 'required|string|max:255',
            'incident_description' => 'required|string',
            'action_taken' => 'required|string',
            'status' => 'required|in:Aman,Tidak Aman',
            'evidence_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validated['status'] === 'Tidak Aman' && !$request->hasFile('evidence_photo') && !$cctvLog->evidence_photo_url) {
            return back()->withErrors(['evidence_photo' => 'Foto bukti wajib diupload untuk status Tidak Aman.']);
        }

        // Upload foto baru jika ada
        if ($request->hasFile('evidence_photo')) {
            // Hapus foto lama
            if ($cctvLog->evidence_photo_url) {
                Storage::disk('public')->delete($cctvLog->evidence_photo_url);
            }
            $validated['evidence_photo_url'] = $request->file('evidence_photo')->store('cctv-evidence', 'public');
        }

        $cctvLog->update($validated);

        return redirect()->route('cctv-logs.index')
            ->with('success', 'Log CCTV berhasil diupdate.');
    }

    public function destroy(CctvLog $cctvLog)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        if ($cctvLog->evidence_photo_url) {
            Storage::disk('public')->delete($cctvLog->evidence_photo_url);
        }

        $cctvLog->delete();

        return redirect()->route('cctv-logs.index')
            ->with('success', 'Log CCTV berhasil dihapus.');
    }
}