<?php
namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
class IsAdmin{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){ 
        if(auth()){
            if(Auth::guard('admin')->user()) {
                return $next($request);
            }
            // if(Auth::guard('storeLogin')->user()) {
            //     return $next($request);
            // }
        }
        return redirect('admin');       
    }
}
