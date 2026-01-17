<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Remove this line: use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Remove HasApiTokens from here

    protected $fillable = [
        'name',
        'email',
        'nip',
        'position',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relasi many-to-one: user memiliki satu role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relasi one-to-many: user bisa memiliki banyak attendance
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Relasi one-to-many: user bisa memiliki banyak salary
     */
    public function salaries()
    {
        return $this->hasMany(Salary::class);
    }

    /**
     * Check jika user adalah admin
     */
    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    /**
     * Check jika user adalah manager
     */
    public function isManager()
    {
        return $this->role_id === 2;
    }

    /**
     * Check jika user adalah employee
     */
    public function isEmployee()
    {
        return $this->role_id === 3;
    }
}