<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if (!auth()->check() || !auth()->user()->can($permission)) {
            abort(403, 'Vous n\'avez pas les droits nécessaires.');
        }

        return $next($request);
    }
}
