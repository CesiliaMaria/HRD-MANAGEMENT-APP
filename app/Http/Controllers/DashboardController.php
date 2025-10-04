<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Salary;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard berdasarkan role user
     */
    public function index()
    {
        $user = auth()->user();
        $today = Carbon::today();
        
        // Data untuk admin/manager
        if ($user->isAdmin() || $user->isManager()) {
            $totalEmployees = User::where('role_id', 3)->count();
            $presentToday = Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->count();
            $lateToday = Attendance::whereDate('date', $today)
                ->where('status', 'late')
                ->count();
            
            return view('dashboard.admin', compact('totalEmployees', 'presentToday', 'lateToday'));
        }
        
        // Data untuk employee
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $today)
            ->first();
            
        $monthlyAttendances = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $today->month)
            ->whereYear('date', $today->year)
            ->get();
            
        return view('dashboard.employee', compact('todayAttendance', 'monthlyAttendances'));
    }
}