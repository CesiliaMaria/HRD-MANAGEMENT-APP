<?php

namespace App\Http\Controllers;

use App\Models\OvertimeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    /**
     * Display a listing of overtime requests.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = OvertimeRequest::with('user', 'approver')
                    ->orderBy('date', 'desc');

        // Jika bukan admin, hanya tampilkan milik user sendiri
        if ($user->role_id !== 1) {
            $query->where('user_id', $user->id);
        }

        // Filter berdasarkan status (untuk admin)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $overtimes = $query->paginate(15);

        return view('overtimes.index', compact('overtimes'));
    }

    /**
     * Show the form for creating a new overtime request.
     */
    public function create()
    {
        return view('overtimes.create');
    }

    /**
     * Store a newly created overtime request in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'activity' => 'required|string|max:1000',
            'location' => 'nullable|string|max:255',
        ]);

        $overtime = new OvertimeRequest($validated);
        $overtime->user_id = Auth::id();
        $overtime->calculateDuration(); // Hitung durasi otomatis
        $overtime->save();

        return redirect()->route('overtimes.index')
            ->with('success', 'Pengajuan lembur berhasil dibuat. Menunggu persetujuan admin.');
    }

    /**
     * Display the specified overtime request.
     */
    public function show(OvertimeRequest $overtime)
    {
        // Authorization check
        $this->authorize('view', $overtime);

        return view('overtimes.show', compact('overtime'));
    }

    /**
     * Approve overtime request (admin only).
     */
    public function approve(Request $request, OvertimeRequest $overtime)
    {
        // Only admin can approve
        if (Auth::user()->role_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'admin_note' => 'nullable|string|max:500',
        ]);

        $overtime->status = 'approved';
        $overtime->approved_by = Auth::id();
        $overtime->approved_at = now();
        $overtime->admin_note = $validated['admin_note'] ?? null;
        $overtime->save();

        return back()->with('success', 'Pengajuan lembur telah disetujui.');
    }

    /**
     * Reject overtime request (admin only).
     */
    public function reject(Request $request, OvertimeRequest $overtime)
    {
        // Only admin can reject
        if (Auth::user()->role_id !== 1) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'admin_note' => 'required|string|max:500',
        ]);

        $overtime->status = 'rejected';
        $overtime->approved_by = Auth::id();
        $overtime->approved_at = now();
        $overtime->admin_note = $validated['admin_note'];
        $overtime->save();

        return back()->with('success', 'Pengajuan lembur telah ditolak.');
    }

    /**
     * Remove the specified overtime request from storage.
     */
    public function destroy(OvertimeRequest $overtime)
    {
        // Hanya bisa delete jika masih pending dan milik sendiri
        if ($overtime->user_id !== Auth::id() || $overtime->status !== 'pending') {
            abort(403, 'Tidak dapat menghapus pengajuan ini.');
        }

        $overtime->delete();

        return redirect()->route('overtimes.index')
            ->with('success', 'Pengajuan lembur berhasil dihapus.');
    }
}
