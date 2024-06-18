<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// using this middleware instead of default 'auth' in order to
// throw proper exceptions with explanations.
// also the default one doesn't have logic for checking if provided token expired.

class AuthenticateTokenUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $guard = null): Response
    {
        if (empty($guard))
            $guard = config('auth.defaults.guard');

        if (empty(Auth::getTokenForRequest()))
            throw new AuthenticationException('No token provided.');

        if ($guard == 'any')
        {
            // don't know why 'web' guard keeps pop up in here, it is not in auth config file
            $guards = collect(config('auth.guards'))->except('web');

            foreach ($guards as $k => $_)
                if ($v = Auth::guard($k)->check())
                {
                    $guard = $k;
                    break;
                }
        }
        else
            $v = Auth::guard($guard)->check();

        if (!$v)
            throw new AuthenticationException('Invalid token.');

        if (Auth::guard($guard)->user()->token_expires_at->lte(Carbon::now()))
            throw new AuthenticationException('Token expired.');

        Auth::shouldUse($guard); // so next time Auth::user() will use right guard within this request

        return $next($request);
    }
}
