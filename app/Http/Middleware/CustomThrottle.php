<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;

class CustomThrottle extends ThrottleRequests
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $limit
     * @param  int  $time
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $limit = 60, $time = 1)
    {
        // Custom throttle logic can go here
        return parent::handle($request, $next, $limit, $time);
    }
}
