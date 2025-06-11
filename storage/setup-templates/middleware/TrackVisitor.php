<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use Illuminate\Support\Facades\Log;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ track GET requests và không track admin routes
        if ($request->isMethod('GET') && !$request->is('admin/*') && !$request->is('api/*')) {
            $this->trackVisitor($request);
        }

        return $next($request);
    }

    /**
     * Track visitor - KISS principle: Ai vào thì track, không dùng session
     */
    private function trackVisitor(Request $request): void
    {
        try {
            // Lấy thông tin cơ bản
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();
            $url = $request->fullUrl();

            // Configurable tracking interval - có thể điều chỉnh trong .env
            $trackingInterval = env('VISITOR_TRACKING_INTERVAL', 5); // Default 5 giây
            
            // Check xem có visit gần đây không (cùng IP + URL)
            $recentVisit = Visitor::where('ip_address', $ipAddress)
                ->where('url', $url)
                ->where('visited_at', '>', now()->subSeconds($trackingInterval))
                ->exists();

            // Nếu chưa có visit gần đây thì track
            if (!$recentVisit) {
                $visitor = Visitor::create([
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'url' => $url,
                    'content_id' => null, // Đơn giản hóa, không detect content
                    'session_id' => 'simple_' . uniqid(), // Session đơn giản
                    'visited_at' => now(),
                ]);

                // Clear cache để update stats realtime
                \Illuminate\Support\Facades\Cache::forget('visitor_stats_today_unique');
                \Illuminate\Support\Facades\Cache::forget('visitor_stats_today_total');
                \Illuminate\Support\Facades\Cache::forget('visitor_stats_total_unique');
                \Illuminate\Support\Facades\Cache::forget('visitor_stats_total_visits');
                \Illuminate\Support\Facades\Cache::forget('analytics_widget_stats');

                Log::info('Visitor tracked', [
                    'visitor_id' => $visitor->id,
                    'ip' => $ipAddress,
                    'url' => $url
                ]);
            }
        } catch (\Exception $e) {
            // Log error để debug
            Log::error('TrackVisitor error: ' . $e->getMessage(), [
                'url' => $request->fullUrl(),
                'ip' => $request->ip()
            ]);
        }
    }
}
