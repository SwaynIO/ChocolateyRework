<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Cache Control Middleware pour optimiser les performances
 * Implémente les meilleures pratiques de cache HTTP
 */
class CacheControl
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $maxAge = 3600)
    {
        $response = $next($request);
        
        // Cache static assets for longer periods
        if ($this->isStaticAsset($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=31536000, immutable');
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s \G\M\T', time() + 31536000));
        }
        // Cache API responses with shorter TTL
        elseif ($this->isApiRoute($request)) {
            $response->headers->set('Cache-Control', 'public, max-age=' . $maxAge . ', stale-while-revalidate=300');
        }
        // No cache for admin and auth routes
        elseif ($this->isPrivateRoute($request)) {
            $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        // Add security headers for performance and eco-design
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        return $response;
    }

    private function isStaticAsset(Request $request): bool
    {
        $path = $request->getPathInfo();
        return preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf)$/i', $path);
    }

    private function isApiRoute(Request $request): bool
    {
        return str_starts_with($request->getPathInfo(), '/api/');
    }

    private function isPrivateRoute(Request $request): bool
    {
        $path = $request->getPathInfo();
        return str_starts_with($path, '/admin/') || 
               str_contains($path, '/login') || 
               str_contains($path, '/logout');
    }
}