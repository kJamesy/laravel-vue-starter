<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthenticateMember
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
        if ( ! Auth::guard('member')->check() ) {
            if ( Auth::guard('web')->check() )
                return redirect(route('admin.home'));

            return redirect(route('member.auth.show_login'));
        }

        return $next($request);
    }
}
