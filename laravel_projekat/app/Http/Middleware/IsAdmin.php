<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if(Auth::check()){
            if(Auth()->user()->role=='1'){
                return $next($request);

            }else {
                return redirect('/')->with('message','Access denied!');
            }

        }
        else{
            return redirect('/login')->with('message','Login to continue');
        }
    }
}
