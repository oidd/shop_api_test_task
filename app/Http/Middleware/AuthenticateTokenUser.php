<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

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

        Auth::shouldUse($guard);

        if (empty(Auth::getTokenForRequest()))
            throw new AuthenticationException('No token provided.');

        if (!Auth::check())
            throw new AuthenticationException('Invalid token.');

        if ($request->user()->token_expires_at->lte(Carbon::now()))
            throw new AuthenticationException('Token expired.');

        return $next($request);
    }
}
