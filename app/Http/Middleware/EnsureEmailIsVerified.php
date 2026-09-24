<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified as BaseEnsureEmailIsVerified;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Standardowy "verified" z możliwością wyłączenia przez AUTH_EMAIL_VERIFICATION_REQUIRED=false (środowisko lokalne).
 */
class EnsureEmailIsVerified extends BaseEnsureEmailIsVerified
{
    public function handle($request, Closure $next, $redirectToRoute = null): Response
    {
        if (! config('auth.email_verification_required')) {
            return $next($request);
        }

        return parent::handle($request, $next, $redirectToRoute);
    }
}
