<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Salary;
use App\Models\OvertimeRequest;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan role user
     * Menggunakan satu view (home.blade.php) dengan conditional rendering
     */
    public function index()
    {
        // Semua logic ada di view home.blade.php
        // View akan otomatis detect role dan render sesuai
        return view('home');
    }
}