<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Class RequireAdmin
 * Middleware to require admin rank (7 or higher) to access admin routes
 * @package App\Http\Middleware
 */
class RequireAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'authentication-needed'], 401);
        }

        if ($user->isBanned) {
            return response()->json(['message' => 'account-banned'], 403);
        }

        // Check if user has admin rank (7 or higher)
        if (!isset($user->rank) || $user->rank < 7) {
            return response()->json(['message' => 'insufficient-permissions'], 403);
        }

        return $next($request);
    }
}