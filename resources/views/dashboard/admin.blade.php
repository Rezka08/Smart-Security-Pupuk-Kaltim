@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-speedometer2"></i> Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, {{ auth()->user()->full_name }}</p>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Total Users</h6>
                        <h2 class="mb-0">{{ $stats['total_users'] }}</h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Log CCTV</h6>
                        <h2 class="mb-0">{{ $stats['total_cctv_logs'] }}</h2>
                    </div>
                    <i class="bi bi-camera-video" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Kondisi Tidak Aman</h6>
                        <h2 class="mb-0">{{ $stats['unsafe_conditions'] }}</h2>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title">Tiket Terbuka</h6>
                        <h2 class="mb-0">{{ $stats['open_tickets'] }}</h2>
                    </div>
                    <i class="bi bi-wrench" style="font-size: 2.5rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts & Recent Activities -->
<div class="row">
    <!-- Log CCTV per Shift -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Log CCTV per Shift (7 Hari Terakhir)</h5>
            </div>
            <div class="card-body">
                <canvas id="shiftChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Ticket Status -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Status Tiket Maintenance</h5>
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Open
                        <span class="badge bg-danger rounded-pill">{{ $stats['open_tickets'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        In Progress
                        <span class="badge bg-warning rounded-pill">{{ $stats['in_progress_tickets'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Resolved
                        <span class="badge bg-success rounded-pill">{{ $stats['resolved_tickets'] }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Recent Logs -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Log CCTV Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Petugas</th>
                                <th>Shift</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr>
                                <td>{{ $log->log_time->format('d/m/Y H:i') }}</td>
                                <td>{{ $log->user->full_name }}</td>
                                <td><span class="badge bg-info">{{ $log->shift }}</span></td>
                                <td>{{ $log->camera_location }}</td>
                                <td>
                                    @if($log->status == 'Aman')
                                        <span class="badge bg-success">Aman</span>
                                    @else
                                        <span class="badge bg-danger">Tidak Aman</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Report Form -->
<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="bi bi-file-pdf"></i> Export Laporan PDF</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('reports.export') }}" method="GET">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-secondary">
                        <i class="bi bi-download"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const ctx = document.getElementById('shiftChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Shift A', 'Shift B', 'Shift C', 'Shift D'],
        datasets: [{
            label: 'Jumlah Log',
            data: [
                {{ $cctvByShift->where('shift', 'A')->first()->total ?? 0 }},
                {{ $cctvByShift->where('shift', 'B')->first()->total ?? 0 }},
                {{ $cctvByShift->where('shift', 'C')->first()->total ?? 0 }},
                {{ $cctvByShift->where('shift', 'D')->first()->total ?? 0 }}
            ],
            backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>
@endpush