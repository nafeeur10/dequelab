<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — post registry
 * One source of truth for every article on the site. The post
 * page, the /blogs index, the homepage row and the related
 * cards all read from here, so a title can never say one thing
 * in a listing and another on the article itself.
 * Requires inc/lib.php (for e()). Idempotent.
 * ============================================================ */

if (defined('DQLAB_POSTS')) {
    return;
}
define('DQLAB_POSTS', true);

/**
 * Categories, in the order their sections appear on /blogs.
 * Key is the filter value and the section id; value is the label.
 * Rename freely — tabs, section headings, filters and counts all
 * follow this one array.
 */
const DQ_CATEGORIES = [
    'shopify-b2b' => 'Shopify B2B',
    'guides'      => 'Guides',
    'apps'        => 'Apps & Tools',
];

/**
 * Every post, newest first.
 *
 * status   'live' renders a linked card; 'soon' renders an inert card with no
 *          href, so a listing can advertise what is coming without shipping a
 *          link to a 404.
 * category exactly one, and it must be a key of DQ_CATEGORIES. One primary
 *          category keeps /blogs sections free of the same card twice.
 * cover    drives the HTML banner. No image file: see dq_post_cover().
 */
const DQ_POSTS = [
    [
        'slug'      => 'shopify-b2b-wholesale-basic-plan',
        'status'    => 'live',
        'category'  => 'shopify-b2b',
        'title'     => 'Run B2B and wholesale business on Shopify Basic plan in 2026',
        'h1'        => 'Run B2B and wholesale business on Shopify Basic plan in 2026',
        'excerpt'   => 'Run wholesale on Shopify with no app at all. What native B2B covers, which theme to pick, the 3-catalog limit, and when an app finally earns its cost.',
        'card_text' => 'What native B2B covers on the cheapest paid plan, which theme to pick, the 3-catalog limit, and the point where an app finally earns its cost.',
        'published' => '2026-08-03',
        'modified'  => '2026-08-10',
        'read_time' => 7,
        'words'     => 1550,
        'tags'      => ['Shopify B2B', 'Wholesale', 'Basic Plan', 'Shopify Apps', 'Ecommerce'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'tiers',
            'eyebrow' => 'Guide · Shopify B2B',
            'title'   => 'Run B2B and wholesale on Shopify <b>Basic</b>',
            'foot'    => 'No Plus. No apps.',
        ],
    ],
    [
        'slug'      => 'quote-form-that-converts',
        'status'    => 'soon',
        'category'  => 'guides',
        'title'     => 'Designing a quote form that converts',
        'excerpt'   => 'The fields that move a browser to a serious lead — and the ones that just add friction.',
        'card_text' => 'The fields that move a browser to a serious lead — and the ones that just add friction.',
        'published' => '2026-09-01',
        'modified'  => '2026-09-01',
        'read_time' => 6,
        'tags'      => ['Quotes', 'Conversion'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'form',
            'eyebrow' => 'Guide · Quotes',
            'title'   => 'Designing a quote form that <b>converts</b>',
            'foot'    => 'Fewer fields, better leads.',
        ],
    ],
    [
        'slug'      => 'quote-to-paid-in-24-hours',
        'status'    => 'soon',
        'category'  => 'apps',
        'title'     => 'From quote to paid in 24 hours',
        'excerpt'   => 'The follow-up sequence, the invoice, and the payment link that close a wholesale order the same day.',
        'card_text' => 'The follow-up sequence, the invoice, and the payment link that close a wholesale order the same day.',
        'published' => '2026-09-15',
        'modified'  => '2026-09-15',
        'read_time' => 8,
        'tags'      => ['Invoicing', 'Playbook'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'invoice',
            'eyebrow' => 'Playbook · Invoicing',
            'title'   => 'From quote to paid in <b>24 hours</b>',
            'foot'    => 'Quote → invoice → paid.',
        ],
    ],
];

/**
 * Posts, newest published first. Live posts always sort above coming-soon ones
 * so a listing never opens with something the reader cannot click.
 *
 * @param string|null $category Filter to one DQ_CATEGORIES key.
 * @param bool        $live_only Drop coming-soon entries entirely.
 * @return array<int, array<string, mixed>>
 */
function dq_posts(?string $category = null, bool $live_only = false): array {
    $posts = array_values(array_filter(DQ_POSTS, static function (array $p) use ($category, $live_only): bool {
        if ($live_only && $p['status'] !== 'live') {
            return false;
        }
        return $category === null || $p['category'] === $category;
    }));

    usort($posts, static function (array $a, array $b): int {
        if (($a['status'] === 'live') !== ($b['status'] === 'live')) {
            return $a['status'] === 'live' ? -1 : 1;
        }
        return strcmp($b['published'], $a['published']);
    });

    return $posts;
}

