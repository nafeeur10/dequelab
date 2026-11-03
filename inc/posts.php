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
    'shopify-b2b' => 'B2B',
    'marketing'   => 'Marketing',
    'apps'        => 'Apps',
    'guides'      => 'Guides',
];

/**
 * Banner palettes. A post picks one with cover.tone; the grid on /blogs then
 * reads as a run of distinct colours instead of three identical grey cards.
 */
const DQ_COVER_TONES = ['ink', 'moss', 'plum', 'clay', 'slate'];

/**
 * Every post, newest first.
 *
 * status   'live' renders a linked card; 'soon' renders an inert card with no
 *          href, so a listing can advertise what is coming without shipping a
 *          link to a 404.
 * category exactly one, and it must be a key of DQ_CATEGORIES. One primary
 *          category keeps /blogs sections free of the same card twice.
 * cover    drives the HTML banner. No image file: see dq_post_cover().
 *          'tone' paints the banner in one of the palettes in dq_post_cover()
 *          and is what gives the /blogs grid its run of distinct colours; a
 *          post with no tone falls back to the light paper banner.
 */
const DQ_POSTS = [
    [
        'slug'      => 'request-a-quote-shopify',
        'status'    => 'live',
        'category'  => 'shopify-b2b',
        'title'     => 'Shopify Request a Quote (RFQ): The Ultimate Guide for B2B Stores',
        'h1'        => 'Shopify Request a Quote (RFQ): The Ultimate Guide for B2B Stores',
        'excerpt'   => 'Add a Request a Quote button to Shopify. What an RFQ is, how the process works, the three ways to build it, and how to hide prices without losing SEO.',
        'card_text' => 'What an RFQ is, the four steps a quote takes on Shopify, the three ways to build one — custom Liquid, native B2B, or an app — how to hide prices safely, and how to turn an accepted quote into a draft order.',
        'published' => '2026-08-31',
        'modified'  => '2026-09-06',
        'read_time' => 20,
        'words'     => 4526,
        'tags'      => ['Request a Quote', 'RFQ', 'Shopify B2B', 'Hide Price', 'Shopify Apps'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'quote',
            'tone'    => 'slate',
            'eyebrow' => 'Guide · Shopify RFQ',
            'title'   => 'Shopify <b>Request a Quote</b> (RFQ)',
            'foot'    => 'Hide price → quote → order.',
        ],
    ],
    [
        'slug'      => 'shopify-b2b-starter-guide',
        'status'    => 'live',
        'category'  => 'shopify-b2b',
        'title'     => 'Shopify B2B: How to Start a Wholesale Business in 2026',
        'h1'        => 'Shopify B2B: How to Start a Wholesale Business in 2026',
        'excerpt'   => 'Shopify B2B runs on every paid plan since April 2026. What you get natively, which theme to pick, when apps become necessary, and how to win your first buyers.',
        'card_text' => 'What a B2B store is, which features Shopify gives you natively on every plan, which theme to pick, where apps become necessary, and how to find your first wholesale buyers.',
        'published' => '2026-08-03',
        'modified'  => '2026-08-25',
        'read_time' => 10,
        'words'     => 2006,
        'tags'      => ['Shopify B2B', 'Wholesale', 'B2B Ecommerce', 'Shopify Apps', 'B2B Marketing'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'tiers',
            'tone'    => 'ink',
            'eyebrow' => 'Guide · Shopify B2B',
            'title'   => 'How to start a <b>Shopify B2B</b> business',
            'foot'    => 'Setup → apps → first buyers.',
        ],
    ],
    [
        'slug'      => 'quote-form-that-converts',
        'status'    => 'soon',
        'category'  => 'marketing',
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
            'tone'    => 'moss',
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
            'tone'    => 'plum',
            'eyebrow' => 'Playbook · Invoicing',
            'title'   => 'From quote to paid in <b>24 hours</b>',
            'foot'    => 'Quote → invoice → paid.',
        ],
    ],
    [
        'slug'      => 'request-a-quote-shopify-without-app',
        'status'    => 'live',
        'category'  => 'shopify-b2b',
        'title'     => 'Shopify Request a Quote Without an App: Setup and Limitations',
        'h1'        => 'Shopify Request a Quote Without an App: Setup and Limitations',
        'excerpt'   => 'Add a Request a Quote button to Shopify without an app. Get Liquid code, setup steps, limitations, and guidance on when a quote app makes sense.',
        'card_text' => 'Build a product quote form with Shopify Liquid, keep product details in each request, and understand the manual work behind quotes, follow-ups, and draft orders.',
        'published' => '2026-09-10',
        'modified'  => '2026-09-10',
        'read_time' => 15,
        'words'     => 2547,
        'tags'      => ['Request a Quote', 'RFQ', 'Shopify B2B', 'Hide Price', 'Shopify Apps'],
        'author'    => ['name' => 'Nafeeur Rahman', 'role' => 'Founder, Deque Lab'],
        'cover'     => [
            'variant' => 'form',
            'tone'    => 'moss',
            'eyebrow' => 'Guide · No-app quoting',
            'title'   => 'Request a Quote <b>without an app</b>',
            'foot'    => 'Form → email → manual draft order.',
        ],
    ]
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
    // One of the palettes in styles.css (ink / moss / plum / clay). Unknown or
    // missing values fall through to the original light banner rather than
    // shipping a class with no rule behind it.
    $tone    = in_array($c['tone'] ?? '', DQ_COVER_TONES, true) ? (string) $c['tone'] : '';
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
    } elseif ($variant === 'quote') {
        // The whole article in one picture: the price is gone, and the button
        // that replaced it asks for a conversation instead of a card.
        $motif = '<div class="cv-card cv-quote">'
               . '<span class="cv-line w1"></span>'
               . '<span class="cv-lock">'
               . '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">'
               . '<rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>'
               . 'Price hidden</span>'
               . '<span class="cv-btn">Request a quote</span>'
               . '</div>';
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
    return '<div class="cover-art cv-' . e($variant) . ($tone !== '' ? ' toned tone-' . e($tone) : '') . '" aria-hidden="true">'
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
 *
 * $opts['layout']:
 *   'card'      (default) the boxed white card the homepage and the related
 *               row use — border, tag chip, "Read article →".
 *   'editorial' the /blogs listing: banner on top, then title, excerpt and a
 *               date · author line sitting directly on the page with no box
 *               around them. Same registry, same banner, different frame.
 */
function dq_post_card(array $p, array $opts = []): string {
    $editorial = ($opts['layout'] ?? 'card') === 'editorial';
    $live      = $p['status'] === 'live';
    $url       = dq_post_url($p);
    $tag       = DQ_CATEGORIES[$p['category']] ?? $p['category'];
    $stamp     = (int) strtotime($p['published']);

    $attrs = ' class="post' . ($editorial ? ' post-ed' : '') . '" data-cat="' . e($p['category']) . '"'
           . ' data-title="' . e(strtolower($p['title'] . ' ' . implode(' ', $p['tags'] ?? []))) . '"';

    $open  = $live ? '<a href="' . e((string) $url) . '"' . $attrs . '>' : '<article' . $attrs . '>';
    $close = $live ? '</a>' : '</article>';

    if ($editorial) {
        // Date then author, the two facts a reader uses to decide whether a
        // guide is still current and who stands behind it. A coming-soon post
        // has no date worth printing, so it says so instead of a future one.
        $author = $p['author']['name'] ?? 'Deque Lab';
        $when   = $live ? date('M j, Y', $stamp) : 'Coming soon';

        $body = '<h3>' . e($p['title']) . '</h3>'
              . '<p>' . e($p['card_text'] ?? $p['excerpt']) . '</p>'
              . '<div class="meta">'
              . '<span>' . e($when) . '</span>'
              . '<span class="sep" aria-hidden="true">|</span>'
              . '<span>' . e($author) . '</span>'
              . ($live ? '<span class="sep" aria-hidden="true">|</span><span>' . e((string) $p['read_time']) . ' min</span>' : '')
              . '</div>';
    } else {
        $body = '<div class="meta"><span>' . e($live ? date('M Y', $stamp) : 'Soon') . '</span>'
              . '<span class="tg">' . e($tag) . '</span>'
              . ($live ? '<span class="dot">·</span><span>' . e((string) $p['read_time']) . ' min</span>' : '')
              . '</div>'
              . '<h3>' . e($p['title']) . '</h3>'
              . '<p>' . e($p['card_text'] ?? $p['excerpt']) . '</p>'
              . '<span class="rd">' . ($live ? 'Read article →' : 'In progress') . '</span>';
    }

    return $open . dq_post_cover($p) . '<div class="body">' . $body . '</div>' . $close;
}
