@extends('layouts.app')

@section('title', 'Riwayat Log CCTV')

@section('content')
<div class="row mb-3">
    <div class="col-md-6">
        <h2><i class="bi bi-camera-video"></i> Riwayat Log CCTV</h2>
    </div>
    <div class="col-md-6 text-end">
        <a href="{{ route('cctv-logs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Input Log Baru
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Waktu Log</th>
                        <th>Petugas</th>
                        <th>Shift</th>
                        <th>Lokasi</th>
                        <th>Kejadian</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td>{{ $logs->firstItem() + $index }}</td>
                        <td>{{ $log->log_time->format('d/m/Y H:i') }}</td>
                        <td>{{ $log->user->full_name }}</td>
                        <td><span class="badge bg-secondary">{{ $log->shift }}</span></td>
                        <td>{{ $log->camera_location }}</td>
                        <td>{{ \Str::limit($log->incident_description, 40) }}</td>
                        <td>
                            @if($log->status == 'Aman')
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Aman</span>
                            @else
                                <span class="badge bg-danger"><i class="bi bi-exclamation-triangle"></i> Tidak Aman</span>
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()->isAdmin() || $log->user_id == auth()->id())
                                <a href="{{ route('cctv-logs.edit', $log) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif
                            
                            @if($log->evidence_photo_url)
                                <a href="{{ Storage::url($log->evidence_photo_url) }}" target="_blank" class="btn btn-sm btn-info text-white">
                                    <i class="bi bi-image"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">Belum ada data log CCTV</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection