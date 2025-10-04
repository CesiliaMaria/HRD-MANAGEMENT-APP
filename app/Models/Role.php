<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description'];

    /**
     * Relasi one-to-many: satu role bisa dimiliki banyak user
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}