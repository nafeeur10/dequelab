<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — Blog post (pillar / hub)
 * "Shopify Request a Quote (RFQ): The Ultimate Guide for B2B Stores"
 *
 * The hub for everything quote-related on this site. Written in
 * deliberately plain English: the reader is a merchant deciding how
 * to build a quote flow, not a developer reading documentation.
 * Runs in three parts — understand, build, operate — then closes.
 * ============================================================ */

require __DIR__ . '/inc/lib.php';
require __DIR__ . '/inc/views.php';
require __DIR__ . '/inc/posts.php';

// ---- Post metadata (drives markup + JSON-LD) ----
// Registry, not literals: /blogs, the homepage row and the related cards render
// from the same entry, so a title can never say one thing in a listing and
// another on the article. Edit inc/posts.php to change it.
$POST = dq_post('request-a-quote-shopify');

// Page-level SEO (overrides base config from lib.php).
// Three head terms share this page — "Shopify request a quote", "RFQ" and
// "Shopify B2B" — so all three sit in the title, where they carry weight,
// rather than only in the description, where they carry none.
$CONFIG['page_title']       = $POST['title'];
$CONFIG['page_description'] = $POST['excerpt'];
$CONFIG['page_keywords']    = 'shopify request a quote, request a quote, RFQ, shopify RFQ, shopify B2B, request for quotation, hide price shopify, shopify quote app, quote to draft order, B2B wholesale quotes';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/' . $POST['slug'];
// The lead illustration, re-cut to the 1200x630 share ratio. One picture for
// the page and the preview means they can never drift apart, and somebody who
// saw the card on LinkedIn recognises the article when they land on it.
// The artwork's ground is a flat #095c46 in all four corners, so letterboxing
// it to the wider ratio is invisible and nothing had to be cropped off the
// title or the logo. JPEG, not PNG: 86 KB against 1.5 MB.
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/og-request-a-quote-shopify.jpg';
$CONFIG['og_image_alt']     = 'Shopify Request a Quote (RFQ) — a storefront with a Request a Quote form beside a quote summary listing three products and a Submit Quote Request button.';

// Preselect the app this article is about in the shared contact form.
$contact_default_interest = 'DQ: B2B Request a Quote (RFQ)';
$contact_eyebrow = 'Selling on quotes?';
$contact_heading = 'Not sure quoting<br>is worth an app?';
$contact_lede    = 'Tell us what you sell and how often you negotiate. We will tell you honestly whether draft orders are enough, or which quoting app fits — usually within a business day.';

require __DIR__ . '/inc/contact-process.php';

// Running total for the "times read" meta. Returns 0 if unavailable — we hide it then.
$view_count = dq_views_record($POST['slug']);

// ---- Table of contents, grouped into the three parts of the job plus the
//      close. Inner keys are heading ids; values are both the TOC label and the
//      rendered <h2> text, so the sidebar and the headings can never drift. ----
$TOC_GROUPS = [
    'Understand' => [
        'summary'       => 'Summary',
        'what-is-rfq'   => 'What is request a quote (RFQ)?',
        'rfq-process'   => 'How request a quote process works on Shopify',
        'when-to-use'   => 'When Shopify B2B stores should use request a quote feature',
        'native'        => 'Does Shopify have a native request a quote feature?',
    ],
    'Build' => [
        'three-ways'    => 'Three ways to build request a quote system on Shopify',
        'comparison'    => 'Custom code vs. native vs. quote app',
        'choose-app'    => 'How to choose the right Shopify request a quote app',
        'must-have'     => '5 must-have features in a Shopify request a quote app',
        'hide-price'    => 'Hide Prices on Shopify without hurting SEO',
        'form-fields'   => 'What to include in a Shopify request a quote Form',
    ],
    'Operate' => [
        'negotiation'   => 'How quote negotiation works on Shopify',
        'convert'       => 'How to turn an accepted request a quote into a Shopify order',
        'best-practice' => 'Best practices to turn B2B quotes into paid orders',
        'mistakes'      => '5 mistakes that reduce Shopify B2B quote conversions',
    ],
    'Close' => [
        'cta'           => 'Ready to add request a quote to your Shopify store?',
        'faq'           => 'Frequently asked questions'
    ],
];

// Flat id => label map, so h2() below stays a simple lookup.
$TOC = array_merge(...array_values($TOC_GROUPS));

/** Render an <h2> from the TOC so heading text and anchor text always match. */
function h2(array $toc, string $id): string {
    return '<h2 id="' . e($id) . '">' . e($toc[$id] ?? $id) . '</h2>';
}

// ---- The four steps of a quote, on a real Shopify store. Deliberately not the
//      generic procurement version: every step names the Shopify screen it
//      happens on, because that is what a merchant is trying to picture. ----
$PROCESS_STEPS = [
    ['Step 1', 'The buyer asks',
     'On your product page the buyer sees a <b>Request a Quote</b> button instead of <b>Add to Cart</b>. You can hide the price too, so only the button shows.'],
    ['Step 2', 'The buyer fills the form',
     'A short form opens. The buyer picks a quantity and adds their company and email. They can add more products to the same quote before sending it.'],
    ['Step 3', 'You send a price',
     'The request lands in your Shopify admin. You set your price per line, add shipping and payment terms, and send the quote back.'],
    ['Step 4', 'The quote becomes an order',
     'The buyer accepts. The quote turns into a Shopify <b>draft order</b> with a checkout link. They pay, and it shows up in Orders like any other sale.'],
];

// ---- Custom code vs. native vs. app, feature by feature.
//      Each cell is [state, text]: 'y' tick, 'n' cross, 'w' warning, '' plain.
//      Data rather than hand-written rows, so the three columns can never fall
//      out of step with one another. ----
$COMPARE_COLS = ['Custom Liquid form', 'Native B2B (Shopify Plus)', 'Dedicated quote app'];

$COMPARE_ROWS = [
    'Upfront cost' => [
        ['', 'Low (developer time)'],
        ['', 'High ($2,500/mo Plus plan)'],
        ['', 'Low ($7–$37/mo)'],
    ],
    'Buyer-facing quote button' => [
        ['w', 'Only a plain link'],
        ['n', 'No'],
        ['y', 'Yes'],
    ],
    'Multi-product quote cart' => [
        ['n', 'No'],
        ['y', 'Yes'],
        ['y', 'Yes'],
    ],
    'Hide prices / login to view' => [
        ['w', 'Complex liquid'],
        ['y', 'Yes'],
        ['y', 'Yes, one click'],
    ],
    'Auto-convert to draft order' => [
        ['n', 'No, manual entry'],
        ['y', 'Yes'],
        ['y', 'Yes'],
    ],
    'Negotiation thread' => [
        ['n', 'No, email only'],
        ['n', 'No'],
        ['y', 'Yes'],
    ],
    'Quote expiry date' => [
        ['n', 'No'],
        ['n', 'No'],
        ['y', 'Yes, on most'],
    ],
    'Automated PDF quote' => [
        ['n', 'No'],
        ['n', 'No'],
        ['y', 'Yes'],
    ],
    'File upload (PO, drawings)' => [
        ['n', 'No'],
        ['n', 'No'],
        ['y', 'Yes, on most'],
    ],
    'Tracked as a conversion' => [
        ['n', 'No'],
        ['w', 'Partly'],
        ['y', 'Yes, GA4 and Pixel'],
    ],
    'Ease of setup' => [
        ['n', 'Needs coding'],
        ['w', 'Needs a full B2B migration'],
        ['y', 'Plug and play'],
    ],
];

/**
 * One comparison cell. The glyph is decorative — the words beside it are what a
 * screen reader announces, so a column never reads as a bare tick.
 */
function cmp_cell(array $cell): string {
    [$state, $text] = $cell;
    $mark = '';
    if ($state === 'y') {
        $mark = '<span class="sr-only">Yes:</span><span class="mk yes" aria-hidden="true">&#10003;</span> ';
    } elseif ($state === 'n') {
        $mark = '<span class="sr-only">No:</span><span class="mk no" aria-hidden="true">&#10007;</span> ';
    } elseif ($state === 'w') {
        $mark = '<span class="sr-only">Partly:</span><span class="mk warn" aria-hidden="true">!</span> ';
    }
    return $mark . e($text);
}

// ---- What native Shopify does and does not do for quoting.
//      true = built in, false = not, a string prints as-is. ----
$NATIVE_QUOTING = [
    'Build a quote with your own line items'        => [true,   'Draft orders'],
    'Lock the price on a sent quote'                => [true,   'Draft orders'],
    'Hold stock against a pending quote'            => [true,   'Draft orders'],
    'Send an invoice with payment terms'            => [true,   'Draft orders + B2B terms'],
    'The buyer sends a cart in for approval'        => [true,   'Checkout to draft'],
    'A Request a Quote button on the product page'  => [false,  'With custom coding'],
    'The price is hidden and a button replaces it'  => ['Half', 'Trade theme hides it, adds no button'],
    'A new lead can ask without a company account'  => [false,  'The company must exist first'],
    'Counter-offers in one thread'                  => [false,  'Email, outside Shopify'],
    'A quote that expires after 14 days'            => [false,  'You have to remember'],
    'Quote versions and history'                    => [false,  'You have to remember'],
    'A "my quotes" page in the customer account'    => [false,  'Not in customer accounts'],
    'Reporting on quotes won and lost'              => [false,  'Not in Shopify analytics'],
];

/** One availability cell for the native table. */
function native_cell($v): string {
    if ($v === true)  return '<span class="sr-only">Built in</span><span class="mk yes" aria-hidden="true">&#10003;</span>';
    if ($v === false) return '<span class="sr-only">Not built in</span><span class="mk no" aria-hidden="true">&#10007;</span>';
    return '<span class="mk-txt">' . e((string) $v) . '</span>';
}

