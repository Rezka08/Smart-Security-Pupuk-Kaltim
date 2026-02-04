@extends('layouts.app')

@section('title', 'Dashboard Petugas')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-person-badge"></i> Dashboard Petugas Keamanan</h2>
        <p class="text-muted">Selamat datang, {{ auth()->user()->full_name }}</p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Log CCTV Saya</h6>
                <h2 class="text-primary">{{ $stats['my_cctv_logs'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Pengecekan Inventaris</h6>
                <h2 class="text-success">{{ $stats['my_inventory_checks'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted">Tiket Terbuka</h6>
                <h2 class="text-warning">{{ $stats['my_open_tickets'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('cctv-logs.create') }}" class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-camera-video"></i> Input Log CCTV
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('inventory.pos.create') }}" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-clipboard-check"></i> Cek Inventaris Pos
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('inventory.general.create') }}" class="btn btn-info btn-lg w-100 text-white">
                            <i class="bi bi-box-seam"></i> Cek Inventaris General
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Logs -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Log CCTV Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Shift</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentLogs as $log)
                            <tr>
                                <td>{{ $log->log_time->format('d/m H:i') }}</td>
                                <td><span class="badge bg-secondary">{{ $log->shift }}</span></td>
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
                                <td colspan="4" class="text-center text-muted">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('cctv-logs.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tiket Saya</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Deskripsi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myTickets as $ticket)
                            <tr>
                                <td><strong>{{ $ticket->ticket_code }}</strong></td>
                                <td>{{ \Str::limit($ticket->issue_description, 30) }}</td>
                                <td>
                                    @if($ticket->status == 'Open')
                                        <span class="badge bg-danger">Open</span>
                                    @elseif($ticket->status == 'In Progress')
                                        <span class="badge bg-warning">In Progress</span>
                                    @elseif($ticket->status == 'Resolved')
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary">Closed</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">Belum ada tiket</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('my-tickets') }}" class="btn btn-sm btn-outline-primary mt-2">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection