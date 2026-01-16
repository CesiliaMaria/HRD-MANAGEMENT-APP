<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'duration_hours',
        'activity',
        'location',
        'status',
        'approved_by',
        'approved_at',
        'admin_note',
    ];

    protected $casts = [
        'date' => 'date',
        'approved_at' => 'datetime',
        'duration_hours' => 'decimal:2',
    ];

    /**
     * Relasi ke User (karyawan yang mengajukan)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User (admin yang approve)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Hitung durasi otomatis dari start_time dan end_time
     */
    public function calculateDuration()
    {
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        
        // Jika end_time lebih kecil dari start_time, berarti lewat tengah malam
        if ($end->lt($start)) {
            $end->addDay();
        }
        
        $diffInMinutes = $start->diffInMinutes($end);
        $this->duration_hours = round($diffInMinutes / 60, 2);
    }

    /**
     * Scope untuk filter berdasarkan user
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk filter berdasarkan periode (bulan & tahun)
     */
    public function scopeInPeriod($query, $month, $year)
    {
        return $query->whereYear('date', $year)
                    ->whereMonth('date', $month);
    }

    /**
     * Check apakah sudah di-approve
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check apakah masih pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check apakah ditolak
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