// ---- Request a Quote apps on the Shopify App Store.
//      Ratings, review counts and prices were read off each listing on
//      31 August 2026. App pricing moves, so the article says so in print and
//      links every row to the listing rather than asking you to trust this.
//      Sorted by review count — the closest thing to a neutral order. ----
$RFQ_APPS = [
    [
        'name'   => 'QS Request a Quote, Hide Price',
        'by'     => 'Quote Snap',
        'url'    => 'https://apps.shopify.com/request-for-quote-by-omega',
        'icon'   => '/assets/app-request-for-quote-by-omega.png',
        'rating' => '4.8',
        'count'  => '685',
        'free'   => '10 / mo',
        'paid'   => '$16.99',
        'note'   => 'Live haggling. You can set a discount floor, and it shows your margin while you negotiate.',
    ],
    [
        'name'   => 'SA Request a Quote, Hide Price',
        'by'     => 'Samita',
        'url'    => 'https://apps.shopify.com/request-for-quote',
        'icon'   => '/assets/app-request-for-quote.png',
        'rating' => '4.9',
        'count'  => '619',
        'free'   => 'Form only',
        'paid'   => '$16.99',
        'note'   => 'Hide-price rules by tag, vendor or collection. Quote-to-order is on the $36.99 plan.',
    ],
    [
        'name'   => 'SB Request a Quote, Hide Price',
        'by'     => 'SetuBridge',
        'url'    => 'https://apps.shopify.com/request-a-quote-hide-price-sb',
        'icon'   => '/assets/app-sb-request-a-quote.jpg',
        'rating' => '4.7',
        'count'  => '191',
        'free'   => '50 / mo',
        'paid'   => '$7.99',
        'note'   => 'The biggest free plan. Buyers can also ask on WhatsApp, which helps if they do not use email.',
    ],
    [
        'name'   => 'S: Request a Quote &amp; Hide Price',
        'by'     => 'Storeify',
        'url'    => 'https://apps.shopify.com/storeify-request-a-quote',
        'icon'   => '/assets/app-storeify-request-a-quote.png',
        'rating' => '5.0',
        'count'  => '126',
        'free'   => 'Yes',
        'paid'   => '$12.99',
        'note'   => 'Quote expiry with reminder emails, built in. Rare at this price, and most stores need it.',
    ],
    [
        'name'   => 'Q: Request a Quote &amp; Hide Price',
        'by'     => 'AppHive',
        'url'    => 'https://apps.shopify.com/quotify',
        'icon'   => '/assets/app-q-request-a-quote.png',
        'rating' => '4.9',
        'count'  => '103',
        'free'   => 'Trial only',
        'paid'   => '$17',
        'note'   => 'PDF proposals, 16+ languages, and a quote funnel you can see in Google Analytics.',
    ],
    [
        'name'   => 'YouQuote: Request a Quote Form',
        'by'     => 'SolverCircle',
        'url'    => 'https://apps.shopify.com/request-a-quote',
        'icon'   => '/assets/app-youquote.png',
        'rating' => '5.0',
        'count'  => '30',
        'free'   => 'Dev only',
        'paid'   => '$14.99',
        'note'   => 'File upload on the form. Good if buyers send drawings or spec sheets. Few reviews so far.',
    ],
    [
        'name'   => 'CWS Request Quote &ndash; Hide Price',
        'by'     => 'Capacity Web Solutions',
        'url'    => 'https://apps.shopify.com/request-a-quote-hide-prices',
        'icon'   => '/assets/app-cws-request-quote.jpg',
        'rating' => '4.9',
        'count'  => '23',
        'free'   => 'Trial only',
        'paid'   => '$6.99',
        'note'   => 'The cheapest paid plan here, and it still makes draft orders. Light on negotiation.',
    ],
];

// ---- Sources. Rendered as a visible list at the foot of the article, because
//      a claim a reader cannot check is a claim they have to take on trust. ----
$SOURCES = [
    ['Shopify Blog — What is a request for quotation (RFQ)?', 'https://www.shopify.com/blog/request-for-quotation'],
    ['Shopify Help Center — Create and manage draft orders', 'https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders/create-draft'],
    ['Shopify Help Center — B2B features by plan', 'https://help.shopify.com/en/manual/b2b/getting-started/plan-features'],
    ['Shopify Themes — Trade', 'https://themes.shopify.com/themes/trade'],
    ['Amazon Business — Request for Quote', 'https://business.amazon.com/en/solutions/bulk-buying/request-for-quote'],
];

/**
 * Bulb that straddles the corner of a .callout-tip. Inline SVG rather than a
 * hotlinked file — no request, and the glass and base take their colours from
 * here. A function because more than one tip box on the page uses it.
 */
function tip_bulb(): string {
    return <<<SVG
        <svg class="tip-bulb" viewBox="0 0 64 64" aria-hidden="true" focusable="false">
          <g stroke="#e0b445" stroke-width="2.6" stroke-linecap="round">
            <line x1="32" y1="2.5" x2="32" y2="8.5" />
            <line x1="16.2" y1="7.2" x2="19.4" y2="12.6" />
            <line x1="47.8" y1="7.2" x2="44.6" y2="12.6" />
            <line x1="5.5" y1="19.5" x2="11.3" y2="22.6" />
            <line x1="58.5" y1="19.5" x2="52.7" y2="22.6" />
          </g>
          <path d="M32 10c-8.8 0-16 7-16 15.6 0 5.6 2.9 9.4 5.4 12.3 1.6 1.9 2.6 3.4 2.6 5.1v1.2h16V43c0-1.7 1-3.2 2.6-5.1 2.5-2.9 5.4-6.7 5.4-12.3C48 17 40.8 10 32 10z"
                fill="#f6d16b" stroke="#e0b445" stroke-width="1.4" />
          <path d="M28.6 24.6c1.3 2.7 1.5 6.6 1.3 9.9M35.4 24.6c-1.3 2.7-1.5 6.6-1.3 9.9"
                fill="none" stroke="#fffbe9" stroke-width="2" stroke-linecap="round" />
          <g fill="#3f4a52">
            <rect x="23" y="45.6" width="18" height="4.4" rx="2.2" />
            <rect x="23" y="51.4" width="18" height="4.4" rx="2.2" />
            <path d="M26.2 57.2h11.6v1.3c0 2-1.6 3.5-3.5 3.5h-4.6c-1.9 0-3.5-1.5-3.5-3.5v-1.3z" />
          </g>
        </svg>
        SVG;
}

$FAQS = [
    ['q' => 'Does Shopify have a built-in Request a Quote button?',
     'a' => 'No. Shopify has no Request a Quote button on the storefront. What it does have is draft orders, which let you build a quote in the admin, set your own prices, lock them, and send an invoice with payment terms. That is a real quoting tool, but you have to start it. The buyer cannot ask from the product page without custom code or an app.'],

    ['q' => 'What is an RFQ in ecommerce?',
     'a' => 'RFQ stands for request for quotation. It is a message a buyer sends to a seller asking for a price on a set list of goods. The buyer says what they need, how many, and when. The seller sends back a price and terms. RFQs are used when the buyer already knows what they want and is mainly comparing price.'],

    ['q' => 'How do I add a Request a Quote button to Shopify?',
     'a' => 'There are three ways. Install a Request a Quote app, which adds the button as a theme block with no code. Edit your theme code and swap the Add to Cart form for a contact form. Or, on Shopify Plus, use native B2B and let approved companies submit a cart for approval. Most stores use an app, because it also handles what happens after the button is clicked.'],

    ['q' => 'Can I hide prices on Shopify without an app?',
     'a' => 'Yes. The free Trade theme hides prices from visitors who are not logged in. On any other theme you can wrap the price and the Add to Cart button in a customer check in Liquid. Neither one puts a Request a Quote button in the empty space, so the visitor just sees a gap.'],

    ['q' => 'What is the best Request a Quote app for Shopify?',
     'a' => 'It depends on what you need. If you only want prices hidden and requests collected, almost any app from $7 to $17 a month will do it. If you negotiate — counter-offers, revised quantities, an approval that becomes an order — you need an app built around a quote thread. QS Request a Quote is the most complete, SB has the biggest free plan, and CWS is the cheapest that still makes draft orders.'],

    ['q' => 'Is there a free Shopify request a quote app?',
     'a' => 'Yes, several. The free plans are limited by how many quotes you get, not by features. SB gives you 50 quote requests a month, QS gives you 10, and Samita and Storeify both have a free tier around the basic form. A free plan is enough to prove buyers will use the button. It is rarely enough once they do.'],

    ['q' => 'Can a customer ask for a quote on a whole cart?',
     'a' => 'Yes, and you should let them. Most apps add a per-product quote button and a "request a quote for this cart" action. B2B buyers negotiate the order total, not one product. Making them send eleven separate requests for an eleven-line order is the fastest way to be ignored.'],

    ['q' => 'How do I turn a quote into an order on Shopify?',
     'a' => 'Turn the accepted quote into a draft order, set the agreed prices on each line, and send the invoice. The draft order holds the price, reserves stock, applies your payment terms, and becomes a normal order once it is paid. A good app does this for you in one click.'],

    ['q' => 'Do quote apps work with Shopify B2B companies and catalogs?',
     'a' => 'The better ones do. They read the company the buyer is logged in as, and start the quote from that company\'s catalog price instead of the retail price. Test this in the trial. An app that quotes off the public price can offer your best distributor a worse price than they already had.'],

    ['q' => 'Will hiding prices hurt my SEO?',
     'a' => 'It takes you out of Google Shopping and out of price snippets in search results. It does not remove your products from Google. Keep the product pages public, keep descriptions and specs visible, and hide only the number. If you sell anything where people compare prices before they click, do not hide it.'],
];

