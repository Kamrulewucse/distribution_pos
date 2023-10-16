<?php

namespace App\Http\Middleware;

use App\Models\LoginCheck;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
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
        $check = LoginCheck::first();

        //$response = $next($request);
        //If the status is not approved redirect to login
        if(Auth::check() && $check->status != 1){
            Auth::logout();
            return redirect('/login');
        }
        return $next($request);
    }
}
