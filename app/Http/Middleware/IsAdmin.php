<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
   public function handle($request, Closure $next)
{
    if (auth()->user() && auth()->user()->role === 'admin') {
        return $next($request);
    }

    abort(403, 'Unauthorized');
}

}
