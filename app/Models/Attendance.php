<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'check_in', 'check_out', 
        'latitude', 'longitude', 'location_address', 
        'status', 'notes'
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
    ];

    /**
     * Relasi many-to-one: attendance dimiliki oleh satu user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk check_in - convert dari UTC ke Asia/Jakarta
     */
    public function getCheckInAttribute($value)
    {
        return $value ? Carbon::parse($value)->setTimezone('Asia/Jakarta') : null;
    }

    /**
     * Accessor untuk check_out - convert dari UTC ke Asia/Jakarta
     */
    public function getCheckOutAttribute($value)
    {
        return $value ? Carbon::parse($value)->setTimezone('Asia/Jakarta') : null;
    }

    /**
     * Accessor untuk date - convert dari UTC ke Asia/Jakarta
     */
    public function getDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->setTimezone('Asia/Jakarta') : null;
    }
}