/** One post by slug, or null. */
function dq_post(string $slug): ?array {
    foreach (DQ_POSTS as $p) {
        if ($p['slug'] === $slug) {
            return $p;
        }
    }
    return null;
}

/**
 * Categories that actually hold a post, in DQ_CATEGORIES order.
 * An empty tab is a dead end, so the UI never renders one.
 *
 * @return array<string, string> key => label
 */
function dq_categories_in_use(): array {
    $used = [];
    foreach (DQ_CATEGORIES as $key => $label) {
        if (dq_posts($key) !== []) {
            $used[$key] = $label;
        }
    }
    return $used;
}

/** Public URL for a post. Coming-soon posts have none. */
function dq_post_url(array $p): ?string {
    return $p['status'] === 'live' ? '/' . $p['slug'] : null;
}

/**
 * The post banner, drawn in HTML rather than shipped as an image: no request,
 * sharp at any size, and the text stays selectable and searchable. Sized in
 * cqw against the card, so one component serves the 3-up homepage row, the
 * /blogs grid and the related cards without a second art file.
 *
 * Decorative by design — aria-hidden, because the card's own <h3> already
 * announces the title and a screen reader should not hear it twice.
 */
function dq_post_cover(array $p): string {
    $c       = $p['cover'] ?? [];
    $variant = $c['variant'] ?? 'plain';
    $eyebrow = $c['eyebrow'] ?? '';
    $foot    = $c['foot'] ?? '';
    // Cover titles carry one <b> for the accented word, so they are written as
    // trusted markup here rather than escaped wholesale.
    $title   = $c['title'] ?? e($p['title']);

    $motif = '';
    if ($variant === 'tiers') {
        $rows = [['5+', '$59'], ['10+', '$57'], ['20+', '$56'], ['30+', '$55']];
        $motif = '<div class="cv-card cv-tiers">';
        foreach ($rows as $i => [$qty, $price]) {
            $hi = $i === count($rows) - 1 ? ' hi' : '';
            $motif .= '<span class="cv-row' . $hi . '"><i>' . e($qty) . '</i><b>' . e($price) . '</b></span>';
        }
        $motif .= '</div>';
    } elseif ($variant === 'form') {
        $motif = '<div class="cv-card cv-form">'
               . '<span class="cv-field"></span><span class="cv-field short"></span>'
               . '<span class="cv-field"></span><span class="cv-btn">Request a quote</span>'
               . '</div>';
    } elseif ($variant === 'invoice') {
        $motif = '<div class="cv-card cv-invoice">'
               . '<span class="cv-line w1"></span><span class="cv-line w2"></span>'
               . '<span class="cv-line w3"></span>'
               . '<span class="cv-total"><i>Paid</i><b>$1,121.00</b></span>'
               . '</div>';
    }

    // Title and motif share a flex row rather than the motif floating on top:
    // absolute positioning meant a headline one line longer than expected ran
    // underneath the card, and the collision only showed at certain widths.
    return '<div class="cover-art cv-' . e($variant) . '" aria-hidden="true">'
         . ($eyebrow !== '' ? '<span class="cv-eyebrow">' . e($eyebrow) . '</span>' : '')
         . '<div class="cv-main">'
         . '<span class="cv-title">' . $title . '</span>'
         . $motif
         . '</div>'
         . ($foot !== '' ? '<span class="cv-foot">' . e($foot) . '</span>' : '')
         . '</div>';
}

/**
 * One post card. A live post is an <a> so the whole card is one hit target;
 * a coming-soon post is an <article>, because nothing about it is clickable.
 * data-title and data-cat are what the /blogs search and tabs filter on.
 */
function dq_post_card(array $p): string {
    $live = $p['status'] === 'live';
    $url  = dq_post_url($p);
    $tag  = DQ_CATEGORIES[$p['category']] ?? $p['category'];
    $date = date('M Y', (int) strtotime($p['published']));

    $attrs = ' class="post" data-cat="' . e($p['category']) . '"'
           . ' data-title="' . e(strtolower($p['title'] . ' ' . implode(' ', $p['tags'] ?? []))) . '"';

    $open  = $live ? '<a href="' . e((string) $url) . '"' . $attrs . '>' : '<article' . $attrs . '>';
    $close = $live ? '</a>' : '</article>';

    return $open
         . dq_post_cover($p)
         . '<div class="body">'
         . '<div class="meta"><span>' . e($live ? $date : 'Soon') . '</span>'
         . '<span class="tg">' . e($tag) . '</span>'
         . ($live ? '<span class="dot">·</span><span>' . e((string) $p['read_time']) . ' min</span>' : '')
         . '</div>'
         . '<h3>' . e($p['title']) . '</h3>'
         . '<p>' . e($p['card_text'] ?? $p['excerpt']) . '</p>'
         . '<span class="rd">' . ($live ? 'Read article →' : 'In progress') . '</span>'
         . '</div>'
         . $close;
}