// ---- Structured data. Built as an array and json_encode'd so quotes and
//      apostrophes in the copy can never break the JSON. Every node here
//      describes something a visitor can actually see on the page. ----
$schema = [
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type' => 'Organization',
            '@id'   => $CONFIG['site_url'] . '/#organization',
            'name'  => $CONFIG['site_name'],
            'url'   => $CONFIG['site_url'] . '/',
            'logo'  => [
                '@type'  => 'ImageObject',
                'url'    => $CONFIG['site_url'] . $CONFIG['logo'],
                'width'  => 778,
                'height' => 778,
            ],
            'sameAs' => [$CONFIG['twitter'], $CONFIG['linkedin']],
        ],
        [
            '@type'            => 'BlogPosting',
            '@id'              => $CONFIG['canonical_url'] . '#article',
            'headline'         => $POST['title'],
            'description'      => $POST['excerpt'],
            'image'            => [
                '@type'  => 'ImageObject',
                'url'    => $CONFIG['og_image'],
                'width'  => 1200,
                'height' => 630,
            ],
            'datePublished'    => $POST['published'],
            'dateModified'     => $POST['modified'],
            'inLanguage'       => 'en',
            'wordCount'        => $POST['words'],
            'keywords'         => $CONFIG['page_keywords'],
            'articleSection'   => 'Shopify',
            'author'           => [
                '@type'    => 'Person',
                'name'     => $POST['author']['name'],
                'jobTitle' => $POST['author']['role'],
                'url'      => $CONFIG['site_url'] . '/',
            ],
            'publisher'        => ['@id' => $CONFIG['site_url'] . '/#organization'],
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $CONFIG['canonical_url']],
            // The sources block at the foot of the article, in machine-readable
            // form. Same list, same URLs — both render from $SOURCES.
            'citation'         => array_map(static fn(array $s): array => [
                '@type' => 'CreativeWork',
                'name'  => $s[0],
                'url'   => $s[1],
            ], $SOURCES),
        ],
        // Legitimate because the same ten questions render visibly in #faq — the
        // rule Google actually enforces is that marked-up content must be on the
        // page. Worth knowing what it does and does not buy: FAQ rich results
        // have been limited to government and health sites since 2023, so this
        // earns topical coverage and a shot at being quoted in AI answers, not
        // stars in the SERP.
        [
            '@type'      => 'FAQPage',
            '@id'        => $CONFIG['canonical_url'] . '#faq',
            'mainEntity' => array_map(static fn(array $f): array => [
                '@type'          => 'Question',
                'name'           => $f['q'],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
            ], $FAQS),
        ],
        // The four steps render as visible cards in #rfq-process. HowTo is the
        // honest type for them: they are ordered, and each one is an action.
        [
            '@type'       => 'HowTo',
            '@id'         => $CONFIG['canonical_url'] . '#howto',
            'name'        => 'How the RFQ process works on Shopify',
            'description' => 'The four steps a quote takes on a Shopify store, from the Request a Quote button to a paid order.',
            'step'        => array_map(static function (array $s, int $i): array {
                return [
                    '@type'    => 'HowToStep',
                    'position' => $i + 1,
                    'name'     => $s[1],
                    'text'     => strip_tags($s[2]),
                ];
            }, $PROCESS_STEPS, array_keys($PROCESS_STEPS)),
        ],
        [
            '@type'           => 'BreadcrumbList',
            '@id'             => $CONFIG['canonical_url'] . '#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $CONFIG['site_url'] . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => $CONFIG['site_url'] . '/blogs'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $POST['title']],
            ],
        ],
    ],
];

$share_url   = rawurlencode($CONFIG['canonical_url']);
$share_title = rawurlencode($POST['title']);
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?= e($CONFIG['page_title']) ?></title>
  <meta name="description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="keywords" content="<?= e($CONFIG['page_keywords']) ?>" />
  <meta name="author" content="<?= e($POST['author']['name']) ?>" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="theme-color" content="#0e1210" />
  <meta name="color-scheme" content="light" />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />
  <meta property="og:type" content="article" />
  <meta property="og:site_name" content="<?= e($CONFIG['site_name']) ?>" />
  <meta property="og:locale" content="<?= e($CONFIG['site_locale']) ?>" />
  <meta property="og:title" content="<?= e($POST['title']) ?>" />
  <meta property="og:description" content="<?= e($POST['excerpt']) ?>" />
  <meta property="og:url" content="<?= e($CONFIG['canonical_url']) ?>" />
  <meta property="og:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="<?= e($CONFIG['og_image_alt']) ?>" />
  <meta property="article:published_time" content="<?= e($POST['published']) ?>" />
  <meta property="article:modified_time" content="<?= e($POST['modified']) ?>" />
  <meta property="article:author" content="<?= e($POST['author']['name']) ?>" />
  <?php foreach ($POST['tags'] as $tag): ?>
  <meta property="article:tag" content="<?= e($tag) ?>" />
  <?php endforeach; ?>
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:creator" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:title" content="<?= e($POST['title']) ?>" />
  <meta name="twitter:description" content="<?= e($POST['excerpt']) ?>" />
  <meta name="twitter:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta name="twitter:image:alt" content="<?= e($CONFIG['og_image_alt']) ?>" />
  <link rel="icon" type="image/png" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="apple-touch-icon" href="<?= e($CONFIG['logo']) ?>" />
  <?php
    // One request, matching every other page: Lato sets the whole site, Geist
    // Mono handles eyebrows, counters and badges. display=swap, not optional —
    // Lato sets the prose, and a cold cache on optional would never show it.
    $font_css = 'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Geist+Mono:wght@400;500&display=swap';
  ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="<?= e($font_css) ?>" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) ?></script>

  <?php /* The article shell — hero, rail, prose scale, in-body components. */ ?>
  <link rel="stylesheet" href="/assets/post.css" />

  <style>
    /* ============================================================
     * Page-only additions. Everything else — rail, TOC, prose,
     * tables, callouts, FAQ — comes from post.css.
     * ============================================================ */

    /* ============================================================
     * Hero: one product page, mid-quote.
     *
     * The product card is the subject, so it sits alone in the middle and
     * takes the whole frame. The quote it belongs to peeks out from behind
     * it, and the four things an RFQ setup has to do pin to its four sides
     * instead of queueing in a column beside it.
     *
     * Everything is sized in cqw against .hero-art, so the whole composition
     * scales like an SVG and the geometry holds at any width.
     * ============================================================ */
    /* Overrides post.css, which lays the scene out as two columns. Same
       specificity, later in the cascade — the <style> block follows the
       stylesheet link, so these win. */
    .scene {
      display: grid; place-items: center;
      grid-template-columns: none;
      gap: 0;
    }
    /* The positioning context everything else hangs off. Not a flex column
       any more: the panel sets the box, and the rest is placed against it. */
    .scene .stage {
      position: relative;
      display: block;
      width: 90cqw;
    }

    /* ---- The quote list, beside the card ----
       Sits to the right, vertically centred against the product card, with
       only its left edge sliding behind it — about a quarter of its width.
       The overlap is what ties the two together: the quote is the same
       object as the product page, one step later.
       It was tucked under the bottom edge first, and disappeared completely
       behind the panel — a card nobody can see is not a hint, it is dead
       weight. */
    .scene .qlist {
      position: absolute; z-index: 1;
      top: 50%; right: 0;
      width: 36cqw;
      /* Extra left padding equal to the overlap, so what slides behind the
         card is the list's blank margin — never its text. */
      padding: 2.2cqw 2.4cqw 1.9cqw 6.5cqw;
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 2cqw;
      box-shadow: 0 1.6cqw 3.6cqw rgba(14, 18, 16, 0.13);
      transform: translateY(-50%) rotate(2deg);
      display: grid; gap: 1.5cqw;
    }
    .scene .ql-h {
      font-family: var(--f-mono); font-size: 1.7cqw; letter-spacing: 0.06em;
      text-transform: uppercase; color: var(--muted-2);
    }
    .scene .ql-row, .scene .qlist .ql-total {
      display: flex; align-items: center; justify-content: space-between; gap: 1.4cqw;
      font-size: 1.9cqw;
    }
    /* The product name is a grey bar rather than words: at this size real text
       would be unreadable, and a placeholder reads as "a line item" instantly. */
    .scene .ql-row i { flex: 1; height: 1.5cqw; border-radius: 0.8cqw; background: var(--paper-3); }
    .scene .ql-row b { color: var(--ink-2); font-weight: 500; font-family: var(--f-mono); }
    .scene .ql-total {
      margin-top: 0.4cqw; padding-top: 1.5cqw; border-top: 1px dashed var(--line);
    }
    .scene .ql-total i { font-style: normal; color: var(--muted); }
    .scene .ql-total b { color: var(--accent); font-weight: 700; font-family: var(--f-mono); }

    /* The card holds the left of the stage and stacks above the quote list,
       so the list's left edge disappears behind it rather than in front. */
    .scene .panel { position: relative; z-index: 2; width: 58cqw; }

    /* ---- The product page, mid-quote ----
       Product image left; on the right the four things that change when a
       product goes quote-only: the name stays, the price goes, the quantity
       stays, and the buy button becomes a Request a Quote button. */
    .scene .pv { display: grid; grid-template-columns: 36% 1fr; gap: 3cqw; align-items: center; }
    .scene .pv-img {
      aspect-ratio: 1;
      display: grid; place-items: center;
      background: var(--paper-2); border: 1px solid var(--line); border-radius: 1.8cqw;
    }
    .scene .pv-img svg { width: 86%; height: 86%; }
    .scene .pv-info { min-width: 0; display: flex; flex-direction: column; gap: 1.4cqw; }
    .scene .pv-name {
      font-size: 2.5cqw; font-weight: 700; color: var(--ink); letter-spacing: -0.01em; line-height: 1.15;
    }
    .scene .pv-price {
      display: flex; align-items: center; gap: 1.1cqw;
      font-size: 2cqw; color: var(--muted);
    }
    .scene .pv-price svg { width: 2.3cqw; height: 2.3cqw; flex: none; color: var(--muted-2); }
    /* The old price, struck through beside the lock — the swap reads instantly
       while the number it replaced is still visible. */
    .scene .pv-price s { color: var(--line-2); }
    .scene .pv-qty {
      display: inline-flex; align-items: center; gap: 2cqw; align-self: flex-start;
      padding: 0.7cqw 1.6cqw;
      border: 1px solid var(--line); border-radius: 1.2cqw;
      font-family: var(--f-mono); font-size: 1.9cqw; color: var(--ink-2);
    }
    .scene .pv-qty i { font-style: normal; color: var(--muted-2); }
    .scene .pv-btn {
      margin-top: 0.3cqw; padding: 1.6cqw 0; border-radius: 1.4cqw;
      background: var(--accent); color: var(--accent-ink);
      font-size: 2.2cqw; font-weight: 700; text-align: center; letter-spacing: -0.01em;
    }

    /* ---- The four chips ----
       A bare positioning layer over the stage, not the flex column post.css
       gives it. Each chip is anchored to one side and pulled outward by its
       own width, so it clears the card instead of covering it. */
    .scene .feats {
      position: absolute; inset: 0; z-index: 3;
      display: block; gap: 0;
    }
    .scene .chip {
      position: absolute;
      font-size: 1.95cqw; padding: 1.3cqw 1.9cqw; gap: 1.1cqw;
      border-radius: 2cqw;
      /* The float keyframes fold --dx in; zero it so the anchors below are the
         only thing deciding where a chip sits. */
      --dx: 0;
    }
    .scene .chip .ic { width: 4cqw; height: 4cqw; border-radius: 1.2cqw; }
    .scene .chip svg { width: 2.4cqw; height: 2.4cqw; }

    /* Top-left, top-right, bottom-left, bottom-right. The vertical offsets are
       larger than the horizontal ones because the card is wider than it is
       tall — this keeps every chip the same distance from the nearest edge. */
    .scene .c1 { top: -5cqw;    left: -4cqw; }
    .scene .c2 { top: -5cqw;    right: -4cqw; }
    .scene .c3 { bottom: -5cqw; left: -4cqw; }
    .scene .c4 { bottom: -5cqw; right: -4cqw; }

    /* ---- Executive summary ----
       A .callout carrying the whole article in one box, so a reader who came
       for the answer rather than the guide can leave with it. */
    /* .callout-tip hangs the bulb off the top-left corner and reserves 75px of
       top margin for it. As the first thing under an <h2> that is too much air,
       so the margin comes back down and the bulb tucks in slightly. */
    .post-body .callout.exec { margin: 46px 0 30px; padding: 26px 30px 22px 34px; }
    .post-body .callout.exec .tip-bulb { top: -30px; left: -24px; }
    .post-body .callout.exec p { font-size: 18px; }
    .post-body .callout.exec p:last-child { margin-bottom: 2px; }

    /* ---- The four RFQ steps, as cards ----
       A 2x2 grid, because four steps in one row are unreadable at this width
       and a plain list hides that they are a sequence. */
    .rfq-steps {
      list-style: none; margin: 30px 0 26px; padding: 0;
      display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
    }
    .rfq-steps > li {
      margin: 0; padding: 20px 22px 19px;
      background: #fff; border: 1px solid var(--line); border-radius: 14px;
    }
    .rfq-steps > li::before { content: none; }
    .rs-n {
      display: block; font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--accent); margin-bottom: 7px;
    }
    .rs-t { display: block; font-size: 18px; font-weight: 700; color: var(--ink); letter-spacing: -0.01em; }
    .rs-d { display: block; margin-top: 7px; font-size: 16px; line-height: 1.6; color: var(--muted); }
    .rs-d b { color: var(--ink-2); font-weight: 700; }
    @media (max-width: 700px) { .rfq-steps { grid-template-columns: 1fr; gap: 12px; } }

    /* ---- Comparison table ---- */
    /* Warning glyph, the third state beside .mk.yes and .mk.no. Amber rather
       than red: "possible, but painful" is not the same as "no". */
    .stable .mk.warn {
      display: inline-grid; place-items: center;
      width: 17px; height: 17px; border-radius: 50%;
      background: #fff4e5; color: #a45300;
      font-family: var(--f-mono); font-size: 11px; font-weight: 500;
    }
    .stable-3 { table-layout: fixed; }
    .stable-3 col.c-feat { width: 25%; }
    .stable-3 td { font-size: 15.5px; }



    /* Lists here are prose, not a numbered timeline: plain markers on the
       .checklist rhythm — 14px between items rather than post.css's 10px — so
       a run of two-line items reads as separate points instead of a block. */
    .post-body ul, .post-body ol { gap: 14px; }
    .post-body li { line-height: 1.6; }

    #summary {
      margin-top: 0!important;
    }

    .pl-0 {
      padding-left: 0!important;
    }

    /* ---- Related-article card ----
       Same shape as .author-box — paper-2 panel, hairline border, large
       radius — so the two horizontal blocks in the body read as one family.
       The accent lives in three places only: the icon disc, the eyebrow and
       the pill, which is what keeps it a signpost rather than a second CTA. */
    .rel-card {
      display: flex; align-items: center; gap: 20px;
      margin: 32px 0; padding: 22px 24px;
      background: white;
      border: 1px solid var(--line);
      border-radius: var(--r-lg);
    }
    .rel-icon {
      flex: none;
      width: 56px; height: 56px;
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%;
      background: color-mix(in oklch, var(--accent) 12%, white);
      color: var(--accent);
    }
    /* Same box as .rel-icon so the row geometry is identical; a squircle
       rather than a disc because it holds a square app icon, not a glyph. */
    .rel-logo {
      flex: none;
      width: 56px; height: 56px;
      border-radius: 14px; border: 1px solid var(--line);
    }
    .rel-body { flex: 1; min-width: 0; }
    .rel-k {
      display: block;
      font-family: var(--f-mono); font-size: 10.5px; letter-spacing: 0.13em;
      text-transform: uppercase; color: var(--accent);
    }
    /* Prefixed with .post-body, not bare: `.post-body a` in post.css is (0,1,1)
       and would beat a bare class at (0,1,0), repainting these links accent
       green — on the pill that means green label on green pill, invisible. */
    .post-body .rel-title {
      display: inline-block; margin: 6px 0 0;
      font-size: 20px; font-weight: 700; line-height: 1.3;
      letter-spacing: -0.01em; color: var(--ink);
      text-decoration: none;
      transition: color 140ms ease;
    }
    .post-body .rel-title:hover { color: var(--accent); }
    .post-body .rel-desc {
      margin: 0 0 0;
      font-size: 14.5px; line-height: 1.55; color: var(--muted);
    }
    .post-body .rel-btn {
      flex: none;
      display: inline-flex; align-items: center; gap: 7px;
      padding: 10px 20px; border-radius: 999px;
      background: var(--accent); color: var(--accent-ink);
      font-size: 14px; font-weight: 700; text-decoration: none;
      transition: background 140ms ease;
    }
    .post-body .rel-btn:hover { background: var(--accent-mid); color: var(--accent-ink); }
    .post-body .rel-btn:focus-visible { outline: 2px solid var(--ink); outline-offset: 2px; }
    .rel-btn svg { transition: transform 140ms ease; }
    .post-body .rel-btn:hover svg { transform: translateX(3px); }
    /* Stacks at the shared 560px breakpoint: the pill drops under the text
       and stays left-aligned with it rather than stretching full width. */
    @media (max-width: 560px) {
      .rel-card {
        display: grid; grid-template-columns: 56px 1fr;
        gap: 14px 16px; padding: 18px;
      }
      .post-body .rel-title { font-size: 18px; }
      .post-body .rel-btn { grid-column: 2; justify-self: start; }
    }

    .ul-mb-1 {
      margin: 0 0 5px!important;
    }

    .mt-2 {
      margin-top: 10px;
    }
  </style>
  <?php require __DIR__ . '/inc/crisp.php'; ?>
  <?php require __DIR__ . '/inc/analytics.php'; ?>
