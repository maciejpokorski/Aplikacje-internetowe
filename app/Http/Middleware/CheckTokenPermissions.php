<?php

namespace App\Http\Middleware;

use Closure;

class CheckTokenPermissions
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
        if($request->user()->hasPermissionTo('everything')){
            return $next($request);
        }else{
            return response('No access', 403);
        }
    }
}
