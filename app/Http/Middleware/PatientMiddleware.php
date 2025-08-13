<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Auth;
class PatientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has user_type 'reception'
        if (Auth::check() && Auth::user()->user_type === 'patient') {
            return $next($request); // Allow access
        }

        // Deny access if user_type is not 'reception'
        return response()->json(['error' => 'Unauthorized'], 403);
        // Alternatively, you can redirect:
        // return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
