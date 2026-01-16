<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salary;
use App\Models\User;

class SalaryController extends Controller
{
    /**
     * Menampilkan daftar gaji
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->isAdmin() || $user->isManager()) {
            $salaries = Salary::with('user')->paginate(10);
        } else {
            $salaries = Salary::where('user_id', $user->id)->paginate(10);
        }
        
        return view('salaries.index', compact('salaries'));
    }
    
    /**
     * Menampilkan form pembuatan gaji (admin only)
     */
    public function create()
    {
        $employees = User::where('role_id', 3)->get();
        return view('salaries.create', compact('employees'));
    }
    
    /**
     * Menyimpan data gaji baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);
        
        // Hitung total gaji
        $totalSalary = $request->basic_salary + $request->allowance + $request->overtime_pay - $request->tax;
        
        $salary = new Salary();
        $salary->user_id = $request->user_id;
        $salary->basic_salary = $request->basic_salary;
        $salary->allowance = $request->allowance ?? 0;
        $salary->overtime_pay = $request->overtime_pay ?? 0;
        $salary->tax = $request->tax ?? 0;
        $salary->total_salary = $totalSalary;
        $salary->save();
        
        return redirect()->route('salaries.index')->with('success', 'Data gaji berhasil ditambahkan.');
    }
    
    /**
     * Proses pembayaran gaji
     */
    public function processPayment($id)
    {
        $salary = Salary::findOrFail($id);
        
        // Simulasi payment gateway
        // Di production, ini akan terintegrasi dengan payment gateway seperti Midtrans, Xendit, dll.
        $transactionId = 'PAY-' . time() . '-' . $salary->id;
        
        $salary->payment_status = 'paid';
        $salary->payment_method = 'bank_transfer';
        $salary->transaction_id = $transactionId;
        $salary->payment_date = now();
        $salary->save();
        
        return redirect()->back()->with('success', 'Pembayaran gaji berhasil diproses.');
    }
}