<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class PasswordCheck
{
    public function handle($request, Closure $next, $guard = null)
    {
        $cacheKey = 'password_authenticated';

        if (Cache::get($cacheKey)) {
            return $next($request);
        }
        else {
            return redirect('/password-protected');
        }
       

        
    }
}