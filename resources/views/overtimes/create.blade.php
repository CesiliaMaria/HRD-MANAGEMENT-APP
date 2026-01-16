@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Ajukan Lembur</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('overtimes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Karyawan</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->name }}" disabled>
                    <small class="text-muted">Data otomatis dari akun login Anda</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Lembur <span class="text-danger">*</span></label>
                    <input type="date" name="date" class="form-control @error('date') is-invalid @enderror" 
                           value="{{ old('date') }}" required>
                    @error('date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                            <input type="time" name="start_time" class="form-control @error('start_time') is-invalid @enderror" 
                                   value="{{ old('start_time') }}" required>
                            @error('start_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                            <input type="time" name="end_time" class="form-control @error('end_time') is-invalid @enderror" 
                                   value="{{ old('end_time') }}" required>
                            @error('end_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Sistem akan otomatis menghitung durasi</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Uraian Kegiatan Lembur <span class="text-danger">*</span></label>
                    <textarea name="activity" class="form-control @error('activity') is-invalid @enderror" 
                              rows="4" required>{{ old('activity') }}</textarea>
                    @error('activity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Jelaskan secara detail kegiatan yang dilakukan saat lembur</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi / Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" 
                           value="{{ old('location') }}" placeholder="Contoh: Kantor Pusat, Work From Home, dll.">
                    @error('location')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('overtimes.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Ajukan Lembur</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
