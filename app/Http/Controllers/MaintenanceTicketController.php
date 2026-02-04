<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;

class MaintenanceTicketController extends Controller
{
    // Untuk Maintenance: Lihat semua tiket
    public function index()
    {
        $tickets = MaintenanceTicket::with(['reporter', 'technician', 'sourceCctvLog', 'sourceInventoryDetail.inventoryItem'])
            ->latest()
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    // Detail Tiket
    public function show(MaintenanceTicket $ticket)
    {
        $ticket->load(['reporter', 'technician', 'sourceCctvLog', 'sourceInventoryDetail.inventoryItem']);
        return view('tickets.show', compact('ticket'));
    }

    // Update Status Tiket (Maintenance Only)
    public function update(Request $request, MaintenanceTicket $ticket)
    {
        $validated = $request->validate([
            'status' => 'required|in:Open,In Progress,Resolved,Closed',
            'resolution_notes' => 'nullable|string',
        ]);

        // Assign technician jika belum ada
        if (!$ticket->technician_id && $request->status === 'In Progress') {
            $ticket->technician_id = auth()->id();
        }

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Status tiket berhasil diupdate.');
    }

    // Tiket yang dibuat oleh user (Admin & Security)
    public function myTickets()
    {
        $tickets = MaintenanceTicket::with(['technician', 'sourceCctvLog', 'sourceInventoryDetail.inventoryItem'])
            ->where('reporter_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('tickets.my-tickets', compact('tickets'));
    }
}