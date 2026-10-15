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

        if (!str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            return $response;
        }

        $original = $response->getContent();
        if (!is_string($original) || $original === '') {
            return $response;
        }

        $name  = config('brand.name');
        $upper = strtoupper($name);
        $lower = strtolower($name);

        // Safe preg_replace: never returns null (would blank the page)
        $sub = fn($pattern, $rep, $subject) => preg_replace($pattern, $rep, $subject) ?? $subject;

        // ---- Protect <script> blocks and Inertia data-page from the regexes ----
        $stash = [];
        $content = preg_replace_callback(
            '/<script\b[^>]*>.*?<\/script>|data-page="[^"]*"/is',
            function ($m) use (&$stash) {
                $k = '@@KEEP' . count($stash) . '@@';
                $stash[$k] = $m[0];
                return $k;
            },
            $original
        ) ?? $original;

        // ---- Brand text swap ----
        $content = preg_replace_callback(
            '/\bvape\s*[-_ ]?\s*expo\b/i',
            function ($m) use ($name, $upper, $lower) {
                $t = $m[0];
                if ($t === strtoupper($t)) return $upper;
                if ($t === strtolower($t)) return $lower;
                return $name;
            },
            $content
        ) ?? $content;

        // ---- Logo swap ----
        $content = str_replace('images/logo.png', config('brand.logo'), $content);

        // ---- Branch image swap ----
        $content = $sub(
            '#images/branches/[^"\'\s>]+#i',
            'https://placehold.co/1200x500/e2e8f0/475569?text=Branch',
            $content
        );

        // ============================================================
        // ONE ordered rule list (used by BOTH server and browser script)
        // Order: config hide list -> barangay -> branches -> cities
        // ============================================================
        $rules = [];
        foreach ((array) config('brand.hide', []) as $find => $replace) {
            $rules[] = [(string) $find, (string) $replace];
        }
        $rules = array_merge($rules, [
            ['Brgy. Paciano Rizal', 'Sample Barangay'],
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

        foreach ($rules as [$find, $replace]) {
            $content = $sub(
                '/\b' . preg_quote($find, '/') . '\b/iu',
                $replace,
                $content
            );
        }

        // ============================================================
        // FULL ADDRESS REDACTION (server-side only)
        // ============================================================
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

        $content = preg_replace_callback(
            '/(?:Blk|Block)\s+\d+\s+Lot\s+\d+[^<]*/i',
            fn($m) => $pickFake($m[0], $fakeAddresses),
            $content
        ) ?? $content;

        $content = preg_replace_callback(
            '/\b\d{2,4}\s+(?:National Road|Sample Area[^\s<]*|Cadre Road)[^<]*/i',
            fn($m) => $pickFake($m[0], $fakeAddresses),
            $content
        ) ?? $content;

        $content = preg_replace_callback(
            '/\b(?:EFG Building|Majada Out Road|Kapayapaan)[^<]*/i',
            fn($m) => $pickFake($m[0], $fakeAddresses),
            $content
        ) ?? $content;

        // Landmarks — LAST. Case-sensitive now so "near you" is not touched.
        $content = preg_replace_callback(
            '/\b(?:Near|In front of)\s+[^<]{5,120}(?=<|$)/',
            fn($m) => $pickFake($m[0], $fakeLandmarks),
            $content
        ) ?? $content;

        // ---- Restore scripts and data-page ----
        $content = strtr($content, $stash);

        // ============================================================
        // BROWSER SCRIPT: same rules, keeps working after Inertia navigation
        // and also fixes text rendered by Vue/React.
        // ============================================================
        $data = json_encode(
            ['name' => $name, 'upper' => $upper, 'lower' => $lower, 'rules' => $rules],
            JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
        );

        $js = <<<'JS'
<script>
(function () {
  var D = __DATA__;
  var esc = function (s) { return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); };
  var brand = /\bvape\s*[-_ ]?\s*expo\b/gi;
  var rules = D.rules.map(function (p) {
    return [new RegExp('\\b' + esc(p[0]) + '\\b', 'giu'), p[1]];
  });
  var ATTRS = ['placeholder', 'title', 'alt', 'aria-label'];

  function fix(s) {
    if (!s) return s;
    s = s.replace(brand, function (m) {
      return m === m.toUpperCase() ? D.upper : (m === m.toLowerCase() ? D.lower : D.name);
    });
    for (var i = 0; i < rules.length; i++) s = s.replace(rules[i][0], rules[i][1]);
    return s;
  }

  function fixNode(n) {
    if (n.nodeType === 3) {
      var p = n.parentNode;
      if (p && /^(SCRIPT|STYLE|TEXTAREA)$/.test(p.nodeName)) return;
      var v = fix(n.nodeValue);
      if (v !== n.nodeValue) n.nodeValue = v;
      return;
    }
    if (n.nodeType !== 1 || /^(SCRIPT|STYLE)$/.test(n.nodeName)) return;
    ATTRS.forEach(function (a) {
      if (n.hasAttribute(a)) {
        var o = n.getAttribute(a), r = fix(o);
        if (r !== o) n.setAttribute(a, r);
      }
    });
    for (var c = n.firstChild; c; c = c.nextSibling) fixNode(c);
  }

  function fixTitle() {
    var t = fix(document.title);
    if (t !== document.title) document.title = t;
  }

  function run() {
    fixNode(document.body);
    fixTitle();
    new MutationObserver(function (muts) {
      muts.forEach(function (m) {
        if (m.type === 'characterData') fixNode(m.target);
        m.addedNodes.forEach(fixNode);
      });
      fixTitle();
    }).observe(document.documentElement, { childList: true, subtree: true, characterData: true });
  }

  if (document.body) run(); else document.addEventListener('DOMContentLoaded', run);
})();
</script>
JS;
        $js = str_replace('__DATA__', $data, $js);

        $pos = strripos($content, '</body>');
        $content = $pos !== false
            ? substr_replace($content, $js . "\n", $pos, 0)
            : $content . $js;

        $response->setContent($content);

        return $response;
    }
}
