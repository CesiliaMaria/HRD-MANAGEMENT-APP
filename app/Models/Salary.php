<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'basic_salary', 'allowance', 'overtime_pay',
        'tax', 'total_salary', 'payment_status', 'payment_method',
        'transaction_id', 'payment_date', 'notes'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'allowance' => 'decimal:2',
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
}