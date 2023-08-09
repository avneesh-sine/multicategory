<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null){
        $guard = ($guard == null)?config('auth.defaults.guard'):$guard;
        if(Auth::guard($guard)->check()){ // Check logged in
            $user = Auth::user();

            if(!$user->hasRole(config('constants.ROLE_TYPE_SUPERADMIN_ID'))){
                $current_route = $request->route()->getName();
                if(!$user->can($current_route, $guard)){ // Check permission of current route
                    return redirect()->route('access-denied');
                }          
            }
        }
        return $next($request);
    }
}