</head>
<body id="top">

<a href="#main" class="skip-link">Skip to content</a>

<!-- ============== NAV ============== -->
<header role="banner">
  <nav class="nav" aria-label="Primary">
    <div class="wrap nav-inner">
      <a href="/" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
        <span class="brand-mark">
          <img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" width="176" height="32" />
        </span>
      </a>
      <div class="nav-links">
        <a href="/#saas">SaaS</a>
        <a href="/#apps">Shopify Apps</a>
        <a href="/blogs" class="active">Blog</a>
        <a href="/#contact">Contact</a>
      </div>
      <div class="nav-actions">
        <a href="/#contact" class="btn btn-primary btn-sm nav-cta">Talk to us</a>
      </div>
    </div>
  </nav>
</header>

<main id="main">

<!-- ============== HERO ============== -->
<section class="post-hero" aria-labelledby="post-title">
  <div class="wrap">
    <div class="post-hero-grid">
      <div>
        <div class="eyebrow">Guide · Shopify RFQ</div>
        <h1 id="post-title"><?= e($POST['h1']) ?></h1>

        <div class="post-meta">
          <span class="m">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 11h18"/></svg>
            <?= e(date('j F Y', strtotime($POST['published']))) ?>
          </span>
          <?php if ($POST['modified'] !== $POST['published']): ?>
          <span class="m">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 12a9 9 0 1 0 3-6.7"/><path d="M3 4v5h5"/></svg>
            Updated <?= e(date('j F Y', strtotime($POST['modified']))) ?>
          </span>
          <?php endif; ?>
          <span class="m">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            <?= e((string) $POST['read_time']) ?> min read
          </span>
          <?php if ($view_count > 0): ?>
          <span class="m">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
            <?= e(number_format($view_count)) ?> read<?= $view_count === 1 ? '' : 's' ?>
          </span>
          <?php endif; ?>
        </div>
      </div>
      <?php /* Same scene component as the B2B guide, different contents: one
         product page mid-quote, the cubes spelling RFQ, and the chips naming
         the four things an RFQ setup has to do. */ ?>
      <div class="hero-art" aria-hidden="true">
        <div class="scene">
          <div class="stage">

            <?php /* The quote the buyer is building, sitting behind the product
               card. Deliberately half-covered: it is a hint of what happens
               after the button, not a second thing to read. */ ?>
            <div class="qlist">
              <span class="ql-h">Your quote</span>
              <span class="ql-row"><i></i><b>$120.00</b></span>
              <span class="ql-row"><i></i><b>$85.00</b></span>
              <span class="ql-row"><i></i><b>$95.00</b></span>
              <span class="ql-total"><i>Total</i><b>$300.00</b></span>
            </div>

            <div class="panel">
              <div class="panel-bar">
                <i></i><i></i><i></i>
                <span class="brandpill"><img src="/assets/dqlab.png" alt="" width="1346" height="244" decoding="async" /></span>
              </div>
              <div class="panel-body">
                <div class="pv">
                  <div class="pv-img">
                    <svg viewBox="0 0 260 240" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M150 44 L196 150" stroke="#a9743f" stroke-width="12" stroke-linecap="round"/>
                      <rect x="86" y="40" width="70" height="98" rx="12" fill="#727977" transform="rotate(-9 121 89)"/>
                      <rect x="78" y="120" width="108" height="36" rx="12" fill="#7f8583"/>
                      <path d="M80 150 L184 150" stroke="#b57e46" stroke-width="12" stroke-linecap="round"/>
                      <path d="M92 118 L172 108" stroke="#a9743f" stroke-width="10" stroke-linecap="round"/>
                      <path d="M96 118 L92 150" stroke="#a9743f" stroke-width="10" stroke-linecap="round"/>
                      <path d="M92 150 L78 214" stroke="#9a6a39" stroke-width="11" stroke-linecap="round"/>
                      <path d="M180 150 L206 214" stroke="#9a6a39" stroke-width="11" stroke-linecap="round"/>
                      <path d="M96 138 L182 138" stroke="#6a716f" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                  </div>
                  <div class="pv-info">
                    <div class="pv-name">Modern Lounge Chair</div>
                    <div class="pv-price">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>
                      Price hidden <s>$500</s>
                    </div>
                    <div class="pv-qty"><i>Qty</i> 24</div>
                    <div class="pv-btn">Request a quote</div>
                  </div>
                </div>
              </div>
            </div>

            <?php /* Four chips, one per side of the card. They pin to the stage
               rather than stacking in a column, so the product page stays the
               subject and the features read as things orbiting it. */ ?>
            <div class="feats">
              <span class="chip c1">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></span>
                Hide price
              </span>

              <span class="chip c2">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h4"/></svg></span>
                Quote form
              </span>

              <span class="chip c3">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a8 8 0 0 1-11.6 7.1L3 21l1.9-6.4A8 8 0 1 1 21 12z"/></svg></span>
                Negotiate
              </span>

              <span class="chip c4 amber">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2h9l5 5v15H6z"/><path d="M15 2v5h5M9.5 13h5M9.5 17h5"/></svg></span>
                Draft order
              </span>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== ARTICLE ============== -->
