<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|array  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Convert roles to array and split comma-separated roles
        $allowedRoles = collect($roles)->flatMap(function ($role) {
            return explode(',', $role);
        })->toArray();
        
        if (!in_array($request->user()->role, $allowedRoles)) {
            abort(403, 'Unauthorized action. You need one of these roles: ' . implode(', ', $allowedRoles));
        }

        return $next($request);
    }
} 