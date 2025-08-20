<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth()->user();
        if ($user && in_array($permission, $user->getPermissions())) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