<div class="wrap mt-6">
  <div class="post-layout">

    <!-- ---------- Left rail ---------- -->
    <aside class="post-rail">
      <div class="rail-card">
        <div class="rail-author">
          <img src="/assets/author-112.png" width="52" height="52" alt="" loading="lazy" decoding="async" />
          <div>
            <div class="n"><?= e($POST['author']['name']) ?></div>
            <div class="r"><?= e($POST['author']['role']) ?></div>
          </div>
        </div>

        <hr class="rail-sep" />

        <nav aria-labelledby="toc-label">
          <div class="toc-head">
            <p class="rail-label" id="toc-label">Contents</p>
            <span class="toc-pct" id="toc-pct">0%</span>
          </div>
          <div class="toc-track" id="toc-bar" role="progressbar"
               aria-labelledby="toc-label" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
            <span></span>
          </div>

          <ul class="toc">
            <?php $gi = 0; foreach ($TOC_GROUPS as $group => $items): $gi++; ?>
            <li class="toc-group">
              <button type="button" class="toc-group-head" aria-expanded="true" aria-controls="tg-<?= $gi ?>">
                <span class="toc-group-n"><?= e(str_pad((string) $gi, 2, '0', STR_PAD_LEFT)) ?></span>
                <span class="toc-group-t"><?= e($group) ?></span>
                <span class="toc-group-meta"><?= count($items) ?></span>
                <svg class="toc-chev" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M6 9l6 6 6-6"/></svg>
              </button>
              <div class="toc-panel" id="tg-<?= $gi ?>">
                <ul class="toc-items">
                  <?php foreach ($items as $id => $label): ?>
                  <li><a href="#<?= e($id) ?>"><?= e($label) ?></a></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </nav>

        <hr class="rail-sep" />

        <p class="rail-label">Share</p>
        <div class="share">
          <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?= $share_url ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4.98 3.5a2.5 2.5 0 100 5 2.5 2.5 0 000-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-.95 1.83-1.95 3.75-1.95C20.4 8.75 21 11.1 21 14.1V21h-4v-6.1c0-1.45-.03-3.3-2.05-3.3-2.05 0-2.37 1.57-2.37 3.2V21H9z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?url=<?= $share_url ?>&amp;text=<?= $share_title ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on X">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.24 2.25h3.31l-7.23 8.26 8.5 11.24h-6.65l-5.22-6.82-5.96 6.82H1.67l7.73-8.84L1.25 2.25h6.82l4.71 6.23zm-1.16 17.52h1.83L7.01 4.13H5.05z"/></svg>
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>" target="_blank" rel="noopener noreferrer" aria-label="Share on Facebook">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22 12a10 10 0 10-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.33v6.99A10 10 0 0022 12z"/></svg>
          </a>
          <button type="button" id="copy-link" data-url="<?= e($CONFIG['canonical_url']) ?>" aria-label="Copy link to this article">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M10 13a5 5 0 007.07 0l2.83-2.83a5 5 0 00-7.07-7.07L11.5 4.4"/><path d="M14 11a5 5 0 00-7.07 0L4.1 13.83a5 5 0 007.07 7.07l1.3-1.3"/></svg>
          </button>
        </div>
      </div>
    </aside>

    <!-- ---------- Prose ---------- -->
    <article class="post-body">

      <!-- ========== PART 1 — UNDERSTAND ========== -->
      <?php /* The summary opens the article — before the lede, not after it.
         A reader who came for the answer should be able to take it and leave
         without scrolling. */ ?>
      <section>
        <?= h2($TOC, 'summary') ?>

        <div class="callout callout-tip exec">
          <?= tip_bulb() ?>
          <ul>
            <li>
              A <strong>Request a Quote</strong> feature on Shopify allows customers to negotiate
              pricing, ask for bulk discounts, or submit custom order requirements instead of
              immediately purchasing through a standard <strong>Add to Cart</strong> button.
            </li>
            <li>
              While Shopify does not natively offer a comprehensive quote request button on standard
              plans, merchants can implement this workflow using custom Liquid code, Shopify Plus B2B
              features, or dedicated third-party apps.
            </li>
            <li>
              The most efficient method for scalability and conversion tracking is using a specialized
              Shopify quote app that automatically converts approved quotes into <strong>Draft
              Orders</strong>.
            </li>
          </ul>
        </div>

        <p>
          Most Shopify B2B buyers do not want to click <strong>Add to Cart</strong>. They want a price for
          their order — their quantity, their delivery date, their terms.
        </p>

        <p>
          The rest of this guide is the long version. You will learn what a Request a quote (RFQ) is, how the process
          works on Shopify, the three ways to build it, which apps are worth paying for, and how to
          turn an accepted quote into a paid order.
        </p>

        <figure class="lead-art">
          <img src="/assets/rfq.webp" width="1536" height="1024"
               alt="Shopify Request a Quote (RFQ) — a storefront with a Request a Quote form beside a quote summary listing three products and a Submit Quote Request button."
               loading="lazy" decoding="async" />
        </figure>
      </section>

      <section>
        <?= h2($TOC, 'what-is-rfq') ?>

        <p>
          A <em>request for quotation</em>, or <em>request a quote</em>, is commonly abbreviated as <strong>RFQ</strong>. 
          It is a message that a buyer sends to
          sellers asking for a price on a specific list of goods or services.
        </p>

        <p>
          The buyer says exactly what they need, how many units, what quality, when they need it.
          Each seller sends back a price. The buyer compares the offers and picks one or negotiate.
        </p>

        <p>Shopify puts it like this:</p>

        <blockquote>
          <p>
            A request for quotation (RFQ) is a document that solicits detailed pricing and terms for
            specific products and services from a selection of potential suppliers or service
            providers. An RFQ outlines a business’s needs and provides suppliers with clear
            instructions on how and when to respond, ensuring a standardized process for evaluating
            price quotes.
          </p>
        </blockquote>

        <p>
          Amazon Business runs the same idea inside its store. A buyer submits their requirements
          once and gets competing bids back from many sellers, instead of emailing every vendor
          one at a time.
        </p>

        <p>
          Use an RFQ when you already know what you need, and price is the main thing you are
          comparing. It works best when the quantity and the quality are fixed, and only the number
          is open.
        </p>

        <div class="callout callout-tip">
          <?= tip_bulb() ?>
          <p>
            <span class="tip-k">Quick difference: RFQ vs RFP</span><br />
            Use an RFQ (Request for Quotation) when you know exactly what you want and 
            just need the lowest price. Use an RFP (Request for Proposal) 
            when you have a goal or problem and need an expert to suggest the best solution.
          </p>
        </div>
      </section>

      <section>
        <?= h2($TOC, 'rfq-process') ?>

        <p>
          On a Shopify store, a quote takes four steps. Every one of them happens on a screen you
          already have.
        </p>

        <ul class="rfq-steps pl-0">
          <?php foreach ($PROCESS_STEPS as [$n, $title, $desc]): ?>
          <li>
            <span class="rs-n"><?= e($n) ?></span>
            <span class="rs-t"><?= e($title) ?></span>
            <span class="rs-d"><?= $desc ?></span>
          </li>
          <?php endforeach; ?>
        </ul>

        <p>
          Step 4 matters most. If the quote does not become a Shopify draft order, somebody has to
          retype it. That person will get it wrong one day, and it will be on your biggest order.
        </p>
      </section>

      <section>
        <?= h2($TOC, 'when-to-use') ?>

        <p>
          If you run a Shopify B2B, wholesale, or custom manufacturing store, pushing buyers through a
          normal retail checkout costs you sales. Big orders, heavy items, and made-to-order
          products all need a conversation first.
        </p>

        <p>A quote flow fixes four common problems:</p>

        <ul>
          <li><strong>Volume and tiered pricing:</strong> 
            When wholesale orders require negotiated rates based on quantity, 
            order history, or specific B2B contracts rather than flat pricing.
          </li>
          <li><strong>Bulk shipping:</strong> The cost of shipping machinery, pallets or bulk material often cannot be worked out automatically at checkout.</li>
          <li><strong>Custom made products:</strong>
           If items require bespoke work—such as custom manufacturing, 
           branded merchandise, or tailored dimensions—merchants must 
           evaluate the exact scope before assigning a precise price.
          </li>
          <li><strong>High-ticket items:</strong> 
            or expensive physical goods or complex service packages, 
            corporate buyers typically require a direct conversation or 
            formal proposal for procurement approval before committing funds.
          </li>
        </ul>

        <?php /* Signpost to the B2B guide, not a CTA — the pill is the only
                 loud thing in it and it points at another article. */ ?>
        <div class="rel-card">
          <span class="rel-icon" aria-hidden="true">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
              <path d="M14 2v6h6" /><path d="M8 13h8" /><path d="M8 17h6" />
            </svg>
          </span>
          <div class="rel-body">
            <span class="rel-k">Related article</span>
            <a class="rel-title" href="/shopify-b2b-starter-guide">Shopify B2B Starter Guide</a>
            <p class="rel-desc">Learn how to set up a Shopify B2B store step by step.</p>
          </div>
          <a class="rel-btn" href="/shopify-b2b-starter-guide">
            Read the guide
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" />
            </svg>
          </a>
        </div>

      </section>

      <section>
        <?= h2($TOC, 'native') ?>

        <p>
          There is no Shopify Request a Quote button on the storefront. Customers cannot make their own quotes. 
          Instead, store owners must create the quote for them in the admin dashboard using the <strong>Draft Order</strong> feature.
        </p>

        <p>Here is the honest line-by-line.</p>

        <div class="stable-wrap">
          <table class="stable stable-marks">
            <caption class="sr-only">Which parts of a quoting workflow Shopify handles natively</caption>
            <thead>
              <tr>
                <th scope="col">Can Shopify do it?</th>
                <th scope="col">Native</th>
                <th scope="col">Where it lives</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($NATIVE_QUOTING as $capability => [$state, $where]): ?>
              <tr>
                <th scope="row"><span class="ft"><?= e($capability) ?></span></th>
                <td><?= native_cell($state) ?></td>
                <td><?= e($where) ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p class="src">
          Source:
          <a href="https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders/create-draft" target="_blank" rel="noopener noreferrer">Shopify Help Center — Creating and managing draft orders</a>
        </p>

        <p>
          <span style="background-color: #FFE6E6; padding: 0 5px; border-left: 1px solid red;">Here is the problem:</span> Shopify gives tools for store owners to make quotes, 
          but nothing for customers to ask for quotes on the website. 
          Shopify thinks you already know the customer and what price to charge them. 
          This is true for old customers, but it does not work for new ones.
        </p>
      </section>

      <!-- ========== PART 2 — BUILD ========== -->
      <section>
        <?= h2($TOC, 'three-ways') ?>

        <p>
          There are three ways to put quote functionality on your storefront. Here is what each one
          gives you, and what it costs you.
        </p>

        <h3>The native route: Shopify B2B</h3>
        <p>
          On a Shopify Plus plan ($2,500+/month) you get native B2B. That means company profiles,
          per-company price lists, and payment terms. You can also set a company so its buyers
          submit a cart for approval instead of checking out.
        </p>
        <p>
          <strong>The catch:</strong> it handles wholesale <em>pricing</em> well, but its quote
          negotiation is rigid next to a dedicated app. It suits established clients who already
          know your prices, not new leads asking for a custom number.
        </p>

        <h3>The developer route: custom Liquid and a contact form</h3>
        <p>
          If you want something free and light, edit your theme. Make an alternate product template.
          Comment out the <code class="mono">{% form 'product' %}</code> tag and put an HTML contact
          form in its place, with <code class="mono">{{ product.title }}</code> in a hidden field.
        </p>
        <p>
          <strong>The catch:</strong> you get an email. Nothing more. It does not create a draft
          order. The buyer cannot build one quote from several products. And you retype everything
          by hand to invoice them later.
        </p>

        <h3>The app route: a dedicated quote app</h3>
        <p>
          A dedicated Shopify Request a Quote app is what most merchants use. These apps drop into your
          existing theme as blocks. They handle the boring parts: routing requests, building PDFs,
          and talking to Shopify’s Draft Orders API.
        </p>
        <p>
          <strong>The catch:</strong> a monthly bill, and one more app running inside your product
          page. Both are small next to the alternative.
        </p>
      </section>

      <section>
        <?= h2($TOC, 'comparison') ?>

        <p>Side by side, the three routes look like this.</p>

        <div class="stable-wrap">
          <table class="stable stable-3">
            <caption class="sr-only">Custom Liquid form, native B2B on Shopify Plus, and a dedicated quote app compared feature by feature</caption>
            <colgroup>
              <col class="c-feat" /><col /><col /><col />
            </colgroup>
            <thead>
              <tr>
                <th scope="col">Feature</th>
                <?php foreach ($COMPARE_COLS as $col): ?>
                <th scope="col"><?= e($col) ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($COMPARE_ROWS as $feature => $cells): ?>
              <tr>
                <th scope="row"><span class="ft"><?= e($feature) ?></span></th>
                <?php foreach ($cells as $cell): ?>
                <td><?= cmp_cell($cell) ?></td>
                <?php endforeach; ?>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="callout callout-tip">
          <?= tip_bulb() ?>
          <p>
            <span class="tip-k">In short:</span>
            Custom code is the cheapest to start and the most expensive to keep. Native B2B is
            powerful, but only if you are already on Plus. An app is the only route that gives you
            the button <em>and</em> everything that happens after it.
          </p>
        </div>
      </section>

      <section>
        <?= h2($TOC, 'choose-app') ?>

        <p>
          If you sell B2B or wholesale products on Shopify, 
          fixed prices do not always work. You often need a "Request a Quote" app so buyers can ask for custom pricing.
        </p>

        <p>
          However, picking the wrong app can break your store design or slow down your website. 
          Here are the four key areas to check before choosing a quote app for your store.
        </p>

        <?php /* Four areas as plain h3s rather than one long list: each one
                 carries its own sub-points, so it needs a heading of its own. */ ?>
        <h3>How well it fits with your Shopify store</h3>

        <p>
          A good app should fit smoothly into your Shopify store without causing technical problems.
        </p>

        <ul>
          <li>
            <strong>No code setup:</strong> 
            The app should use <strong>Theme App Blocks</strong>. 
            This means you can add or remove the quote button easily inside your Shopify Theme Editor without editing liquid code.
          </li>
          <li>
            <strong>Creates draft orders:</strong> 
            When you accept a quote, the app should automatically turn it into a <strong>Shopify Draft Order</strong>. 
            This keeps your taxes, shipping, inventory, and invoices inside Shopify.
          </li>
          <li>
            <strong>Shopify B2B Compatible: </strong> 
            If you use Shopify B2B features, the app must respect your B2B company accounts and special price lists.
          </li>
        </ul>

        <h3>Website Speed</h3>

        <p>
          A slow website costs you sales. Some apps add heavy code that makes product pages load slowly.
        </p>

        <ul>
          <li>
            <strong>Speed Testing: </strong> 
            Before installing an app permanently, 
            run a speed test on your product page (using tools like Google Lighthouse).
          </li>
          <li>
            <strong>Lightweight Code: </strong> 
            Check your speed again after installing the app. 
            If the quote button makes your page significantly slower, look for a lighter app.
          </li>
        </ul>

        <h3>Buyer experience and customization</h3>

        <p>
          Your request a quote form should be easy for buyers to fill out and flexible for your business needs.
        </p>

        <ul>
          <li>
            <strong>Smart price-hiding rules:</strong> 
            You should be able to hide prices for guests while keeping them visible for logged-in wholesale buyers.
          </li>
          <li>
            <strong>Price negotiation: </strong>
            Smart B2B customers don't like negotiate via chat or meeting. They like to negotiate via website.
          </li>
          <li>
            <strong>Custom form fields: </strong> 
            Look for a drag-and-drop form builder so you can ask for details like company name, tax ID, or preferred delivery dates.
          </li>
          <li>
            <strong>File uploads: </strong> 
            Shopify B2B buyers often need to send purchase orders, 
            product drawings, or design files. Ensure the form allows PDF and image uploads.
          </li>
          <li>
            <strong>Multi-currency: </strong> 
            If you sell internationally, the quote form must work with Shopify Markets and support local currencies.
          </li>
        </ul>

        <p>
          <span style="background-color: #e6ffe9; padding: 0 5px; border-left: 1px solid green;">
            Final tip before you buy:
          </span> 
          Always use the app's free trial on your real theme. 
          Send a test quote to yourself and open the email on your mobile phone. 
          Your buyers will judge your business by how that quote email looks!
        </p>
      </section>

      <section>
        <?= h2($TOC, 'must-have') ?>

        <p>
          If you go the app route, do not settle for a fancy contact form. A good quote app works
          like a small sales CRM. Look for these five things.
        </p>

        <ul>
          <li>
            <strong>Targeted request a quote widget: </strong> 
            <ul class="ul-mb-1">
              <li class="mt-2">Show on all products</li>
              <li>Show on specific products</li>
              <li>Exclude specific products</li>
              <li>Show only to specific customers (like B2B or wholesale buyers)</li>
            </ul>
          </li>
          <li>
            <strong>Dynamic price hiding & custom forms: </strong> 
            <ul class="ul-mb-1">
              <li class="mt-2">Hide prices on all products, specific items, collections, or tags.</li>
              <li>
                Hide prices from guest visitors and show a <strong>Login to see price</strong> message. 
                Only logged-in buyers can see the real price.
              </li>
              <li>Replace Add to Cart and Buy Now button.</li>
              <li>Drag and drop form builder.</li>
            </ul>
          </li>
          <li>
            <strong>Price negotiation loop & order conversion: </strong> 
            <ul class="ul-mb-1">
              <li class="mt-2">Request a quote offer and counter-offer.</li>
              <li>After accepted quote, it will automatically convert to draft order.</li>
            </ul>
          </li>
          <li>
            <strong>Professional emails & PDF invoices: </strong> 
            <ul class="ul-mb-1">
              <li class="mt-2">Get every status of quote via emails.</li>
              <li>Brand trust via own email server option.</li> 
              <li>Every mail should have option to set company and client details.</li>
            </ul>
          </li>
          <li>
            <strong>Multi-language & multi-currency setup: </strong>
            <ul class="ul-mb-1">
              <li class="mt-2">Form and button should be translatable.</li>
              <li>Currency setup.</li>
            </ul>
          </li>
        </ul>
      </section>

      <section>
        <?= h2($TOC, 'hide-price') ?>

        <p>There are three ways to hide prices on Shopify. Each has good and bad points.</p>

        <ul class="ul-mb-1">
          <li class="mt-2">Use Shopify’s free <strong>Trade</strong> theme
              <ul class="ul-mb-1">
                <li class="mt-2"><strong>Best for:</strong> Stores that only sell wholesale.</li>
                <li><strong>The Good:</strong> It is free. It hides prices from guest visitors automatically.</li>
                <li>
                  <strong>The Bad:</strong> 
                  It just leaves an empty space where the price was. 
                  There is no "Request a Quote" button for buyers to click.
                </li>
              </ul>
          </li>
          <li>Edit the theme code yourself
            <ul class="ul-mb-1">
              <li class="mt-2"><strong>Best for:</strong> Developers.</li>
              <li><strong>The Good:</strong> It is free and you can make exactly what you want.</li>
              <li>
                <strong>The Bad:</strong> 
                Your custom code will break the next time you update your Shopify theme.
              </li>
            </ul>
          </li>
          <li>Use a Request a Quote app
            <ul class="ul-mb-1">
              <li class="mt-2"><strong>Best for:</strong> Most store owners.</li>
              <li><strong>The Good:</strong> 
                It replaces the hidden price with a nice "Request a Quote" button so you get leads. 
                You can easily choose which products hide their prices.
              </li>
              <li>
                <strong>The Bad:</strong> 
                It costs a monthly fee.
              </li>
            </ul>
          </li>
        </ul>

        <h3>Adjust Google & AI Search during hide prices on Shopify</h3>
        <p>Many store owners ruin their Google or AI ranking when they hide prices. Follow these simple rules to stay safe:</p>

        <ul class="ul-mb-1">
          <li class="mt-2">
            <strong>Keep your product details visible: </strong> 
            Let everyone read about the product without logging in. 
            Include its size, materials, features, and uses. 
            These details help buyers and search engines understand what you sell.
          </li>

          <li>
            <strong>Add a “Request a Quote” button: </strong> 
            Give buyers a clear way to ask for a price. 
            Add a short message beside the button, such as: “Need a price for your order? Request a quote.”
          </li>

          <li>
            <strong>Keep Google able to find your pages: </strong> 
            If you want a product to appear in search, do not hide the whole page behind a login. 
            Also, avoid adding a “noindex” setting. This tells Google to remove the page from search results. 
            <a href="https://developers.google.com/search/docs/crawling-indexing/block-indexing" target="_blank" rel="noopener noreferrer">Google's noindex guide</a>
          </li>

          <li>
            <strong>Use accurate price information: </strong> 
            Do not use $0 or a made-up price to mean “ask for a quote.” 
            Ask your developer to check the product information your theme or app sends to Google. 
            It should match what visitors can see on the page. 
            <a href="https://developers.google.com/search/docs/appearance/ai-features" target="_blank" rel="noopener noreferrer">Google's guidance</a>
          </li>

          <li>
            <strong>Check before using Google Shopping: </strong> 
            Google Shopping ads and free product listings need a clear price that matches your product page and checkout. 
            Products that only show “Request a Quote,” with no public price, generally do not meet these requirements. 
            They can still appear in regular Google Search.
            <a 
            href="https://support.google.com/merchants/answer/6324371" 
            target="_blank" 
            rel="noopener noreferrer">Google’s price requirements</a>
          </li>
        </ul>
      </section>

      <section>
        <?= h2($TOC, 'form-fields') ?>

        <p>
          A Shopify request a quote form should collect the product, quantity, and buyer’s email address. 
          Add other fields only when you need them to prepare the quote.
        </p>

        <p>
          Keep the form short and easy to fill out. 
          Buyers should not have to enter details that are already in their cart.
        </p>

        <div class="stable-wrap">
          <table class="stable">
            <caption class="sr-only">Which fields belong on a Shopify quote request form</caption>
            <thead>
              <tr><th scope="col">Field</th><th scope="col">Should you include it?</th><th scope="col">Why</th></tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row"><span class="ft">Product and variant</span></th>
                <td><span>Yes — add automatically if possible</span></td>
                <td>Shows which product, size, or color the buyer wants.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Quantity</span></th>
                <td><span>Yes</span></td>
                <td>Helps you calculate the total price and any bulk discount.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Email address</span></th>
                <td><span>Yes</span></td>
                <td>Lets you send the quote and ask questions.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Company name</span></th>
                <td><span>For business buyers</span></td>
                <td>Helps you know which business is asking for a quote.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Delivery date</span></th>
                <td><span>If needed</span></td>
                <td>It changes the price on anything made to order, and tells you which quotes to answer first.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Country or postcode</span></th>
                <td><span>If you quote shipping costs</span></td>
                <td>Helps you calculate delivery charges.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">File upload</span></th>
                <td><span>For custom orders</span></td>
                <td>Lets buyers share designs, images, or other files.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Message box</span></th>
                <td><span>Optional</span></td>
                <td>Gives buyers space to explain special needs.</td>
              </tr>
              <tr>
                <th scope="row"><span class="ft">Phone number</span></th>
                <td><span>Optional</span></td>
                <td>Lets buyers share a number if they want a call.</td>
              </tr>
            </tbody>
          </table>
        </div>

        <p>
          You can usually ask for the billing address after the buyer accepts the quote. 
          Leave out extra questions, such as “How did you hear about us?”, unless you have a clear reason to ask.
        </p>

        <p>Let buyers request a quote for several products at once. This makes it easier to ask for a price for the whole order.</p>
      </section>

      <!-- ========== PART 3 — OPERATE ========== -->
      <section>
        <?= h2($TOC, 'negotiation') ?>

        <p>
          A quote negotiation is rarely finished on the first try. 
          Most B2B deals require two or three steps. 
          To win the sale, you must keep the whole conversation in one system instead of using scattered emails.
        </p>

        <ul>
          <li>
            <strong>The buyer requests a quote:</strong><br>
            The buyer sends their product choice, quantity, and company details. 
            Always send a quick reply within an hour to confirm you received it, even if calculating the final price takes more time.
          </li>
          <li>
            <strong>You send your offer:</strong><br>
            Send a complete and clear price. 
            You must include the unit price, shipping costs, taxes, payment rules, and an expiration date for the offer.
          </li>
          <li>
            <strong>The buyer asks for changes:</strong><br>
            The buyer might ask for a lower price or offer to buy more items for a discount. 
            Keep this new message in the exact same app or thread. Do not start a new email chain.
          </li>
          <li>
            <strong>You update the quote:</strong><br>
            Review the request and send a new offer if you can meet it. 
            Keep messages and earlier quotes together so both sides can follow the changes.
          </li>
          <li>
            <strong>The buyer accepts the quote:</strong><br>
            Once both sides agree, create the order using the agreed details. 
            Depending on your Shopify request a quote app, you may be able to turn the accepted quote into a draft order or send a checkout link.
          </li>
        </ul>

        <p>
          Set your lowest price before you start. Know how much you need to cover your costs and make a profit. 
          If your app supports a minimum price rule, use it to help prevent discounts that are too large.
        </p>
      </section>

      <!-- Convert -->
      <section>
        <?= h2($TOC, 'convert') ?>

        <p>
          Turning an accepted quote into a real order is the most important step. A basic contact form cannot do this.
          You can turn an accepted quote into a Shopify order using a draft order. 
          Add the agreed products and prices, then send the buyer an invoice with a checkout link.
        </p>

        <ul>
          <li>
            <strong>Create a draft order:</strong><br>
            Go to your Shopify dashboard. Open <strong>Orders &rarr; Drafts &rarr; Create order</strong> 
            and add the products and quantities the buyer requested for.
          </li>
          <li>
            <strong>Set the agreed prices:</strong><br>
            Enter the price for each product. 
            Check that shipping, taxes, and the final total match the agreement.
          </li>
          <li>
            <strong>Lock the product prices:</strong><br>
            Use Lock product pricing to keep the quoted prices, 
            even if you change your store prices later. 
            Price locks do not support product bundles.
          </li>
          <li>
            <strong>Reserve stock if needed:</strong><br>
            Hold the items for the buyer and choose when the hold ends. 
            This stops other customers from buying that stock during the hold.
          </li>
          <li>
            <strong>Set the payment deadline:</strong><br>
            Choose when the buyer must pay. For example, Net 30 means payment is due within 30 days. 
            <a 
              href="https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders/create-draft" 
              target="_blank" 
              rel="noopener noreferrer"
            >Shopify’s draft order guide.
            </a>
          </li>
          <li>
            <strong>Send the invoice:</strong><br> 
            The buyer receives an email with a checkout link. When they pay, the draft becomes an order in Shopify. 
            If you allow payment later, the order can be created before payment is received. 
            <a 
              href="https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders/send-draft" 
              target="_blank" 
              rel="noopener noreferrer"
            >Shopify’s invoice guide.
            </a>
          
          </li>
        </ul>

        <p>Before sending, check the final total against the accepted quote. The buyer should see the price they agreed to pay.</p>

        <div class="callout callout-tip">
          <?= tip_bulb() ?>
          <p>
            <span class="tip-k">The Smart Shortcut: Use an App</span><br>
            If you find the perfect "Request a Quote" app like <a href="https://apps.shopify.com/dlab-request-quote-hide-price" target="_blank" rel="noopener noreferrer">DQ: B2B Request a Quote (RFQ)</a>, it will do all of these six steps automatically for you.
          </p>
        </div>
      </section>

      <!-- Best practices -->
      <section>
        <?= h2($TOC, 'best-practice') ?>

        <p>
          Getting a quote button on your website is just the beginning. 
          How you handle the request decides if you win the sale. 
          Here are the proven rules to convert more quotes into orders.
        </p>

        <ul>
          <li>
            <strong>Respond within one business hour:</strong><br>
            The first response is the most important. 
            Buyers compare prices from multiple vendors at this time. 
            A fast reply helps you win the sale.
          </li>

          <li>
            <strong>Keep the form short:</strong><br>
            Ask only for what you need: Name, Email, Company, and a Message. 
            Long forms make buyers leave.
          </li>

          <li>
            <strong>Confirm the request and give a reply time:</strong><br>
            The moment a buyer clicks submit, email them a copy of their request. 
            Add a simple line saying: "We will reply with a firm price within 2 business hours."
          </li>

          <li>
            <strong>Send clear, detailed proposals:</strong><br>
            Show product prices, shipping, taxes, delivery time, and payment terms. 
            Include a clear date for how long the offer is valid.
          </li>

          <li>
            <strong>Use the "3-7-14" Follow-Up rule:</strong><br>
            Send a friendly reminder on day 3, a reminder of the deadline on day 7, and a final yes-or-no question on day 14.
          </li>

          <li>
            <strong>Quote accept or decline:</strong><br>
            Give the buyer a clear way to accept the quote and complete the order, never ask the buyer to re-type their order.
            When they accept the quote, your system should turn it into a Shopify Draft Order with one click. 
            Send them a direct link so they can simply review and pay.
          </li>
        </ul>
      </section>

      <!-- Mistakes -->
      <section>
        <?= h2($TOC, 'mistakes') ?>

        <p>These mistakes can make it harder for buyers to request a quote and place an order.</p>

        <ul>
          <li>
            <strong>Hiding prices from everyone: </strong><br>
            If you sell to both retail and b2b or wholesale buyers, keep retail prices visible. 
            Hide prices only for products or buyers that need a custom quote.
          </li>
          <li>
            <strong>Hiding simple bulk discounts: </strong><br> 
            Do not use a quote form for basic bulk discounts (like "Buy 10, get 10% off"). 
            If you already know the discount, just show it on the product page. 
            Only use quotes for real custom pricing and large negotiations.
          </li>
          <li>
            <strong>No reply time and no expiration date: </strong><br> 
            Tell buyers when they will receive a reply and how long the quote is valid. 
            This helps them plan their next step.
          </li>
          <li>
            <strong>Replying too slowly: </strong><br> 
            Buyers may compare several sellers. 
            Confirm their request quickly and send the full quote within the time you promised.
          </li>
          <li>
            <strong>Typing orders manually:</strong><br> 
            After a buyer accepts, carry the agreed products, quantities, and prices into a Shopify draft order. 
            Check the details before sending the checkout link.
          </li>
        </ul>
      </section>

      <!-- ========== CLOSE ========== -->
      <section>
        <?= h2($TOC, 'cta') ?>

        <p>
          With <a href="/dq-b2b-request-quote-rfq">DQ: B2B Request a Quote (RFQ)</a>, you can hide prices, add a quote form, 
          and let buyers suggest a price. 
          Manage requests in one place and turn accepted quotes into Shopify draft orders in one click. 
          No coding needed.
        </p>

        <div class="rel-card">
          <img class="rel-logo" src="/assets/app-dlab-request-quote-hide-price.png" width="56" height="56"
               alt="DQ: B2B Request a Quote (RFQ) app icon" loading="lazy" decoding="async">
          <div class="rel-body">
            <span class="rel-k">Shopify app</span>
            <a class="rel-title" href="/dq-b2b-request-quote-rfq">DQ: B2B Request a Quote (RFQ)</a>
            <p class="rel-desc">Add a Request a Quote button and hide prices on your Shopify store.</p>
          </div>
          <a class="rel-btn" href="/dq-b2b-request-quote-rfq">
            See the app
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
              <line x1="5" y1="12" x2="19" y2="12" /><polyline points="12 5 19 12 12 19" />
            </svg>
          </a>
        </div>
      </section>

      <section>
        <?= h2($TOC, 'faq') ?>

        <div class="faq">
          <?php foreach ($FAQS as $i => $faq): ?>
          <details<?= $i === 0 ? ' open' : '' ?>>
            <summary><?= e($faq['q']) ?></summary>
            <div class="a"><?= e($faq['a']) ?></div>
          </details>
          <?php endforeach; ?>
        </div>
      </section>

    </article>
  </div>
