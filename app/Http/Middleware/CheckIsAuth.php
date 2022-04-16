<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIsAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next)
    {
        $flag = false;

        if ( Auth::guard('organization')->check() or Auth::guard('web')->check() )
            $flag = true;

        if ( !$flag )
            return redirect('/');

        return $next($request);
    }
}
