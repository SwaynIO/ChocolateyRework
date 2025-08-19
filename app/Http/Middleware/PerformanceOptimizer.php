<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Performance Optimizer Middleware
 * Optimise les réponses pour la performance et l'éco-conception
 */
class PerformanceOptimizer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Compress response if not already compressed
        if ($this->shouldCompress($request, $response)) {
            $this->compressResponse($response);
        }

        // Add performance hints
        $this->addPerformanceHints($response);
        
        // Optimize images and assets
        $this->optimizeAssets($response);

        return $response;
    }

    private function shouldCompress(Request $request, $response): bool
    {
        return $response->getStatusCode() === 200 &&
               !$response->headers->has('Content-Encoding') &&
               $this->acceptsGzip($request) &&
               strlen($response->getContent()) > 1024;
    }

    private function acceptsGzip(Request $request): bool
    {
        return str_contains($request->header('Accept-Encoding', ''), 'gzip');
    }

    private function compressResponse($response): void
    {
        $content = $response->getContent();
        if ($content && function_exists('gzencode')) {
            $compressed = gzencode($content, 9);
            if ($compressed !== false) {
                $response->setContent($compressed);
                $response->headers->set('Content-Encoding', 'gzip');
                $response->headers->set('Content-Length', strlen($compressed));
            }
        }
    }

    private function addPerformanceHints($response): void
    {
        // DNS prefetch for external resources
        $hints = [
            '<https://cdn.jsdelivr.net>; rel=dns-prefetch',
            '<https://cdnjs.cloudflare.com>; rel=dns-prefetch',
            '<https://code.jquery.com>; rel=dns-prefetch'
        ];
        
        $response->headers->set('Link', implode(', ', $hints));
    }

    private function optimizeAssets($response): void
    {
        $content = $response->getContent();
        
        if (str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            // Minify HTML in production
            if (app()->environment('production')) {
                $content = $this->minifyHtml($content);
                $response->setContent($content);
            }
        }
    }

    private function minifyHtml(string $html): string
    {
        // Basic HTML minification for eco-design
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        $html = trim($html);
        
        return $html;
    }
}