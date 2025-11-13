<?php

namespace App\Services;

class SvgSanitizer
{
    /**
     * Very small SVG sanitizer: removes <script> blocks, on* attributes and javascript: URIs.
     * This is intentionally conservative but not a full-proof sanitizer. For stricter
     * sanitization consider using a library like "enshrined/svg-sanitize".
     *
     * @param string $svg
     * @return string
     */
    public static function sanitize(string $svg): string
    {
        // remove script tags
        $svg = preg_replace('#<script[^>]*?>.*?</script>#is', '', $svg);

        // remove foreignObject (can include html/js)
        $svg = preg_replace('#<foreignObject[^>]*?>.*?</foreignObject>#is', '', $svg);

        // remove on* attributes (onclick, onload, etc.)
        $svg = preg_replace_callback('/\s+on[[:alnum:]_-]+\s*=\s*("[^"]*"|\'[^\']*\')/i', function ($m) { return ''; }, $svg);

        // remove javascript: URIs in href/src/xlink:href
        $svg = preg_replace('/(href|xlink:href|src)\s*=\s*("|\')\s*javascript:[^\"\']*("|\')/i', '$1=$2#removed$3', $svg);

        // remove potentially dangerous xmlns:xlink references to external resources
        $svg = preg_replace('/xlink:href\s*=\s*("|\')\s*http[^\"\']*("|\')/i', '$1$2', $svg);

        // Trim and return
        return trim($svg);
    }
}
