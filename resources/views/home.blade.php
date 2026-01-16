@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="page-title">
            <i class="fas fa-home me-2"></i>
            Dashboard - Selamat Datang, {{ auth()->user()->name }}!
        </h2>
        <p class="text-muted">Role: <strong>{{ auth()->user()->role->name }}</strong></p>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    @php
        $user = auth()->user();
        $isAdmin = $user->isAdmin() || $user->isManager();
        
        if ($isAdmin) {
            // Admin Stats
            $totalEmployees = \App\Models\User::where('role_id', 3)->count();
            $pendingOvertimes = \App\Models\OvertimeRequest::where('status', 'pending')->count();
            $pendingSalaries = \App\Models\Salary::where('payment_status', 'pending')->count();
            $todayAttendances = \App\Models\Attendance::whereDate('date', today())->count();
        } else {
            // Employee Stats
            $myOvertimes = \App\Models\OvertimeRequest::where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count();
            $myApprovedOvertimes = \App\Models\OvertimeRequest::where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->where('status', 'approved')
                ->sum('duration_hours');
            $myPendingOvertimes = \App\Models\OvertimeRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->count();
            $myAttendances = \App\Models\Attendance::where('user_id', $user->id)
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count();
        }
    @endphp
    
    @if($isAdmin)
        <!-- Admin Dashboard Cards -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Total Karyawan</h6>
                            <h2 class="mb-0">{{ $totalEmployees }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Lembur Pending</h6>
                            <h2 class="mb-0">{{ $pendingOvertimes }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <a href="{{ route('overtimes.index', ['status' => 'pending']) }}" class="btn btn-sm btn-light mt-2">
                        <i class="fas fa-check me-1"></i> Review
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Gaji Pending</h6>
                            <h2 class="mb-0">{{ $pendingSalaries }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Absen Hari Ini</h6>
                            <h2 class="mb-0">{{ $todayAttendances }}</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Employee Dashboard Cards -->
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Lembur Bulan Ini</h6>
                            <h2 class="mb-0">{{ $myOvertimes }}</h2>
                            <small>pengajuan</small>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #43e97b, #38f9d7);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Jam Lembur Approved</h6>
                            <h2 class="mb-0">{{ number_format($myApprovedOvertimes, 1) }}</h2>
                            <small>jam</small>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #f093fb, #f5576c);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Lembur Pending</h6>
                            <h2 class="mb-0">{{ $myPendingOvertimes }}</h2>
                            <small>menunggu approval</small>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card stat-card text-white" style="background: linear-gradient(45deg, #4facfe, #00f2fe);">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50">Absensi Bulan Ini</h6>
                            <h2 class="mb-0">{{ $myAttendances }}</h2>
                            <small>hari</small>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i> Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($isAdmin)
                        <!-- Admin Quick Actions -->
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('overtimes.index', ['status' => 'pending']) }}" class="btn btn-outline-warning w-100 btn-lg">
                                <i class="fas fa-tasks d-block fs-2 mb-2"></i>
                                Review Lembur
                                @if($pendingOvertimes > 0)
                                    <span class="badge bg-danger ms-2">{{ $pendingOvertimes }}</span>
                                @endif
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salaries.create') }}" class="btn btn-outline-success w-100 btn-lg">
                                <i class="fas fa-plus-circle d-block fs-2 mb-2"></i>
                                Buat Payroll
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('attendances.index') }}" class="btn btn-outline-info w-100 btn-lg">
                                <i class="fas fa-calendar-alt d-block fs-2 mb-2"></i>
                                Lihat Absensi
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('salaries.index') }}" class="btn btn-outline-primary w-100 btn-lg">
                                <i class="fas fa-money-check-alt d-block fs-2 mb-2"></i>
                                Kelola Gaji
                            </a>
                        </div>
                    @else
                        <!-- Employee Quick Actions -->
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('overtimes.create') }}" class="btn btn-outline-primary w-100 btn-lg">
                                <i class="fas fa-clock d-block fs-2 mb-2"></i>
                                Ajukan Lembur
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('overtimes.index') }}" class="btn btn-outline-info w-100 btn-lg">
                                <i class="fas fa-list d-block fs-2 mb-2"></i>
                                Riwayat Lembur
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="{{ route('salaries.index') }}" class="btn btn-outline-success w-100 btn-lg">
                                <i class="fas fa-file-invoice-dollar d-block fs-2 mb-2"></i>
                                Slip Gaji Saya
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@if($isAdmin)
    <!-- Recent Overtime Requests (Admin) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i> Pengajuan Lembur Terbaru</h5>
                </div>
                <div class="card-body">
                    @php
                        $recentOvertimes = \App\Models\OvertimeRequest::with('user')
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($recentOvertimes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Karyawan</th>
                                        <th>Tanggal</th>
                                        <th>Durasi</th>
                                        <th>Kegiatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOvertimes as $overtime)
                                    <tr>
                                        <td>{{ $overtime->user->name }}</td>
                                        <td>{{ $overtime->date->format('d M Y') }}</td>
                                        <td>{{ $overtime->duration_hours }} jam</td>
                                        <td>{{ Str::limit($overtime->activity, 50) }}</td>
                                        <td>
                                            @if($overtime->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($overtime->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('overtimes.show', $overtime->id) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> Lihat
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('overtimes.index') }}" class="btn btn-outline-primary">
                                Lihat Semua Lembur <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <p class="text-muted text-center">Belum ada pengajuan lembur.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@else
    <!-- My Recent Overtimes (Employee) -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Riwayat Lembur Saya</h5>
                </div>
                <div class="card-body">
                    @php
                        $myRecentOvertimes = \App\Models\OvertimeRequest::where('user_id', auth()->id())
                            ->orderBy('date', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($myRecentOvertimes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Waktu</th>
                                        <th>Durasi</th>
                                        <th>Kegiatan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($myRecentOvertimes as $overtime)
                                    <tr>
                                        <td>{{ $overtime->date->format('d M Y') }}</td>
                                        <td>{{ $overtime->start_time }} - {{ $overtime->end_time }}</td>
                                        <td>{{ $overtime->duration_hours }} jam</td>
                                        <td>{{ Str::limit($overtime->activity, 40) }}</td>
                                        <td>
                                            @if($overtime->status == 'pending')
                                                <span class="badge bg-warning">Pending</span>
                                            @elseif($overtime->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @else
                                                <span class="badge bg-danger">Rejected</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('overtimes.show', $overtime->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('overtimes.index') }}" class="btn btn-outline-primary">
                                Lihat Semua Lembur Saya <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clock fs-1 text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat lembur.</p>
                            <a href="{{ route('overtimes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i> Ajukan Lembur Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif
@endsection