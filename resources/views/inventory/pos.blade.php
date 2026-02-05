@extends('layouts.app')

@section('title', 'Cek Inventaris Isi Pos')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-clipboard-check"></i> Pengecekan Inventaris Isi Pos</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('inventory.pos.store') }}" method="POST" id="inventoryForm">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Pengecekan <span class="text-danger">*</span></label>
                            <input type="date" name="check_date" class="form-control @error('check_date') is-invalid @enderror" 
                                   value="{{ old('check_date', date('Y-m-d')) }}" required>
                            @error('check_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
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

                        <div class="col-md-4">
                            <label class="form-label">Lokasi Pos <span class="text-danger">*</span></label>
                            <input type="text" name="pos_location" class="form-control @error('pos_location') is-invalid @enderror" 
                                   value="{{ old('pos_location') }}" placeholder="Contoh: Pos Gate 1" required>
                            @error('pos_location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr>

                    <h6 class="mb-3">Checklist Barang Isi Pos</h6>
                    
                    @if($items->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> Belum ada data inventaris kategori "Isi Pos". 
                            Silakan hubungi Admin untuk menambahkan master data.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="35%">Nama Barang</th>
                                        <th width="30%">Deskripsi</th>
                                        <th width="15%">Kondisi <span class="text-danger">*</span></th>
                                        <th width="15%">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $index => $item)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->item_name }}</strong>
                                            <input type="hidden" name="items[{{ $index }}][item_id]" value="{{ $item->id }}">
                                        </td>
                                        <td><small class="text-muted">{{ $item->description ?? '-' }}</small></td>
                                        <td>
                                            <select name="items[{{ $index }}][condition]" class="form-select form-select-sm" required>
                                                <option value="">-- Pilih --</option>
                                                <option value="Baik" {{ old("items.$index.condition") == 'Baik' ? 'selected' : '' }}>✅ Baik</option>
                                                <option value="Rusak" {{ old("items.$index.condition") == 'Rusak' ? 'selected' : '' }}>❌ Rusak</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" name="items[{{ $index }}][remarks]" 
                                                   class="form-control form-control-sm" 
                                                   placeholder="Opsional"
                                                   value="{{ old("items.$index.remarks") }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="bi bi-info-circle"></i> <strong>Catatan:</strong> 
                            Jika ada barang dengan kondisi <strong>Rusak</strong>, sistem akan otomatis membuat tiket perbaikan untuk bagian Maintenance.
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Simpan Pengecekan
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection