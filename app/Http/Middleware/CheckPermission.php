<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $permissions = $user->getPermissions();

        if (!in_array($permission, $permissions)) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
