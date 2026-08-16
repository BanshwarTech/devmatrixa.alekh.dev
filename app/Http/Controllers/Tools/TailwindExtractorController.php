<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Services\PageFetcher;
use Illuminate\Http\Request;

class TailwindExtractorController extends Controller
{
    private const PREFIXES = [
        'container', 'block', 'inline', 'flex', 'grid', 'hidden', 'visible', 'invisible', 'contents', 'flow-root',
        'static', 'fixed', 'absolute', 'relative', 'sticky',
        'inset', 'top', 'right', 'bottom', 'left', 'z',
        'float', 'clear', 'isolation', 'object', 'overflow', 'overscroll',
        'w', 'h', 'min-w', 'max-w', 'min-h', 'max-h', 'size',
        'm', 'mx', 'my', 'mt', 'mr', 'mb', 'ml', 'ms', 'me',
        'p', 'px', 'py', 'pt', 'pr', 'pb', 'pl', 'ps', 'pe',
        'space', 'gap', 'gap-x', 'gap-y',
        'basis', 'grow', 'shrink', 'order', 'col', 'row', 'auto-cols', 'auto-rows',
        'items', 'justify', 'content', 'place', 'self', 'justify-self', 'align', 'place-self',
        'font', 'text', 'leading', 'tracking', 'indent', 'decoration', 'underline',
        'uppercase', 'lowercase', 'capitalize', 'truncate', 'whitespace', 'break', 'list',
        'bg', 'from', 'via', 'to', 'border', 'divide', 'outline', 'ring', 'shadow',
        'opacity', 'mix-blend', 'bg-blend',
        'filter', 'blur', 'brightness', 'contrast', 'drop-shadow', 'grayscale', 'hue-rotate', 'invert', 'saturate', 'sepia',
        'backdrop',
        'transition', 'duration', 'ease', 'delay', 'animate',
        'scale', 'rotate', 'translate', 'skew', 'origin',
        'cursor', 'pointer', 'resize', 'select', 'touch', 'will-change', 'appearance',
        'rounded', 'border-collapse', 'border-separate', 'border-spacing',
        'fill', 'stroke', 'sr', 'not-sr', 'aspect', 'columns', 'scroll', 'snap',
        'caret', 'accent', 'table', 'caption',
    ];

    private const STANDALONE = [
        'italic', 'not-italic', 'antialiased', 'subpixel-antialiased', 'normal-case',
        'underline', 'overline', 'line-through', 'no-underline', 'truncate', 'overflow-ellipsis',
        'grow', 'shrink', 'flex-1', 'flex-auto', 'flex-initial', 'flex-none',
        'ltr', 'rtl', 'prose',
    ];

    public function analyze(Request $request)
    {
        $url = (string) $request->input('url', '');

        if (! PageFetcher::isValidUrl($url)) {
            return response()->json(['error' => 'Invalid URL'], 422);
        }

        try {
            $page = PageFetcher::fetchPage($url);
        } catch (\Throwable) {
            return response()->json(['error' => 'Could not fetch the URL. Make sure it is publicly accessible.'], 422);
        }

        $html = $page['html'];
        $loadTime = $page['loadTime'];

        $classes = [];
        if (preg_match_all('/\bclass=["\']([^"\']*)["\']/', $html, $matches)) {
            foreach ($matches[1] as $classAttr) {
                foreach (preg_split('/\s+/', trim($classAttr)) as $c) {
                    if ($c !== '') {
                        $classes[$c] = ($classes[$c] ?? 0) + 1;
                    }
                }
            }
        }

        $totalRaw = array_sum($classes);

        $tw = [];
        foreach ($classes as $c => $n) {
            if ($this->isTailwind($c)) {
                $tw[$c] = $n;
            }
        }

        $groups = [];
        foreach ($tw as $c => $n) {
            $base = preg_replace('/^([\w-]+:)+/', '', $c);
            $g = $this->groupClass($c, $base);
            $groups[$g][$c] = $n;
        }

        $grouped = [];
        foreach ($groups as $name => $items) {
            arsort($items);
            $grouped[$name] = ['classes' => array_keys($items), 'count' => count($items)];
        }

        return response()->json([
            'url' => $url,
            'loadTime' => $loadTime,
            'totalRaw' => $totalRaw,
            'tailwindCount' => count($tw),
            'grouped' => $grouped,
        ]);
    }

    private function isTailwind(string $cls): bool
    {
        $base = preg_replace('/^([\w-]+:)+/', '', $cls);
        if (in_array($base, self::STANDALONE, true)) {
            return true;
        }
        foreach (self::PREFIXES as $p) {
            if ($base === $p || str_starts_with($base, $p.'-')) {
                return true;
            }
        }

        return false;
    }

    private function isVariant(string $cls): bool
    {
        return preg_replace('/^([\w-]+:)+/', '', $cls) !== $cls;
    }

    private function groupClass(string $cls, string $base): string
    {
        if ($this->isVariant($cls)) {
            return 'Responsive & States';
        }
        if (preg_match('#^(container|block|inline(?:-block|-flex|-grid)?|flex|grid|hidden|visible|invisible|contents|flow-root|list-item|table(?:-\w+)?)$#', $base)) {
            return 'Layout';
        }
        if (preg_match('#^(flex|grid|items|justify|content|place|self|order|grow|shrink|basis|gap|col|row|auto-cols|auto-rows)(-|$)#', $base)) {
            return 'Flexbox & Grid';
        }
        if (preg_match('#^[mp][xytrblse]?-|^space-[xy]-#', $base)) {
            return 'Spacing';
        }
        if (preg_match('#^(w|h|min-w|max-w|min-h|max-h|size)(-|$)#', $base)) {
            return 'Sizing';
        }
        if (preg_match('#^(text|font|leading|tracking|whitespace|break|truncate|indent|uppercase|lowercase|capitalize|italic|underline|line-through|antialiased|decoration|list|prose)(-|$)|^(italic|not-italic|normal-case|antialiased|truncate|underline|overline|line-through|no-underline)$#', $base)) {
            return 'Typography';
        }
        if (preg_match('#^(bg|from|via|to|fill|stroke|accent|caret)(-|$)#', $base)) {
            return 'Colors & Backgrounds';
        }
        if (preg_match('#^(rounded|border|divide|outline|ring)(-|$)#', $base)) {
            return 'Borders & Rings';
        }
        if (preg_match('#^(shadow|opacity|blur|brightness|contrast|grayscale|hue-rotate|invert|saturate|sepia|backdrop|mix-blend|bg-blend|drop-shadow|filter)(-|$)#', $base)) {
            return 'Effects & Filters';
        }
        if (preg_match('#^(transition|duration|ease|delay|animate|scale|rotate|translate|skew|origin)(-|$)#', $base)) {
            return 'Transitions & Animation';
        }
        if (preg_match('#^(static|fixed|absolute|relative|sticky|inset|top|right|bottom|left|z|overflow|overscroll)(-|$)#', $base)) {
            return 'Positioning';
        }

        return 'Other';
    }
}
