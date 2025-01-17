<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ThrottleRequests
{
    public function handle($request, Closure $next)
    {
        $key = 'throttle_' . $request->ip();
        $maxAttempts = 10; // Maximum requests allowed
        $decayMinutes = 1; // Time window in minutes

        if (Cache::has($key)) {
            $attempts = Cache::get($key);

            if ($attempts >= $maxAttempts) {
                return response()->json([
                    'message' => 'Too many requests. Please try again later.'
                ], Response::HTTP_TOO_MANY_REQUESTS);
            }

            Cache::increment($key);
        } else {
            Cache::put($key, 1, now()->addMinutes($decayMinutes));
        }

        return $next($request);
    }
}
