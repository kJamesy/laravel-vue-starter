<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfInactive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( $user = Auth::guard('web')->user() ) {
            if ( ! $user->active )
                return redirect(route('admin.inactive'));
        }

        if ( $user = Auth::guard('member')->user() ) {
            if ( ! $user->active )
                return redirect(route('member.inactive'));
        }

        return $next($request);
    }
}
