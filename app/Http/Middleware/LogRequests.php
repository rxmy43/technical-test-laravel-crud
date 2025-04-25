<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('Incoming Request', [
            'ip' => $request->ip(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'input' => $request->except(['password', 'password_confirmation']), // avoid logging sensitive data
            'user_agent' => $request->userAgent(),
        ]);

        return $next($request);
    }
}
