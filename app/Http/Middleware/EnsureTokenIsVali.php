<?php

namespace App\Http\Middleware;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;

class EnsureTokenIsVali
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
        try{
            if($request->token){
                \Session::put('auth_token', $request->token);
                $user = \Tymon\JWTAuth\Facades\JWTAuth::toUser(\Tymon\JWTAuth\Facades\JWTAuth::getToken());
                Auth::guard('web')->login($user);
                \Session::put('auth_token', $request->token);
                \Session::put('user_id', @$user['id']);
            }
        }
        catch(\Exception $e){}
        return $next($request);
    }

}
