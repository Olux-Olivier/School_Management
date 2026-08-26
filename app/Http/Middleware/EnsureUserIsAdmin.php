<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user('admin')?->type === 'Admin', 403, 'Accès réservé aux administrateurs.');

        return $next($request);
    }
}
