<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user)
        {
            return redirect('login');
        }

        if (!in_array($user->rol, $roles, true))
        {
            abort(403, 'Sin permisos');
        }

        return $next($request);
    }
}
