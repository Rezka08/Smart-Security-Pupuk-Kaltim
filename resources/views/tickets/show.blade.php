@extends('layouts.app')

@section('title', 'Detail Tiket')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-ticket-detailed"></i> Detail Tiket Perbaikan</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Kode Tiket:</strong><br>
                        <span class="badge bg-primary fs-6">{{ $ticket->ticket_code }}</span>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Status:</strong><br>
                        @if($ticket->status == 'Open')
                            <span class="badge bg-danger fs-6">Open</span>
                        @elseif($ticket->status == 'In Progress')
                            <span class="badge bg-warning fs-6">In Progress</span>
                        @elseif($ticket->status == 'Resolved')
                            <span class="badge bg-success fs-6">Resolved</span>
                        @else
                            <span class="badge bg-secondary fs-6">Closed</span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <strong><i class="bi bi-person"></i> Pelapor:</strong><br>
                    {{ $ticket->reporter->full_name }} ({{ $ticket->reporter->role }})
                </div>

                <div class="mb-3">
                    <strong><i class="bi bi-calendar"></i> Tanggal Dibuat:</strong><br>
                    {{ $ticket->created_at->format('d F Y, H:i') }}
                </div>

                @if($ticket->technician)
                <div class="mb-3">
                    <strong><i class="bi bi-person-gear"></i> Teknisi:</strong><br>
                    {{ $ticket->technician->full_name }}
                </div>
                @endif

                <div class="mb-3">
                    <strong><i class="bi bi-file-text"></i> Deskripsi Masalah:</strong>
                    <div class="alert alert-light mt-2">
                        {{ $ticket->issue_description }}
                    </div>
                </div>

                @if($ticket->sourceCctvLog)
                <div class="mb-3">
                    <strong><i class="bi bi-camera-video"></i> Sumber: Log CCTV</strong>
                    <div class="alert alert-info mt-2">
                        <strong>Lokasi:</strong> {{ $ticket->sourceCctvLog->camera_location }}<br>
                        <strong>Waktu:</strong> {{ $ticket->sourceCctvLog->log_time->format('d/m/Y H:i') }}<br>
                        <strong>Status:</strong> {{ $ticket->sourceCctvLog->status }}
                    </div>
                </div>
                @endif

                @if($ticket->sourceInventoryDetail)
                <div class="mb-3">
                    <strong><i class="bi bi-box"></i> Sumber: Inventaris</strong>
                    <div class="alert alert-info mt-2">
                        <strong>Barang:</strong> {{ $ticket->sourceInventoryDetail->inventoryItem->item_name }}<br>
                        <strong>Kondisi:</strong> {{ $ticket->sourceInventoryDetail->condition }}<br>
                        <strong>Keterangan:</strong> {{ $ticket->sourceInventoryDetail->remarks ?? '-' }}
                    </div>
                </div>
                @endif

                @if($ticket->resolution_notes)
                <div class="mb-3">
                    <strong><i class="bi bi-check-circle"></i> Catatan Penyelesaian:</strong>
                    <div class="alert alert-success mt-2">
                        {{ $ticket->resolution_notes }}
                    </div>
                </div>
                @endif

                <hr>

                @if(auth()->user()->isMaintenance())
                <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label"><strong>Update Status</strong></label>
                        <select name="status" class="form-select" required>
                            <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Catatan Penyelesaian</strong></label>
                        <textarea name="resolution_notes" class="form-control" rows="4" 
                                  placeholder="Jelaskan tindakan perbaikan yang telah dilakukan...">{{ old('resolution_notes', $ticket->resolution_notes) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('tickets.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Tiket
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center">
                    <a href="{{ route('my-tickets') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection