<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role;
        
        // Special case for kaprodi hybrid role
        if (in_array('kaprodi', $roles) && $userRole === 'lecturer') {
            if (auth()->user()->lecturer && auth()->user()->lecturer->is_kaprodi) {
                return $next($request);
            }
        }

        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        abort(403, 'Unauthorized action.');
    }
}
