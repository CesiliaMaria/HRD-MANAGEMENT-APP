<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Middleware untuk mengecek role user
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek jika user tidak login, redirect ke login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek jika user memiliki role yang diizinkan
        $userRole = auth()->user()->role->name;
        if (!in_array($userRole, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}