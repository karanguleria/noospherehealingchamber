<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;


class NovaAccessControl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and has type_id 2 or 3
        if (Auth::check() && !in_array(Auth::user()->type_id, [2, 3])) {
            // If not authorized, log the user out
            Auth::logout();

            // Redirect to Nova login page with an error message
            return redirect()->route('nova.login')->withErrors([
                'email' => 'Access denied. Only users with type_id 2 or 3 can access Nova.'
            ]);
        }

        return $next($request);
    }
}
