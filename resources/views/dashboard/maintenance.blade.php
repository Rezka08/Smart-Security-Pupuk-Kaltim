@extends('layouts.app')

@section('title', 'Dashboard Maintenance')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-tools"></i> Dashboard Maintenance</h2>
        <p class="text-muted">Selamat datang, {{ auth()->user()->full_name }}</p>
    </div>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-danger">
            <div class="card-body">
                <h6 class="text-muted">Tiket Terbuka</h6>
                <h2 class="text-danger">{{ $stats['open_tickets'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted">Sedang Dikerjakan</h6>
                <h2 class="text-warning">{{ $stats['my_in_progress'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted">Selesai</h6>
                <h2 class="text-success">{{ $stats['my_resolved'] }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted">Total Ditugaskan</h6>
                <h2 class="text-primary">{{ $stats['assigned_tickets'] }}</h2>
            </div>
        </div>
    </div>
</div>

<!-- Available Tickets -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Tiket Menunggu Penanganan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Pelapor</th>
                                <th>Deskripsi Masalah</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($availableTickets as $ticket)
                            <tr>
                                <td><strong>{{ $ticket->ticket_code }}</strong></td>
                                <td>{{ $ticket->reporter->full_name }}</td>
                                <td>{{ \Str::limit($ticket->issue_description, 50) }}</td>
                                <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-success">
                                    <i class="bi bi-check-circle"></i> Tidak ada tiket yang menunggu
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- My Tickets -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tiket yang Saya Tangani</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Kode Tiket</th>
                                <th>Pelapor</th>
                                <th>Deskripsi Masalah</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myTickets as $ticket)
                            <tr>
                                <td><strong>{{ $ticket->ticket_code }}</strong></td>
                                <td>{{ $ticket->reporter->full_name }}</td>
                                <td>{{ \Str::limit($ticket->issue_description, 50) }}</td>
                                <td>
                                    @if($ticket->status == 'In Progress')
                                        <span class="badge bg-warning">In Progress</span>
                                    @elseif($ticket->status == 'Resolved')
                                        <span class="badge bg-success">Resolved</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $ticket->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info text-white">
                                        <i class="bi bi-pencil"></i> Update
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Belum ada tiket yang ditugaskan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('tickets.index') }}" class="btn btn-outline-primary mt-2">
                    Lihat Semua Tiket <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection