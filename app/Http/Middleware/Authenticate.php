<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request ;
use Closure, Auth;
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
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

        return parent::handle($request, $next);
    }

    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
