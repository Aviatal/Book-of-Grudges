<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperadmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->getAttribute('is_superadmin')) {
            // Te trasy nie przechodzą przez EnsureCampaignSelected, a sidebar potrzebuje tej flagi.
            View::share('isSuperadmin', true);

            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect(url('/'));
    }
}
