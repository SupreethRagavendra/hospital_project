<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\AuditLog;

class LogActivityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log POST, PUT, PATCH, DELETE requests to avoid log flooding
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $action = strtolower($request->method()) . '_' . str_replace('/', '_', trim($request->path(), '/'));
            $description = $request->method() . ' ' . $request->fullUrl();

            AuditLog::log($action, $description);
        }

        return $response;
    }
}
