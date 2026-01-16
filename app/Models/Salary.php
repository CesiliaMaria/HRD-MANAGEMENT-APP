<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'period_month', 
        'period_year',
        'basic_salary', 
        'allowance', 
        'overtime_hours',
        'overtime_rate',
        'overtime_pay', // ini adalah overtime_amount
        'tax', 
        'total_salary', 
        'payment_status', 
        'payment_method',
        'transaction_id', 
        'payment_date', 
        'notes'
    ];

    protected $casts = [
        'period_month' => 'integer',
        'period_year' => 'integer',
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_rate' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_salary' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Relasi many-to-one: salary dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Hitung total gaji otomatis
     * Total = Gaji Pokok + Tunjangan + (Jam Lembur * Tarif) - Pajak
     */
    public function calculateTotal()
    {
        $this->overtime_pay = $this->overtime_hours * $this->overtime_rate;
        $this->total_salary = $this->basic_salary + $this->allowance + $this->overtime_pay - $this->tax;
    }

    /**
     * Scope untuk filter berdasarkan periode
     */
    public function scopeInPeriod($query, $month, $year)
    {
        return $query->where('period_month', $month)
                    ->where('period_year', $year);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get period label (e.g., "Januari 2026")
     */
    public function getPeriodLabelAttribute()
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return ($months[$this->period_month] ?? '') . ' ' . $this->period_year;
    }
}