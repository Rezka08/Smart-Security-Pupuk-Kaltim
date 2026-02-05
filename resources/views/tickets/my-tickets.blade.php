@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')
<div class="row mb-3">
    <div class="col-12">
        <h2><i class="bi bi-ticket-detailed"></i> Tiket Perbaikan Saya</h2>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Kode Tiket</th>
                        <th>Deskripsi Masalah</th>
                        <th>Teknisi</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $index => $ticket)
                    <tr>
                        <td>{{ $tickets->firstItem() + $index }}</td>
                        <td><strong>{{ $ticket->ticket_code }}</strong></td>
                        <td>{{ \Str::limit($ticket->issue_description, 50) }}</td>
                        <td>{{ $ticket->technician ? $ticket->technician->full_name : '-' }}</td>
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
                        <td>{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('tickets.show', $ticket) }}" class="btn btn-sm btn-info text-white">
                                <i class="bi bi-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada tiket</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection