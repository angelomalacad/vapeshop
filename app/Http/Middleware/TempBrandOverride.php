<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TempBrandOverride
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (!config('brand.override')) {
            return $response;
        }

        if (!$request->isMethod('GET')
            || $request->is('*/edit*')
            || $request->is('*/create*')
            || $request->ajax()
            || $request->expectsJson()) {
            return $response;
        }

        if (!str_contains((string) $response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (!is_string($content) || $content === '') {
            return $response;
        }

        $name  = config('brand.name');
        $upper = strtoupper($name);
        $lower = strtolower($name);
        $logo  = config('brand.logo');

        // ---- Replacement rules (applied to VISIBLE TEXT only) ----
        $rules = [];
        foreach ((array) config('brand.hide', []) as $find => $replace) {
            $rules[] = [(string) $find, (string) $replace];
        }
        $rules = array_merge($rules, [
            ['Brgy. Paciano Rizal', 'Sample Barangay'],
            ['Paciano Rizal',       'Sample Barangay'],
            ['Paciano V2',          'Branch 5'],   // before "Paciano"
            ['Paciano',             'Branch 4'],
            ['Majada Out',          'Branch 2'],
            ['Asia 1',              'Branch 1'],
            ['MCDC',                'Branch 3'],
            ['Canlubang',           'Sample District'],
            ['Calamba City',        'Sample City'],
            ['Calamba',             'Sample City'],
            ['Laguna',              'Sample Province'],
            ['Mayapa',              'Sample Area'],
        ]);

        $fakeAddresses = [
            '123 Sample Street, Sample City, Sample Province',
            '456 Placeholder Ave, Testville, Demo State',
            '789 Example Road, Dummy Town, Mock County',
            '1012 Fictional Lane, Sample City, Sample Province',
            '1314 Fake Boulevard, Testville, Demo State',
        ];
        $fakeLandmarks = [
            'Near Sample Landmark',
            'Near Placeholder Plaza',
            'Near Example Mall',
            'Near Test Point',
            'Near Demo Square',
        ];
        $pickFake = fn($original, $pool) => $pool[crc32($original) % count($pool)];

        // ---- Text swap: runs on text between tags only ----
        $swapText = function (string $t) use ($name, $upper, $lower, $rules, $fakeAddresses, $fakeLandmarks, $pickFake): string {
            // Brand name
            $t = preg_replace_callback(
                '/\bvape\s*[-_ ]?\s*expo\b/i',
                function ($m) use ($name, $upper, $lower) {
                    $x = $m[0];
                    if ($x === strtoupper($x)) return $upper;
                    if ($x === strtolower($x)) return $lower;
                    return $name;
                },
                $t
            ) ?? $t;

            // Hide list + branches + cities (in order)
            foreach ($rules as [$find, $replace]) {
                $t = preg_replace(
                    '/\b' . preg_quote($find, '/') . '\b/iu',
                    $replace,
                    $t
                ) ?? $t;
            }

            // Addresses (single line only)
            $t = preg_replace_callback(
                '/(?:Blk|Block)\s+\d+\s+Lot\s+\d+[^<\r\n]*/i',
                fn($m) => $pickFake($m[0], $fakeAddresses),
                $t
            ) ?? $t;

            $t = preg_replace_callback(
                '/\b\d{2,4}\s+(?:National Road|Sample Area\S*|Cadre Road)[^<\r\n]*/i',
                fn($m) => $pickFake($m[0], $fakeAddresses),
                $t
            ) ?? $t;

            $t = preg_replace_callback(
                '/\b(?:EFG Building|Majada Out Road|Kapayapaan)[^<\r\n]*/i',
                fn($m) => $pickFake($m[0], $fakeAddresses),
                $t
            ) ?? $t;

            // Landmarks — case-sensitive, single line, LAST
            $t = preg_replace_callback(
                '/\b(?:Near|In front of)\s+[^<\r\n]{5,120}/',
                fn($m) => $pickFake($m[0], $fakeLandmarks),
                $t
            ) ?? $t;

            return $t;
        };

        // ---- Split HTML into markup and text; only touch text ----
        $parts = preg_split(
            '/(<script\b.*?<\/script>|<style\b.*?<\/style>|<!--.*?-->|<(?:[^>"\']|"[^"]*"|\'[^\']*\')*>)/is',
            $content,
            -1,
            PREG_SPLIT_DELIM_CAPTURE
        );

        if ($parts === false) {
            return $response; // never break the page
        }

        foreach ($parts as $i => $part) {
            if ($part === '') {
                continue;
            }

            if ($i % 2 === 1) {
                // Markup: leave scripts, styles, comments untouched
                if (stripos($part, '<script') === 0
                    || stripos($part, '<style') === 0
                    || str_starts_with($part, '<!--')) {
                    continue;
                }

                // Only swap the logo and branch images inside tags (src, etc.)
                $part = str_replace('images/logo.png', $logo, $part);
                $part = preg_replace(
                    '#images/branches/[^"\'\s>]+#i',
                    'https://placehold.co/1200x500/e2e8f0/475569?text=Branch',
                    $part
                ) ?? $part;

                $parts[$i] = $part;
            } else {
                if (trim($part) === '') {
                    continue;
                }
                $parts[$i] = $swapText($part);
            }
        }

        $response->setContent(implode('', $parts));

        return $response;
    }
}
