@extends('layouts.app')

@section('title', 'Validasi Laporan Harian')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-clipboard-check"></i> Validasi Laporan Harian</h2>
        <p class="text-muted">Tanggal: {{ now()->format('d F Y') }}</p>
    </div>
    <div class="col-md-4 text-end">
        <form action="{{ route('reports.export') }}" method="GET" class="d-inline">
            <input type="hidden" name="start_date" value="{{ now()->format('Y-m-d') }}">
            <input type="hidden" name="end_date" value="{{ now()->format('Y-m-d') }}">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-file-pdf"></i> Export PDF Hari Ini
            </button>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-primary">
            <div class="card-body">
                <h6 class="text-muted mb-2">Total Log CCTV Hari Ini</h6>
                <h2 class="text-primary mb-0">{{ $cctvLogs->count() }}</h2>
                <small class="text-muted">
                    <span class="text-danger">{{ $cctvLogs->where('status', 'Tidak Aman')->count() }}</span> Tidak Aman
                </small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-body">
                <h6 class="text-muted mb-2">Pengecekan Inventaris Hari Ini</h6>
                <h2 class="text-success mb-0">{{ $inventoryChecks->count() }}</h2>
                <small class="text-muted">{{ $inventoryChecks->sum(function($check) { return $check->details->count(); }) }} item dicek</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-warning">
            <div class="card-body">
                <h6 class="text-muted mb-2">Barang Rusak Ditemukan</h6>
                <h2 class="text-warning mb-0">
                    {{ $inventoryChecks->sum(function($check) { return $check->details->where('condition', 'Rusak')->count(); }) }}
                </h2>
                <small class="text-muted">item</small>
            </div>
        </div>
    </div>
</div>

<!-- CCTV Logs Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-camera-video"></i> Log Sheet CCTV Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($cctvLogs->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada log CCTV untuk hari ini.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="10%">Waktu</th>
                                    <th width="12%">Petugas</th>
                                    <th width="8%">Shift</th>
                                    <th width="12%">Lokasi</th>
                                    <th width="25%">Kejadian yang terpantau</th>
                                    <th width="18%">Tindakan</th>
                                    <th width="10%">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cctvLogs as $index => $log)
                                <tr class="{{ $log->status == 'Tidak Aman' ? 'table-danger' : '' }}">
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $log->log_time->format('H:i') }}</td>
                                    <td>{{ $log->user->full_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $log->shift }}</span>
                                    </td>
                                    <td>{{ $log->camera_location }}</td>
                                    <td>
                                        <small>{{ $log->incident_description }}</small>
                                    </td>
                                    <td>
                                        <small>{{ $log->action_taken }}</small>
                                    </td>
                                    <td class="text-center">
                                        @if($log->status == 'Aman')
                                            <span class="badge bg-success">
                                                <i class="bi bi-check-circle"></i> Aman
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="bi bi-exclamation-triangle"></i> Tidak Aman
                                            </span>
                                            @if($log->evidence_photo_url)
                                                <br>
                                                <a href="{{ Storage::url($log->evidence_photo_url) }}" target="_blank" class="btn btn-sm btn-info mt-1">
                                                    <i class="bi bi-image"></i> Foto
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Filter by Shift -->
                    <div class="mt-3">
                        <strong>Ringkasan per Shift:</strong>
                        <div class="row mt-2">
                            @foreach(['A', 'B', 'C', 'D'] as $shift)
                                @php
                                    $shiftLogs = $cctvLogs->where('shift', $shift);
                                    $unsafe = $shiftLogs->where('status', 'Tidak Aman')->count();
                                @endphp
                                <div class="col-md-3">
                                    <div class="card {{ $unsafe > 0 ? 'border-danger' : 'border-success' }}">
                                        <div class="card-body p-2">
                                            <strong>Shift {{ $shift }}</strong>: {{ $shiftLogs->count() }} log
                                            @if($unsafe > 0)
                                                <br><small class="text-danger">{{ $unsafe }} tidak aman</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Inventory Checks Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Pengecekan Inventaris Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($inventoryChecks->isEmpty())
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Belum ada pengecekan inventaris untuk hari ini.
                    </div>
                @else
                    @foreach($inventoryChecks as $check)
                        <div class="card mb-3 border-secondary">
                            <div class="card-header bg-light">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong><i class="bi bi-person"></i> {{ $check->user->full_name }}</strong>
                                        <span class="badge bg-secondary ms-2">Shift {{ $check->shift }}</span>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <i class="bi bi-geo-alt"></i> {{ $check->pos_location }}
                                        <span class="text-muted ms-2">{{ $check->check_date->format('H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="30%">Nama Barang</th>
                                                <th width="20%">Kategori</th>
                                                <th width="15%">Kondisi</th>
                                                <th width="30%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($check->details as $index => $detail)
                                            <tr class="{{ $detail->condition == 'Rusak' ? 'table-warning' : '' }}">
                                                <td class="text-center">{{ $index + 1 }}</td>
                                                <td>{{ $detail->inventoryItem->item_name }}</td>
                                                <td>
                                                    <span class="badge bg-info">{{ $detail->inventoryItem->category }}</span>
                                                </td>
                                                <td class="text-center">
                                                    @if($detail->condition == 'Baik')
                                                        <span class="badge bg-success">✓ Baik</span>
                                                    @else
                                                        <span class="badge bg-danger">✗ Rusak</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small>{{ $detail->remarks ?? '-' }}</small>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @php
                                    $damagedCount = $check->details->where('condition', 'Rusak')->count();
                                @endphp
                                
                                @if($damagedCount > 0)
                                    <div class="alert alert-warning mt-2 mb-0">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        <strong>{{ $damagedCount }}</strong> barang rusak ditemukan. 
                                        Tiket maintenance telah dibuat otomatis.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons -->
<div class="row mt-4">
    <div class="col-12 text-center">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-lg">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
        
        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#exportModal">
            <i class="bi bi-file-pdf"></i> Export Laporan Custom
        </button>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('reports.export') }}" method="GET">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="bi bi-file-pdf"></i> Export Laporan PDF
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="end_date" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Laporan akan mencakup semua data Log CCTV, Inventaris, dan Tiket dalam periode yang dipilih.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-download"></i> Download PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .card-header {
        font-weight: 600;
    }
    
    .table th {
        font-size: 0.9rem;
        font-weight: 600;
    }
    
    .table td small {
        font-size: 0.85rem;
    }
</style>
@endpush