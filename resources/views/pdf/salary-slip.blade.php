<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $salary->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .document-title {
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 0;
        }
        .info-table td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .salary-table th,
        .salary-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .salary-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .salary-table .amount {
            text-align: right;
        }
        .total-row {
            background-color: #e8f4f8;
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .signature {
            margin-top: 60px;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">HRD MANAGEMENT SYSTEM</div>
        <div>Jl. Contoh No. 123, Jakarta 12345</div>
        <div>Email: hr@company.com | Tel: (021) 1234-5678</div>
        <div class="document-title">SLIP GAJI KARYAWAN</div>
    </div>

    <div class="info-section">
        <table class="info-table">
            <tr>
                <td>Nama Karyawan</td>
                <td>: {{ $salary->user->name }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: {{ $salary->user->email }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: {{ $salary->user->role->name == 'admin' ? 'Administrator' : ($salary->user->role->name == 'manager' ? 'Manager' : 'Karyawan') }}</td>
            </tr>
            <tr>
                <td>Periode</td>
                <td>: <strong>{{ $salary->period_label }}</strong></td>
            </tr>
            <tr>
                <td>Status Pembayaran</td>
                <td>: <strong>{{ $salary->payment_status == 'paid' ? 'LUNAS' : 'PENDING' }}</strong></td>
            </tr>
            @if($salary->payment_date)
            <tr>
                <td>Tanggal Pembayaran</td>
                <td>: {{ $salary->payment_date->format('d F Y') }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="salary-table">
        <thead>
            <tr>
                <th>Komponen</th>
                <th>Keterangan</th>
                <th class="amount">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td>Gaji dasar bulanan</td>
                <td class="amount">{{ number_format($salary->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Tunjangan</td>
                <td>Tunjangan tetap</td>
                <td class="amount">{{ number_format($salary->allowance, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td>{{ $salary->overtime_hours }} jam @ Rp {{ number_format($salary->overtime_rate, 0, ',', '.') }}/jam</td>
                <td class="amount">{{ number_format($salary->overtime_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td colspan="2"><strong>Sub Total</strong></td>
                <td class="amount"><strong>{{ number_format($salary->basic_salary + $salary->allowance + $salary->overtime_pay, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Pajak / Potongan</td>
                <td>Potongan pajak</td>
                <td class="amount">({{ number_format($salary->tax, 0, ',', '.') }})</td>
            </tr>
            <tr class="total-row">
                <td colspan="2">TOTAL GAJI BERSIH</td>
                <td class="amount">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @if($salary->notes)
    <div style="margin-top: 20px;">
        <strong>Catatan:</strong> {{ $salary->notes }}
    </div>
    @endif

    <div class="footer">
        <div>Dicetak pada: {{ now()->format('d F Y H:i') }}</div>
        <div class="signature">
            <div>Mengetahui,</div>
            <div style="margin-top: 60px; border-top: 1px solid #000; width: 200px; display: inline-block;">
                HRD Manager
            </div>
        </div>
    </div>

    <div style="margin-top: 30px; font-size: 10px; color: #666; text-align: center; border-top: 1px solid #ddd; padding-top: 10px;">
        Dokumen ini digenerate otomatis oleh sistem HRD Management. 
        @if($salary->transaction_id)
            ID Transaksi: {{ $salary->transaction_id }}
        @endif
    </div>
</body>
</html>
