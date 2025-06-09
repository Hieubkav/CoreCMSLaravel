<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use App\Actions\FormatApiResponse;

class ApiRateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $maxAttempts = '60', string $decayMinutes = '1'): Response
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, (int) $maxAttempts)) {
            $retryAfter = RateLimiter::availableIn($key);

            return FormatApiResponse::error(
                'Too many requests. Please try again later.',
                429,
                [],
                [
                    'retry_after' => $retryAfter,
                    'max_attempts' => $maxAttempts,
                    'decay_minutes' => $decayMinutes,
                ]
            )->header('Retry-After', $retryAfter)
             ->header('X-RateLimit-Limit', $maxAttempts)
             ->header('X-RateLimit-Remaining', max(0, (int) $maxAttempts - RateLimiter::attempts($key)))
             ->header('X-RateLimit-Reset', now()->addMinutes((int) $decayMinutes)->timestamp);
        }

        RateLimiter::hit($key, (int) $decayMinutes * 60);

        $response = $next($request);

        // Add rate limit headers to successful responses
        if ($response instanceof \Illuminate\Http\JsonResponse) {
            $response->header('X-RateLimit-Limit', $maxAttempts)
                    ->header('X-RateLimit-Remaining', max(0, (int) $maxAttempts - RateLimiter::attempts($key)))
                    ->header('X-RateLimit-Reset', now()->addMinutes((int) $decayMinutes)->timestamp);
        }

        return $response;
    }

    /**
     * Resolve request signature for rate limiting
     */
    protected function resolveRequestSignature(Request $request): string
    {
        $user = $request->user();

        if ($user) {
            // Rate limit by user ID for authenticated requests
            return 'api_rate_limit:user:' . $user->id;
        }

        // Rate limit by IP for guest requests
        return 'api_rate_limit:ip:' . $request->ip();
    }
}
