<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MinifyHtml
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only minify HTML responses in production
        if (app()->environment('production') && 
            $response instanceof \Illuminate\Http\Response &&
            $this->shouldMinify($response)) {
            
            $content = $response->getContent();
            $minified = $this->minifyHtml($content);
            $response->setContent($minified);
        }

        return $response;
    }

    /**
     * Check if response should be minified
     */
    private function shouldMinify($response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        
        return str_contains($contentType, 'text/html') || 
               str_contains($contentType, 'application/xhtml+xml');
    }

    /**
     * Minify HTML content
     */
    private function minifyHtml(string $html): string
    {
        // Preserve important whitespace in pre, code, textarea tags
        $preserveTags = [];
        $preservePattern = '/<(pre|code|textarea|script|style)[^>]*>.*?<\/\1>/is';
        
        preg_match_all($preservePattern, $html, $matches, PREG_OFFSET_CAPTURE);
        
        foreach ($matches[0] as $index => $match) {
            $placeholder = "<!--PRESERVE_WHITESPACE_{$index}-->";
            $preserveTags[$placeholder] = $match[0];
            $html = str_replace($match[0], $placeholder, $html);
        }

        // Remove HTML comments (except IE conditionals and preserve placeholders)
        $html = preg_replace('/<!--(?!\[if)(?!PRESERVE_WHITESPACE_).*?-->/s', '', $html);

        // Remove extra whitespace between tags
        $html = preg_replace('/>\s+</', '><', $html);

        // Remove extra whitespace at the beginning and end of lines
        $html = preg_replace('/^\s+|\s+$/m', '', $html);

        // Replace multiple spaces with single space
        $html = preg_replace('/\s{2,}/', ' ', $html);

        // Remove empty lines
        $html = preg_replace('/\n\s*\n/', "\n", $html);

        // Restore preserved content
        foreach ($preserveTags as $placeholder => $content) {
            $html = str_replace($placeholder, $content, $html);
        }

        return trim($html);
    }
}
