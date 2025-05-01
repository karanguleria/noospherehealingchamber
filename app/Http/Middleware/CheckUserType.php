<?php 
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
public function handle(Request $request, Closure $next)
{
$user = Auth::user();

// Prevent users with type_id 1 and allow users with type_id 2 or 3
if ($user && in_array($user->type_id, [1])) {
return abort(403, 'Unauthorized Access');
}

return $next($request);
}
}
