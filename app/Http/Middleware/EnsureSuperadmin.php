<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->getAttribute('is_superadmin')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect(url('/'));
    }
}