</div>

<!-- ============== RELATED ============== -->
<section id="blog" aria-labelledby="related-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Keep reading</div>
        <h2 id="related-title" style="margin-top:14px">Related guides</h2>
      </div>
      <div class="eyebrow">More on B2B &amp; quote-based selling</div>
    </div>
    <div class="blog">
      <a class="post" href="/dq-b2b-request-quote-rfq">
        <?php
          // Built by the same renderer as every other cover rather than by hand,
          // so a change to the component reaches this card too.
          echo dq_post_cover([
              'title' => 'Hide prices and collect quote requests on Shopify',
              'cover' => [
                  'variant' => 'form',
                  'tone'    => 'clay',
                  'eyebrow' => 'Shopify app · Deque Lab',
                  'title'   => 'Hide the price, collect a <b>quote</b>',
                  'foot'    => 'Free plan · $9/month',
              ],
          ]);
        ?>
        <div class="body">
          <div class="meta"><span>App</span><span class="tg">B2B</span></div>
          <h3>Hide prices and collect quote requests on Shopify</h3>
          <p>Replace the price and add-to-cart button with a quote form, then approve or counter each request from your Shopify admin.</p>
          <span class="rd">View app →</span>
        </div>
      </a>
      <?php
        // Every other post, newest first — the registry is the source, so a new
        // article appears here the moment it is added, with no edit to this file.
        foreach (array_slice(array_filter(dq_posts(), static fn(array $p): bool =>
            $p['slug'] !== $POST['slug']), 0, 2) as $rel) {
            echo dq_post_card($rel);
        }
      ?>
    </div>
  </div>
