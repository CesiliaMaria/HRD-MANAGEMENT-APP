@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="page-title">Manajemen Payroll</h2>
            @if(auth()->user()->isAdmin() || auth()->user()->isManager())
            <a href="{{ route('salaries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Gaji
            </a>
            @endif
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
                                <th>Gaji Pokok</th>
                                <th>Tunjangan</th>
                                <th>Lembur</th>
                                <th>Pajak</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salaries as $salary)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                @if(auth()->user()->isAdmin() || auth()->user()->isManager())
                                <td>{{ $salary->user->name }}</td>
                                @endif
                                <td>Rp {{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($salary->allowance, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($salary->overtime_pay, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($salary->tax, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</strong></td>
                                <td>
                                    @if($salary->payment_status == 'paid')
                                        <span class="badge bg-success">Dibayar</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $salary->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if(auth()->user()->isAdmin() && $salary->payment_status == 'pending')
                                    <form action="{{ route('salaries.process-payment', $salary->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" 
                                                onclick="return confirm('Proses pembayaran gaji?')">
                                            <i class="fas fa-credit-card me-1"></i> Bayar
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $salaries->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection