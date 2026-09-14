<?php

namespace App\Http\Middleware;

use App\Support\CurrentCampaign;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCampaignGm
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app(CurrentCampaign::class)->isGm()) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return redirect(url('/'));
    }
}
