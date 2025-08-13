<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has user_type 'reception'
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return $next($request); // Allow access
        }

        // Deny access if user_type is not 'reception'
        return response()->json(['error' => 'Unauthorized'], 403);
        // Alternatively, you can redirect:
        // return redirect('/')->with('error', 'You do not have permission to access this page.');
    }
}
