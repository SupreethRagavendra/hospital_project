<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSystemIsSetup
{

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if any admin user exists
        try {
            $adminExists = \App\Models\User::where('role', 'admin')->exists();
        } catch (\Exception $e) {
            // If table doesn't exist or DB error, assume setup isn't done (though migration should handle schema)
            $adminExists = false;
        }

        // If no admin exists, force redirect to setup (unless already on setup page)
        if (!$adminExists && !$request->routeIs('setup.*')) {
            return redirect()->route('setup.index');
        }

        // If admin exists, block access to setup page
        if ($adminExists && $request->routeIs('setup.*')) {
            return redirect()->route('login')->with('error', 'System is already set up.');
        }

        return $next($request);
    }
}
