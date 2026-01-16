@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-6">
            <h2>Manajemen Lembur</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('overtimes.create') }}" class="btn btn-primary">
                + Ajukan Lembur
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter untuk Admin -->
    @if(auth()->user()->role_id == 1)
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('overtimes.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Filter Status</label>
                    <select name="status" class="form-control">
                        <option value="">Semua</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-secondary d-block">Filter</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            @if(auth()->user()->role_id == 1)
                                <th>Karyawan</th>
                            @endif
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Durasi</th>
                            <th>Kegiatan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($overtimes as $index => $overtime)
                        <tr>
                            <td>{{ $overtimes->firstItem() + $index }}</td>
                            @if(auth()->user()->role_id == 1)
                                <td>{{ $overtime->user->name }}</td>
                            @endif
                            <td>{{ $overtime->date->format('d/m/Y') }}</td>
                            <td>{{ $overtime->start_time }} - {{ $overtime->end_time }}</td>
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
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('overtimes.show', $overtime) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye me-1"></i>Detail
                                    </a>
                                    
                                    @if(auth()->user()->role_id == 1 && $overtime->status == 'pending')
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $overtime->id }}">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $overtime->id }}">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    @endif

                                    @if($overtime->user_id == auth()->id() && $overtime->status == 'pending')
                                        <form action="{{ route('overtimes.destroy', $overtime) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                                <i class="fas fa-trash me-1"></i>Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <!-- Modal Approve -->
                        @if(auth()->user()->role_id == 1)
                        <div class="modal fade" id="approveModal{{ $overtime->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('overtimes.approve', $overtime) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Approve Lembur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Approve pengajuan lembur dari <strong>{{ $overtime->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Catatan Admin (Opsional)</label>
                                                <textarea name="admin_note" class="form-control" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success">Approve</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Reject -->
                        <div class="modal fade" id="rejectModal{{ $overtime->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('overtimes.reject', $overtime) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Lembur</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Reject pengajuan lembur dari <strong>{{ $overtime->user->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                                <textarea name="admin_note" class="form-control" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Reject</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endif

                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data pengajuan lembur</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $overtimes->links() }}
        </div>
    </div>
</div>
@endsection
