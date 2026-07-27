<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Only admin and superadmin can pass
        if (!auth()->check() || (!auth()->user()->isSuperadmin() && auth()->user()->role !== 'admin')) {
            abort(403, 'Anda tidak memiliki akses (Khusus Admin/Superadmin).');
        }

        return $next($request);
    }
}
