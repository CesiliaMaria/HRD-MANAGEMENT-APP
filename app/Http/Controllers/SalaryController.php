<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\User;
use App\Models\OvertimeRequest;
use Barryvdh\DomPDF\Facade\Pdf;

class SalaryController extends Controller
{
    /**
     * Menampilkan daftar gaji
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role_id == 1 || $user->role_id == 2) { // admin atau manager
            $salaries = Salary::with('user')->orderBy('period_year', 'desc')->orderBy('period_month', 'desc')->paginate(10);
        } else {
            $salaries = Salary::where('user_id', $user->id)->orderBy('period_year', 'desc')->orderBy('period_month', 'desc')->paginate(10);
        }
        
        return view('salaries.index', compact('salaries'));
    }
    
    /**
     * Menampilkan form pembuatan gaji (admin only)
     */
    public function create()
    {
        // Only admin can create salary
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        $employees = User::where('role_id', 3)->get();
        $overtimeRate = env('OVERTIME_RATE_PER_HOUR', 20000);
        
        return view('salaries.create', compact('employees', 'overtimeRate'));
    }
    
    /**
     * Menyimpan data gaji baru dengan perhitungan lembur otomatis
     */
    public function store(Request $request)
    {
        // Only admin can create salary
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'period_month' => 'required|integer|min:1|max:12',
            'period_year' => 'required|integer|min:2020|max:2100',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        // Cek apakah sudah ada salary untuk periode ini
        $existing = Salary::where('user_id', $request->user_id)
            ->where('period_month', $request->period_month)
            ->where('period_year', $request->period_year)
            ->first();

        if ($existing) {
            return back()->withErrors(['period' => 'Gaji untuk periode ini sudah ada.'])->withInput();
        }

        // Hitung total jam lembur yang approved untuk periode ini
        $approvedOvertimes = OvertimeRequest::where('user_id', $request->user_id)
            ->where('status', 'approved')
            ->inPeriod($request->period_month, $request->period_year)
            ->get();

        $totalOvertimeHours = $approvedOvertimes->sum('duration_hours');
        $overtimeRate = env('OVERTIME_RATE_PER_HOUR', 20000);

        // Buat salary record
        $salary = new Salary();
        $salary->user_id = $request->user_id;
        $salary->period_month = $request->period_month;
        $salary->period_year = $request->period_year;
        $salary->basic_salary = $request->basic_salary;
        $salary->allowance = $request->allowance ?? 0;
        $salary->overtime_hours = $totalOvertimeHours;
        $salary->overtime_rate = $overtimeRate;
        $salary->tax = $request->tax ?? 0;
        $salary->payment_status = 'pending';
        
        // Hitung total
        $salary->calculateTotal();
        $salary->save();
        
        return redirect()->route('salaries.index')
            ->with('success', 'Data gaji berhasil ditambahkan. Total jam lembur: ' . $totalOvertimeHours . ' jam.');
    }
    
    /**
     * Proses pembayaran gaji
     */
    public function processPayment($id)
    {
        // Only admin can process payment
        if (auth()->user()->role_id !== 1) {
            abort(403);
        }

        $salary = Salary::findOrFail($id);
        
        // Simulasi payment gateway
        $transactionId = 'TRX-' . date('Y-m-d') . '-' . str_pad($salary->id, 6, '0', STR_PAD_LEFT);
        
        $salary->payment_status = 'paid';
        $salary->payment_method = 'bank_transfer';
        $salary->transaction_id = $transactionId;
        $salary->payment_date = now();
        $salary->save();
        
        return redirect()->back()->with('success', 'Pembayaran gaji berhasil diproses.');
    }

    /**
     * Download slip gaji sebagai PDF
     */
    public function downloadSlip(Salary $salary)
    {
        $user = auth()->user();

        // Authorization: admin bisa download semua, user biasa hanya miliknya
        if ($user->role_id !== 1 && $salary->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses untuk download slip gaji ini.');
        }

        // Load relasi
        $salary->load('user');

        // Generate PDF
        $pdf = Pdf::loadView('pdf.salary-slip', compact('salary'));
        
        $filename = 'slip-gaji-' . str_replace(' ', '-', $salary->user->name) . '-' . $salary->period_month . '-' . $salary->period_year . '.pdf';
        
        return $pdf->download($filename);
    }
}
