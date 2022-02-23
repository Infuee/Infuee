<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Exception;

use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

use Closure;

class TokenAuth extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     * 's
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();            
            return $next($request);
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json(['code' => 301, 'message' => 'Token is Invalid']);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return response()->json(['code' => 301, 'message' => 'Token is Expired']);
            } else if ($e instanceof Exception) {
                return response()->json(['code' => 301, 'message' => $e->getMessage()]);
            } else {
                return response()->json(['code' => 301, 'message' => 'Something is wrong']);
            }
        }
    }
}
