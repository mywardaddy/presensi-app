<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if (Auth::check()) {
        \Log::info('User role_id: ' . Auth::user()->role_id);
    } else {
        \Log::info('User not logged in');
    }
    
    if (Auth::user() && Auth::user()->role_id == 2) {
        return $next($request);
    }
    return abort(403);
}
}