</section>

<!-- ============== CONTACT ============== -->
<?php
  $contact_show_socials  = true;
  $contact_section_style = 'padding-top:0';
  require __DIR__ . '/inc/contact-section.php';
?>

</main>

<!-- ============== FOOTER ============== -->
<footer class="site-footer" role="contentinfo">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-col">
        <a href="/" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
          <span class="brand-mark"><img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" width="221" height="40" /></span>
        </a>
        <p style="color:#8a8d85; font-size:14px; margin-top:18px; max-width: 34ch;">
          A small product lab. We build SaaS and Shopify apps for teams that care about craft.
        </p>
      </div>
      <nav class="footer-col" aria-label="SaaS products">
        <h2>SaaS</h2>
        <ul>
          <li><a href="/#saas-alliances">Alliances PRO</a></li>
          <li><a href="/#saas-planner">Muslim Daily Planner</a></li>
          <li><a href="/#saas">All SaaS products</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Shopify apps">
        <h2>Shopify Apps</h2>
        <ul>
          <li><a href="/dq-b2b-request-quote-rfq">B2B Request a Quote (RFQ)</a></li>
          <li><a href="/#app-appointment">Appointment Booking</a></li>
          <li><a href="/#apps">All Shopify apps</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Connect with us">
        <h2>Connect</h2>
        <ul>
          <li><a href="mailto:<?= e($CONFIG['contact_email']) ?>"><?= e($CONFIG['contact_email']) ?></a></li>
          <li><a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
          <li><a href="<?= e($CONFIG['twitter']) ?>" target="_blank" rel="noopener noreferrer">X / Twitter</a></li>
        </ul>
      </nav>
    </div>
    <div class="footer-meta">
      <span>© <?= e(date('Y')) ?> <?= e($CONFIG['site_name']) ?></span>
      <span class="footer-legal">
        <a href="/privacy">Privacy Policy</a>
        <span aria-hidden="true">·</span>
        <span>Built in-house · v1.0</span>
      </span>
    </div>
  </div>
