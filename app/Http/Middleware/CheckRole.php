<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {   if(Auth::guest()){
         return view('/login');
    } else {
        if (!$request->user()->hasRole($role)) {
           return response()->view('errors.401');
        } 
        return $next($request);
    }
        return $next($request);
    }
}
