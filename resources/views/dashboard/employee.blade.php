@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="page-title">Dashboard Karyawan</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="row">
    <!-- Today's Attendance Status -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Status Absensi Hari Ini</h5>
            </div>
            <div class="card-body">
                @if($todayAttendance)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Sudah Check-in:</strong> 
                        {{ \Carbon\Carbon::parse($todayAttendance->check_in)->format('H:i:s') }}
                    </div>
                    
                    @if($todayAttendance->check_out)
                        <div class="alert alert-info">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <strong>Sudah Check-out:</strong> 
                            {{ \Carbon\Carbon::parse($todayAttendance->check_out)->format('H:i:s') }}
                        </div>
                        <div class="alert alert-success">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Status:</strong> 
                            @if($todayAttendance->status == 'present')
                                <span class="badge bg-success">Tepat Waktu</span>
                            @else
                                <span class="badge bg-warning">Terlambat</span>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Belum Check-out</strong>
                        </div>
                        <a href="{{ route('attendances.checkout-form') }}" class="btn btn-warning btn-lg w-100 mb-2">
                            <i class="fas fa-sign-out-alt me-2"></i> Check-out Sekarang
                        </a>
                    @endif
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Belum Check-in</strong>
                    </div>
                    <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-lg w-100">
                        <i class="fas fa-fingerprint me-2"></i> Check-in Sekarang
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Monthly Summary -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Ringkasan Bulan Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $presentCount = $monthlyAttendances->where('status', 'present')->count();
                    $lateCount = $monthlyAttendances->where('status', 'late')->count();
                    $totalDays = $monthlyAttendances->count();
                @endphp
                
                <div class="row text-center">
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h4 class="text-primary">{{ $presentCount }}</h4>
                            <small>Hadir</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h4 class="text-warning">{{ $lateCount }}</h4>
                            <small>Terlambat</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="border rounded p-3">
                            <h4 class="text-success">{{ $totalDays }}</h4>
                            <small>Total</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Actions -->
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Akses Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-history me-2"></i> Riwayat
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('salaries.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave me-2"></i> Gaji
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-fingerprint me-2"></i> Check-in
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.checkout-form') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Check-out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection