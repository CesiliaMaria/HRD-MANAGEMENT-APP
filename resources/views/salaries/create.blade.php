@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i> Tambah Data Gaji</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('salaries.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Pilih Karyawan</label>
                        <select class="form-select" id="user_id" name="user_id" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="basic_salary" class="form-label">Gaji Pokok</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="basic_salary" name="basic_salary" 
                                       required min="0" step="1000" placeholder="0">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="allowance" class="form-label">Tunjangan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="allowance" name="allowance" 
                                       value="0" min="0" step="1000" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="overtime_pay" class="form-label">Bayaran Lembur</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="overtime_pay" name="overtime_pay" 
                                       value="0" min="0" step="1000" placeholder="0">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="tax" class="form-label">Pajak</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" id="tax" name="tax" 
                                       value="0" min="0" step="1000" placeholder="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">Ringkasan Gaji</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small>Gaji Pokok:</small>
                                        <div id="basic-salary-preview">Rp 0</div>
                                    </div>
                                    <div class="col-6">
                                        <small>Total Gaji:</small>
                                        <div id="total-salary-preview" class="fw-bold text-success">Rp 0</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Simpan Data Gaji
                        </button>
                        <a href="{{ route('salaries.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function calculateTotal() {
        const basicSalary = parseFloat(document.getElementById('basic_salary').value) || 0;
        const allowance = parseFloat(document.getElementById('allowance').value) || 0;
        const overtimePay = parseFloat(document.getElementById('overtime_pay').value) || 0;
        const tax = parseFloat(document.getElementById('tax').value) || 0;
        
        const totalSalary = basicSalary + allowance + overtimePay - tax;
        
        document.getElementById('basic-salary-preview').textContent = 
            'Rp ' + basicSalary.toLocaleString('id-ID');
        document.getElementById('total-salary-preview').textContent = 
            'Rp ' + totalSalary.toLocaleString('id-ID');
    }
    
    // Add event listeners to all salary inputs
    document.getElementById('basic_salary').addEventListener('input', calculateTotal);
    document.getElementById('allowance').addEventListener('input', calculateTotal);
    document.getElementById('overtime_pay').addEventListener('input', calculateTotal);
    document.getElementById('tax').addEventListener('input', calculateTotal);
    
    // Initial calculation
    calculateTotal();
</script>
@endpush