@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2>Detail Pengajuan Lembur</h2>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table">
                <tr>
                    <th width="200">Karyawan</th>
                    <td>{{ $overtime->user->name }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $overtime->user->email }}</td>
                </tr>
                <tr>
                    <th>Tanggal Lembur</th>
                    <td>{{ $overtime->date->format('d F Y') }}</td>
                </tr>
                <tr>
                    <th>Jam Mulai</th>
                    <td>{{ $overtime->start_time }}</td>
                </tr>
                <tr>
                    <th>Jam Selesai</th>
                    <td>{{ $overtime->end_time }}</td>
                </tr>
                <tr>
                    <th>Durasi</th>
                    <td><strong>{{ $overtime->duration_hours }} jam</strong></td>
                </tr>
                <tr>
                    <th>Kegiatan</th>
                    <td>{{ $overtime->activity }}</td>
                </tr>
                @if($overtime->location)
                <tr>
                    <th>Lokasi</th>
                    <td>{{ $overtime->location }}</td>
                </tr>
                @endif
                <tr>
                    <th>Status</th>
                    <td>
                        @if($overtime->status == 'pending')
                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                        @elseif($overtime->status == 'approved')
                            <span class="badge bg-success">Disetujui</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </td>
                </tr>
                @if($overtime->approved_by)
                <tr>
                    <th>Diproses oleh</th>
                    <td>{{ $overtime->approver->name }}</td>
                </tr>
                <tr>
                    <th>Tanggal Diproses</th>
                    <td>{{ $overtime->approved_at->format('d F Y H:i') }}</td>
                </tr>
                @endif
                @if($overtime->admin_note)
                <tr>
                    <th>Catatan Admin</th>
                    <td>{{ $overtime->admin_note }}</td>
                </tr>
                @endif
                <tr>
                    <th>Diajukan pada</th>
                    <td>{{ $overtime->created_at->format('d F Y H:i') }}</td>
                </tr>
            </table>

            <div class="mt-3">
                <a href="{{ route('overtimes.index') }}" class="btn btn-secondary">Kembali</a>
                
                @if(auth()->user()->role_id == 1 && $overtime->status == 'pending')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#approveModal">
                        Approve
                    </button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                        Reject
                    </button>
                @endif

                @if($overtime->user_id == auth()->id() && $overtime->status == 'pending')
                    <form action="{{ route('overtimes.destroy', $overtime) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin hapus pengajuan ini?')">
                            Hapus Pengajuan
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Approve -->
@if(auth()->user()->role_id == 1 && $overtime->status == 'pending')
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('overtimes.approve', $overtime) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Approve Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Approve pengajuan lembur ini?</p>
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
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('overtimes.reject', $overtime) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Lembur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Reject pengajuan lembur ini?</p>
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
@endsection
