<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visitor;
use App\Models\Course;
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
     * Track visitor - KISS principle
     */
    private function trackVisitor(Request $request): void
    {
        try {
            $courseId = null;

            // Detect course page
            if ($request->route() && $request->route()->getName() === 'courses.show') {
                $courseSlug = $request->route('slug');
                $course = Course::where('slug', $courseSlug)->first();
                $courseId = $course?->id;
            }

            // Tránh duplicate trong cùng session và URL
            $sessionId = session()->getId();
            $url = $request->fullUrl();
            $ipAddress = $request->ip();

            $exists = Visitor::where('session_id', $sessionId)
                ->where('url', $url)
                ->where('ip_address', $ipAddress)
                ->whereDate('visited_at', today())
                ->exists();

            if (!$exists) {
                Visitor::create([
                    'ip_address' => $ipAddress,
                    'user_agent' => $request->userAgent(),
                    'url' => $url,
                    'course_id' => $courseId,
                    'session_id' => $sessionId,
                    'visited_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // Silent fail - không làm crash website
            Log::error('TrackVisitor error: ' . $e->getMessage());
        }
    }
}
