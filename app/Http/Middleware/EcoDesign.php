<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\EcoDesignService;

/**
 * Middleware d'éco-conception pour optimiser l'empreinte environnementale
 * Implémente les principes du développement durable
 */
class EcoDesign
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only process HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        $content = $response->getContent();
        $originalSize = strlen($content);
        
        // Apply eco-design optimizations
        if (app()->environment('production')) {
            $content = $this->optimizeHtmlForEcoDesign($content);
        }
        
        // Add eco-design headers
        $this->addEcoDesignHeaders($response, $originalSize, strlen($content));
        
        // Set optimized content
        $response->setContent($content);
        
        return $response;
    }

    /**
     * Check if response is HTML
     */
    private function isHtmlResponse($response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html') || 
               (empty($contentType) && str_contains($response->getContent(), '<html'));
    }

    /**
     * Optimize HTML for eco-design principles
     */
    private function optimizeHtmlForEcoDesign(string $html): string
    {
        // Remove unnecessary whitespace (but preserve pre and textarea content)
        $html = preg_replace_callback(
            '/<(pre|textarea|code)[^>]*>.*?<\/\1>/s',
            function($matches) { return $matches[0]; },
            $html
        );
        
        // Minify HTML outside of preserved elements
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/>\s+</', '><', $html);
        
        // Remove empty lines
        $html = preg_replace('/^\s*$/m', '', $html);
        
        // Add async/defer to external scripts for better performance
        $html = preg_replace(
            '/<script\s+src="([^"]+)"(?![^>]*(?:async|defer))/i',
            '<script src="$1" defer',
            $html
        );
        
        // Add loading="lazy" to images (except above the fold)
        $html = preg_replace(
            '/<img\s+([^>]*src="[^"]*")(?![^>]*loading=)/i',
            '<img $1 loading="lazy"',
            $html
        );
        
        // Optimize CSS in style tags
        $html = preg_replace_callback(
            '/<style[^>]*>(.*?)<\/style>/s',
            function($matches) {
                return '<style>' . EcoDesignService::minifyCSS($matches[1]) . '</style>';
            },
            $html
        );
        
        // Optimize JavaScript in script tags
        $html = preg_replace_callback(
            '/<script(?![^>]*src=)[^>]*>(.*?)<\/script>/s',
            function($matches) {
                return '<script>' . EcoDesignService::minifyJS($matches[1]) . '</script>';
            },
            $html
        );
        
        // Add resource hints for better performance
        $html = $this->addResourceHints($html);
        
        return trim($html);
    }

    /**
     * Add resource hints for better performance and eco-design
     */
    private function addResourceHints(string $html): string
    {
        $hints = [
            '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>',
            '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>',
            '<link rel="dns-prefetch" href="https://fonts.googleapis.com">',
        ];
        
        // Insert hints after <head>
        $html = preg_replace(
            '/<head[^>]*>/i',
            '$0' . "\n    " . implode("\n    ", $hints),
            $html
        );
        
        return $html;
    }

    /**
     * Add eco-design related headers
     */
    private function addEcoDesignHeaders($response, int $originalSize, int $optimizedSize): void
    {
        $savings = $originalSize - $optimizedSize;
        $savingsPercent = $originalSize > 0 ? round(($savings / $originalSize) * 100, 1) : 0;
        
        // Custom headers for monitoring eco-design impact
        $response->headers->set('X-Eco-Original-Size', $originalSize);
        $response->headers->set('X-Eco-Optimized-Size', $optimizedSize);
        $response->headers->set('X-Eco-Savings-Bytes', $savings);
        $response->headers->set('X-Eco-Savings-Percent', $savingsPercent);
        
        // Calculate carbon footprint
        $carbonData = EcoDesignService::calculatePageCarbonFootprint($optimizedSize);
        $response->headers->set('X-Eco-Carbon-Mg', round($carbonData['carbon_mg'], 2));
        
        // Add energy efficiency grade
        $grade = $this->calculateEnergyGrade($optimizedSize);
        $response->headers->set('X-Eco-Energy-Grade', $grade);
        
        // Server timing for performance monitoring
        $response->headers->set('Server-Timing', sprintf(
            'eco-optimization;dur=%.2f;desc="Eco-design optimization"',
            0.1 // Minimal overhead
        ));
    }

    /**
     * Calculate energy efficiency grade based on page size
     */
    private function calculateEnergyGrade(int $size): string
    {
        // Based on HTTP Archive data and eco-design best practices
        if ($size < 50000) return 'A+'; // < 50KB
        if ($size < 100000) return 'A';  // < 100KB
        if ($size < 200000) return 'B';  // < 200KB
        if ($size < 500000) return 'C';  // < 500KB
        if ($size < 1000000) return 'D'; // < 1MB
        return 'E'; // > 1MB
    }
}