@extends('layouts.app')

@section('title', 'Input Log CCTV')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-camera-video"></i> Input Log Sheet CCTV</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('cctv-logs.store') }}" method="POST" enctype="multipart/form-data" id="cctvForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Shift <span class="text-danger">*</span></label>
                            <select name="shift" class="form-select @error('shift') is-invalid @enderror" required>
                                <option value="">-- Pilih Shift --</option>
                                <option value="A" {{ old('shift') == 'A' ? 'selected' : '' }}>Shift A</option>
                                <option value="B" {{ old('shift') == 'B' ? 'selected' : '' }}>Shift B</option>
                                <option value="C" {{ old('shift') == 'C' ? 'selected' : '' }}>Shift C</option>
                                <option value="D" {{ old('shift') == 'D' ? 'selected' : '' }}>Shift D</option>
                            </select>
                            @error('shift')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Waktu Log <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="log_time" class="form-control @error('log_time') is-invalid @enderror" 
                                   value="{{ old('log_time', now()->format('Y-m-d\TH:i')) }}" required>
                            @error('log_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi Kamera (Zona) <span class="text-danger">*</span></label>
                        <select name="camera_location" class="form-select @error('camera_location') is-invalid @enderror" required>
                            <option value="">-- Pilih Zona --</option>
                            <option value="Zona 1" {{ old('camera_location') == 'Zona 1' ? 'selected' : '' }}>Zona 1</option>
                            <option value="Zona 2" {{ old('camera_location') == 'Zona 2' ? 'selected' : '' }}>Zona 2</option>
                            <option value="Zona 3" {{ old('camera_location') == 'Zona 3' ? 'selected' : '' }}>Zona 3</option>
                            <option value="Zona 4" {{ old('camera_location') == 'Zona 4' ? 'selected' : '' }}>Zona 4</option>
                        </select>
                        @error('camera_location')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Kejadian <span class="text-danger">*</span></label>
                        <textarea name="incident_description" rows="3" class="form-control @error('incident_description') is-invalid @enderror" 
                                  required>{{ old('incident_description') }}</textarea>
                        @error('incident_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tindakan yang Diambil <span class="text-danger">*</span></label>
                        <textarea name="action_taken" rows="3" class="form-control @error('action_taken') is-invalid @enderror" 
                                  required>{{ old('action_taken') }}</textarea>
                        @error('action_taken')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Kondisi <span class="text-danger">*</span></label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusAman" 
                                       value="Aman" {{ old('status') == 'Aman' ? 'checked' : '' }} required>
                                <label class="form-check-label text-success fw-bold" for="statusAman">
                                    <i class="bi bi-check-circle"></i> Aman
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="statusTidakAman" 
                                       value="Tidak Aman" {{ old('status') == 'Tidak Aman' ? 'checked' : '' }} required>
                                <label class="form-check-label text-danger fw-bold" for="statusTidakAman">
                                    <i class="bi bi-exclamation-triangle"></i> Tidak Aman
                                </label>
                            </div>
                        </div>
                        @error('status')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- FOTO BUKTI (Muncul jika Tidak Aman) -->
                    <div class="mb-3" id="photoField" style="display: none;">
                        <label class="form-label">Upload Foto Bukti <span class="text-danger" id="photoRequired">*</span></label>
                        <input type="file" name="evidence_photo" class="form-control @error('evidence_photo') is-invalid @enderror" 
                               accept="image/jpeg,image/png,image/jpg" id="photoInput">
                        <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                        @error('evidence_photo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('cctv-logs.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan Log
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusRadios = document.querySelectorAll('input[name="status"]');
    const photoField = document.getElementById('photoField');
    const photoInput = document.getElementById('photoInput');
    
    function togglePhotoField() {
        const selectedStatus = document.querySelector('input[name="status"]:checked');
        
        if (selectedStatus && selectedStatus.value === 'Tidak Aman') {
            photoField.style.display = 'block';
            photoInput.setAttribute('required', 'required');
        } else {
            photoField.style.display = 'none';
            photoInput.removeAttribute('required');
            photoInput.value = ''; // Reset file input
        }
    }
    
    // Event listener untuk setiap radio button
    statusRadios.forEach(radio => {
        radio.addEventListener('change', togglePhotoField);
    });
    
    // Check on page load (untuk old values)
    togglePhotoField();
});
</script>
@endpush