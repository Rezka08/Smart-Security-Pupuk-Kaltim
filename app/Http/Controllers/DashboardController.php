<?php

namespace App\Http\Controllers;

use App\Models\CctvLog;
use App\Models\InventoryCheck;
use App\Models\MaintenanceTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        } elseif ($user->isSecurity()) {
            return $this->securityDashboard();
        } elseif ($user->isMaintenance()) {
            return $this->maintenanceDashboard();
        }
    }

    private function adminDashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'total_cctv_logs' => CctvLog::count(),
            'unsafe_conditions' => CctvLog::where('status', 'Tidak Aman')->count(),
            'total_inventory_checks' => InventoryCheck::count(),
            'open_tickets' => MaintenanceTicket::where('status', 'Open')->count(),
            'in_progress_tickets' => MaintenanceTicket::where('status', 'In Progress')->count(),
            'resolved_tickets' => MaintenanceTicket::where('status', 'Resolved')->count(),
        ];

        // Grafik Logs per Shift (7 hari terakhir)
        $cctvByShift = CctvLog::selectRaw('shift, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('shift')
            ->get();

        // Recent Activities
        $recentLogs = CctvLog::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentTickets = MaintenanceTicket::with('reporter')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin', compact('stats', 'cctvByShift', 'recentLogs', 'recentTickets'));
    }

    private function securityDashboard()
    {
        $stats = [
            'my_cctv_logs' => CctvLog::where('user_id', auth()->id())->count(),
            'my_inventory_checks' => InventoryCheck::where('user_id', auth()->id())->count(),
            'my_open_tickets' => MaintenanceTicket::where('reporter_id', auth()->id())
                ->where('status', 'Open')
                ->count(),
        ];

        $recentLogs = CctvLog::where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        $myTickets = MaintenanceTicket::where('reporter_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.security', compact('stats', 'recentLogs', 'myTickets'));
    }

    private function maintenanceDashboard()
    {
        $stats = [
            'assigned_tickets' => MaintenanceTicket::where('technician_id', auth()->id())->count(),
            'open_tickets' => MaintenanceTicket::whereNull('technician_id')
                ->where('status', 'Open')
                ->count(),
            'my_in_progress' => MaintenanceTicket::where('technician_id', auth()->id())
                ->where('status', 'In Progress')
                ->count(),
            'my_resolved' => MaintenanceTicket::where('technician_id', auth()->id())
                ->where('status', 'Resolved')
                ->count(),
        ];

        $availableTickets = MaintenanceTicket::with('reporter')
            ->whereNull('technician_id')
            ->where('status', 'Open')
            ->latest()
            ->take(5)
            ->get();

        $myTickets = MaintenanceTicket::with('reporter')
            ->where('technician_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.maintenance', compact('stats', 'availableTickets', 'myTickets'));
    }

    // Validation Reports (Admin Only)
    public function validation()
    {
        $cctvLogs = CctvLog::with('user')
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        $inventoryChecks = InventoryCheck::with(['user', 'details.inventoryItem'])
            ->whereDate('created_at', today())
            ->latest()
            ->get();

        return view('reports.validation', compact('cctvLogs', 'inventoryChecks'));
    }

    // Export PDF (Admin Only)
    public function exportPdf(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $cctvLogs = CctvLog::with('user')
            ->whereBetween('log_time', [$startDate, $endDate])
            ->orderBy('log_time', 'desc')
            ->get();

        $inventoryChecks = InventoryCheck::with(['user', 'details.inventoryItem'])
            ->whereBetween('check_date', [$startDate, $endDate])
            ->orderBy('check_date', 'desc')
            ->get();

        $tickets = MaintenanceTicket::with(['reporter', 'technician'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $pdf = Pdf::loadView('reports.pdf', compact('cctvLogs', 'inventoryChecks', 'tickets', 'startDate', 'endDate'));
        
        return $pdf->download('laporan-' . $startDate . '-to-' . $endDate . '.pdf');
    }
}