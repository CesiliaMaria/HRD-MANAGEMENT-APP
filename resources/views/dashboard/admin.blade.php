@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <h2 class="page-title">Dashboard Admin</h2>
        <p class="text-muted">Selamat datang, {{ Auth::user()->name }}!</p>
    </div>
</div>

<div class="row">
    <!-- Statistik Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            Total Karyawan
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">{{ $totalEmployees }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            Hadir Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">{{ $presentToday }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-check fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            Terlambat Hari Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">{{ $lateToday }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            Payroll Bulan Ini
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">Rp {{ number_format(50000000, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-money-bill-wave fa-2x text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Status Absensi Admin Hari Ini -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Status Absensi Saya Hari Ini</h5>
            </div>
            <div class="card-body">
                @php
                    $todayAttendance = \App\Models\Attendance::where('user_id', auth()->id())
                        ->whereDate('date', \Carbon\Carbon::today())
                        ->first();
                @endphp

                @if($todayAttendance)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Sudah Check-in:</strong> 
                        {{ $todayAttendance->check_in->format('H:i:s') }}
                    </div>
                    
                    @if($todayAttendance->check_out)
                        <div class="alert alert-info">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <strong>Sudah Check-out:</strong> 
                            {{ $todayAttendance->check_out->format('H:i:s') }}
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

    <!-- Quick Actions -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-primary w-100">
                            <i class="fas fa-fingerprint me-2"></i> Check-in
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('salaries.create') }}" class="btn btn-success w-100">
                            <i class="fas fa-plus me-2"></i> Tambah Gaji
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('users.create') }}" class="btn btn-info w-100">
                            <i class="fas fa-user-plus me-2"></i> Tambah User
                        </a>
                    </div>
                    <div class="col-md-6 mb-3">
                        <a href="{{ route('attendances.checkout-form') }}" class="btn btn-warning w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Check-out
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Activities -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @php
                        $recentAttendances = \App\Models\Attendance::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @foreach($recentAttendances as $activity)
                    <div class="list-group-item d-flex align-items-center">
                        <i class="fas fa-user-check text-success me-3"></i>
                        <div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                            <p class="mb-0">{{ $activity->user->name }} melakukan check-in</p>
                        </div>
                    </div>
                    @endforeach
                    
                    @if($recentAttendances->isEmpty())
                    <div class="list-group-item text-center text-muted">
                        Tidak ada aktivitas terbaru
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Reports -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Laporan Cepat</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="{{ route('attendances.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-calendar-alt text-primary me-3"></i>
                        <div>
                            <strong>Laporan Absensi</strong>
                            <p class="mb-0 text-muted">Lihat semua data kehadiran</p>
                        </div>
                    </a>
                    <a href="{{ route('salaries.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-money-bill-wave text-success me-3"></i>
                        <div>
                            <strong>Laporan Gaji</strong>
                            <p class="mb-0 text-muted">Kelola data penggajian</p>
                        </div>
                    </a>
                    <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
                        <i class="fas fa-users text-info me-3"></i>
                        <div>
                            <strong>Manajemen User</strong>
                            <p class="mb-0 text-muted">Kelola data karyawan</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Akses Cepat -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Akses Cepat</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.index') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-history me-2"></i> Absensi
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('salaries.index') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-money-bill-wave me-2"></i> Payroll
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-users me-2"></i> Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('attendances.create') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-fingerprint me-2"></i> Check-in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection