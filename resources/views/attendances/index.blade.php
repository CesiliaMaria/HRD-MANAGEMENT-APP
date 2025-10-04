@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title">Riwayat Absensi</h2>
            <a href="{{ route('attendances.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Check-in Baru
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <th>Karyawan</th>
                                @endif
                                <th>Tanggal</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $attendance)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <td>{{ $attendance->user->name }}</td>
                                @endif
                                <td>{{ $attendance->date->format('d/m/Y') }}</td>
                                <td>
                                    @if($attendance->check_in)
                                        <span class="badge bg-success">
                                            {{ $attendance->check_in->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_out)
                                        <span class="badge bg-info">
                                            {{ $attendance->check_out->format('H:i:s') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning">Belum</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted" title="{{ $attendance->location_address }}">
                                        {{ Str::limit($attendance->location_address, 30) }}
                                    </small>
                                </td>
                                <td>
                                    @if($attendance->status == 'present')
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    @elseif($attendance->status == 'late')
                                        <span class="badge bg-warning">Terlambat</span>
                                    @else
                                        <span class="badge bg-danger">Absen</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_in && $attendance->check_out)
                                        @php
                                            $start = \Carbon\Carbon::parse($attendance->check_in);
                                            $end = \Carbon\Carbon::parse($attendance->check_out);
                                            $diff = $start->diff($end);
                                        @endphp
                                        {{ $diff->h }}j {{ $diff->i }}m
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection