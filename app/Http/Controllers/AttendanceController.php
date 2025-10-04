<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{
    /**
     * Menampilkan daftar absensi
     */
    public function index()
    {
        try {
            $user = auth()->user();
            
            if ($user->isAdmin() || $user->isManager()) {
                $attendances = Attendance::with('user')
                    ->orderBy('date', 'desc')
                    ->orderBy('check_in', 'desc')
                    ->paginate(10);
            } else {
                $attendances = Attendance::where('user_id', $user->id)
                    ->orderBy('date', 'desc')
                    ->orderBy('check_in', 'desc')
                    ->paginate(10);
            }
            
            return view('attendances.index', compact('attendances'));
            
        } catch (\Exception $e) {
            Log::error('Error in attendance index: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat mengambil data absensi.');
        }
    }
    
    /**
     * Menampilkan form check-in
     */
    public function create()
    {
        try {
            $user = auth()->user();
            $today = Carbon::now('Asia/Jakarta')->toDateString();
            
            // Cek apakah sudah check-in hari ini
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if ($existingAttendance) {
                return redirect()->route('dashboard')->with('info', 'Anda sudah check-in hari ini pada jam ' . 
                    $existingAttendance->check_in->format('H:i:s'));
            }
            
            return view('attendances.checkin');
            
        } catch (\Exception $e) {
            Log::error('Error in attendance create: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Proses check-in
     */
    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Gunakan waktu Indonesia untuk semua
            $now = Carbon::now('Asia/Jakarta');
            $today = $now->toDateString();
            
            Log::info('Check-in attempt by user: ' . $user->id . ' at ' . $now->format('H:i:s'));
            
            // Cek apakah sudah check-in hari ini
            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if ($existingAttendance) {
                Log::warning('User already checked in today: ' . $user->id);
                return redirect()->route('dashboard')->with('info', 'Anda sudah check-in hari ini pada jam ' . 
                    $existingAttendance->check_in->format('H:i:s'));
            }
            
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'location_address' => 'required|string',
            ]);
            
            // Bulatkan koordinat untuk menghindari precision error
            $latitude = round($request->latitude, 6);
            $longitude = round($request->longitude, 6);
            
            Log::info('Coordinates: ' . $latitude . ', ' . $longitude . ', Address: ' . $request->location_address);
            
            // Simpan data check-in dengan waktu Indonesia
            $attendance = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->date = $today;
            $attendance->check_in = $now; // Sudah dalam timezone Asia/Jakarta
            $attendance->latitude = $latitude;
            $attendance->longitude = $longitude;
            $attendance->location_address = $request->location_address;
            
            // Tentukan status (on-time atau late)
            $officeStartTime = Carbon::createFromTime(8, 0, 0, 'Asia/Jakarta'); // Jam 8 pagi WIB
            
            if ($now->gt($officeStartTime)) {
                $attendance->status = 'late';
                Log::info('User checked in late: ' . $now->format('H:i:s'));
            } else {
                $attendance->status = 'present';
                Log::info('User checked in on time: ' . $now->format('H:i:s'));
            }
            
            $attendance->save();
            Log::info('Check-in saved successfully for user: ' . $user->id);

            return redirect()->route('dashboard')->with('success', 'Check-in berhasil! Waktu: ' . $now->format('H:i:s'));
            
        } catch (\Exception $e) {
            Log::error('Check-in error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('attendances.create')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
    
    /**
     * Proses check-out
     */
    public function checkout(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Gunakan waktu Indonesia untuk semua
            $now = Carbon::now('Asia/Jakarta');
            $today = $now->toDateString();
            
            Log::info('Check-out attempt by user: ' . $user->id . ' at ' . $now->format('H:i:s'));
            
            // Cari attendance hari ini
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if (!$attendance) {
                Log::warning('User tried to check-out without check-in: ' . $user->id);
                return redirect()->route('dashboard')->with('error', 'Anda belum check-in hari ini.');
            }
            
            if ($attendance->check_out) {
                Log::warning('User already checked out: ' . $user->id);
                return redirect()->route('dashboard')->with('info', 'Anda sudah check-out hari ini pada jam ' . 
                    $attendance->check_out->format('H:i:s'));
            }
            
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'location_address' => 'required|string',
            ]);
            
            // Update check-out time dengan waktu Indonesia
            $attendance->check_out = $now;
            $attendance->save();
            
            Log::info('Check-out saved successfully for user: ' . $user->id);

            return redirect()->route('dashboard')->with('success', 'Check-out berhasil! Waktu: ' . $now->format('H:i:s'));
            
        } catch (\Exception $e) {
            Log::error('Check-out error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('attendances.checkout-form')->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan form check-out dengan lokasi
     */
    public function showCheckoutForm()
    {
        try {
            $user = auth()->user();
            $today = Carbon::now('Asia/Jakarta')->toDateString();
            
            $todayAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('date', $today)
                ->first();
                
            if (!$todayAttendance) {
                return redirect()->route('dashboard')->with('error', 'Anda belum check-in hari ini.');
            }
            
            if ($todayAttendance->check_out) {
                return redirect()->route('dashboard')->with('info', 'Anda sudah check-out hari ini pada jam ' . 
                    $todayAttendance->check_out->format('H:i:s'));
            }
            
            return view('attendances.checkout', compact('todayAttendance'));
            
        } catch (\Exception $e) {
            Log::error('Error in checkout form: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail absensi (optional)
     */
    public function show($id)
    {
        try {
            $attendance = Attendance::with('user')->findOrFail($id);
            
            // Authorization check - user hanya bisa lihat absensi sendiri kecuali admin/manager
            $user = auth()->user();
            if (!$user->isAdmin() && !$user->isManager() && $attendance->user_id != $user->id) {
                abort(403, 'Unauthorized action.');
            }
            
            return view('attendances.show', compact('attendance'));
            
        } catch (\Exception $e) {
            Log::error('Error in attendance show: ' . $e->getMessage());
            return redirect()->route('attendances.index')->with('error', 'Data absensi tidak ditemukan.');
        }
    }

    /**
     * Menghapus absensi (admin only)
     */
    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            
            // Hanya admin yang bisa menghapus absensi
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Unauthorized action.');
            }
            
            $attendance->delete();
            
            Log::info('Attendance deleted by admin: ' . auth()->user()->id . ', attendance ID: ' . $id);
            
            return redirect()->route('attendances.index')->with('success', 'Data absensi berhasil dihapus.');
            
        } catch (\Exception $e) {
            Log::error('Error deleting attendance: ' . $e->getMessage());
            return redirect()->route('attendances.index')->with('error', 'Gagal menghapus data absensi.');
        }
    }
}