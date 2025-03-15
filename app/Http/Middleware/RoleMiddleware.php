<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role->value === UserRole::ADMIN->value) {
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => 'Forbidden: You do not have permission to access this resource.',
        ], 403);
    }
}
