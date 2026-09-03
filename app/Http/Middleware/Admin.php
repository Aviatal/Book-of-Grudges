<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Auth::user() && \Auth::user()->getAttribute('is_admin')) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect(url('/'));
    }
}
