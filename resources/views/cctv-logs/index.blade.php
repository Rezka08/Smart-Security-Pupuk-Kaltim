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

<div class="card mb-3 border-primary">
    <div class="card-body py-3">
        <form action="{{ route('cctv-logs.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label for="date" class="form-label mb-0 fw-semibold"><i class="bi bi-calendar3"></i> Pilih Tanggal</label>
            </div>
            <div class="col-auto">
                <input type="date" 
                       name="date" 
                       id="date" 
                       value="{{ $selectedDate }}" 
                       class="form-control"
                       max="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search"></i> Tampilkan
                </button>
            </div>
            <div class="col-auto">
                <a href="{{ route('cctv-logs.index', ['date' => now()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">
                    Hari Ini
                </a>
            </div>
            <div class="col-auto">
                <a href="{{ route('cctv-logs.index', ['date' => now()->subDay()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">
                    Kemarin
                </a>
            </div>
        </form>
        <p class="mb-0 mt-2 text-muted small">
            <i class="bi bi-info-circle"></i> Menampilkan log untuk tanggal <strong>{{ \Carbon\Carbon::parse($selectedDate)->locale('id')->translatedFormat('l, d F Y') }}</strong>
        </p>
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
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x display-6 d-block mb-2 text-secondary"></i>
                            Belum ada data log CCTV untuk tanggal tersebut.<br>
                            <small>Pilih tanggal lain atau <a href="{{ route('cctv-logs.create') }}">input log baru</a></small>
                        </td>
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