</footer>

<script>
(function () {
  // ---- TOC scroll-spy: highlight the section currently being read ----
  var links = Array.prototype.slice.call(document.querySelectorAll('.toc a'));
  var heads = links
    .map(function (a) { return document.getElementById(a.getAttribute('href').slice(1)); })
    .filter(Boolean);

  var groups  = Array.prototype.slice.call(document.querySelectorAll('.toc-group'));
  var article = document.querySelector('.post-body');
  var bar     = document.getElementById('toc-bar');
  var pctEl   = document.getElementById('toc-pct');

  var openGroup = function (g, on) {
    g.classList.toggle('collapsed', !on);
    var head = g.querySelector('.toc-group-head');
    if (head) head.setAttribute('aria-expanded', on ? 'true' : 'false');
  };

  // Clicking a heading opens/closes just that group, without disturbing the rest.
  // A later scroll that moves into a different group re-syncs everything.
  groups.forEach(function (g) {
    var head = g.querySelector('.toc-group-head');
    if (head) head.addEventListener('click', function () { openGroup(g, g.classList.contains('collapsed')); });
  });

  if (heads.length) {
    var activeIndex = -1;

    var setActive = function (i) {
      if (i === activeIndex) return;
      var groupChanged = activeIndex < 0 ||
        links[i].closest('.toc-group') !== links[activeIndex].closest('.toc-group');
      activeIndex = i;

      // Sections above the current one read as done; the rest stay untouched.
      links.forEach(function (a, n) {
        a.classList.toggle('active', n === i);
        a.classList.toggle('read', n < i);
      });

      var currentGroup = links[i].closest('.toc-group');
      groups.forEach(function (g) {
        var isCurrent = g === currentGroup;
        var last = g.querySelector('.toc-items li:last-child a');
        g.classList.toggle('current', isCurrent);
        g.classList.toggle('done', !isCurrent && !!last && last.classList.contains('read'));
        // Only collapse others when the reader actually crosses into a new group,
        // so a manually opened group is not yanked shut on every scroll tick.
        if (groupChanged) openGroup(g, isCurrent);
      });
    };

    // The active section is simply the last heading scrolled past. Read live
    // geometry each time rather than caching intersection state — cached state
    // goes stale on jump-scrolls, where headings never fire a crossing event.
    var update = function () {
      var i = 0;
      for (var n = 0; n < heads.length; n++) {
        if (heads[n].getBoundingClientRect().top <= 130) i = n;
        else break;
      }
      setActive(i);

      if (article && bar) {
        // 0% when the article top reaches the top of the viewport,
        // 100% when its last line reaches the bottom.
        var box   = article.getBoundingClientRect();
        var span  = box.height - window.innerHeight;
        var pct   = span > 0 ? Math.round((-box.top / span) * 100) : (box.top <= 0 ? 100 : 0);
        pct = Math.max(0, Math.min(100, pct));
        bar.firstElementChild.style.width = pct + '%';
        bar.setAttribute('aria-valuenow', String(pct));
        if (pctEl) pctEl.textContent = pct + '%';
      }
    };

    var queued = false;
    var onScroll = function () {
      if (queued) return;
      queued = true;
      requestAnimationFrame(function () { queued = false; update(); });
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll, { passive: true });
    update();
  }

  // ---- Copy link ----
  var btn = document.getElementById('copy-link');
  if (btn) {
    var original = btn.innerHTML;
    btn.addEventListener('click', function () {
      var url = btn.getAttribute('data-url') || location.href;
      var done = function () {
        btn.classList.add('copied');
        btn.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg>';
        setTimeout(function () { btn.classList.remove('copied'); btn.innerHTML = original; }, 1800);
      };
      if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(url).then(done).catch(function () {});
      } else {
        var t = document.createElement('textarea');
        t.value = url; document.body.appendChild(t); t.select();
        try { document.execCommand('copy'); done(); } catch (e) {}
        document.body.removeChild(t);
      }
    });
  }
})();
</script>

<?php if ($form_sent): ?>
<script>
  if (location.hash !== '#contact') location.hash = '#contact';
</script>
<?php endif; ?>

</body>
</html>
