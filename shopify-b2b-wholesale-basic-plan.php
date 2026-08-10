<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — Blog post
 * "Shopify B2B on Basic Plan: Run Wholesale Without Plus"
 * ============================================================ */

require __DIR__ . '/inc/lib.php';
require __DIR__ . '/inc/views.php';
require __DIR__ . '/inc/posts.php';

// ---- Post metadata (drives markup + JSON-LD) ----
// Read from the registry, not declared here: /blogs, the homepage row and the
// related cards render from the same entry, so a title can never say one thing
// in a listing and another on the article. Edit inc/posts.php to change it.
//
// Worth knowing about the numbers in there: title and h1 are deliberately the
// same string, because Google asks that the schema headline match the visible
// headline. read_time and words are counted from the rendered body, not
// estimated — both are public (one on the page, one as schema wordCount), so
// an inflated guess is a claim we cannot back up.
$POST = dq_post('shopify-b2b-wholesale-basic-plan');

// Page-level SEO (overrides base config from lib.php).
// Keeps the brand suffix under ~60 chars total or Google truncates it in the SERP.
// "Basic" and "without Plus" are the terms this page competes on — the slug
// already targets them, so the title has to carry them too.
$CONFIG['page_title']       = 'Shopify B2B on Basic: Run Wholesale Without Plus · Deque Lab';
$CONFIG['page_description'] = $POST['excerpt'];
$CONFIG['page_keywords']    = 'Shopify B2B, Shopify Basic plan B2B, wholesale store Shopify, Shopify wholesale apps, Shopify B2B vs Plus, Shopify B2B catalogs, Trade theme, request a quote Shopify';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/' . $POST['slug'];
// Purpose-built 1200x630 card, 60 KB. The old share image was the 1254x1254
// app shot at 1.1 MB: square, so every platform centre-cropped it, and heavy
// enough that some scrapers gave up and showed no preview at all.
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/og-shopify-b2b-basic.png';
$CONFIG['og_image_alt']     = 'Run B2B and wholesale on Shopify Basic — volume pricing tiers from $59 at 5+ down to $55 at 30+.';

// Preselect the closest waitlist in the shared contact form.
$contact_default_interest = 'DQ: Wholesale Pricing (waitlist)';
$contact_eyebrow = 'Building something like this?';
$contact_heading = 'Setting up wholesale<br>on Shopify?';
$contact_lede    = 'Tell us what you sell and which plan you are on. We will tell you honestly whether native B2B covers it, or which app is worth the money — usually within a business day.';

require __DIR__ . '/inc/contact-process.php';

// Running total for the "times read" meta. Returns 0 if unavailable — we hide it then.
$view_count = dq_views_record($POST['slug']);

// 50x50 Shopify bag mark, inlined so a 1.2 KB icon costs no extra request.
// Kept up here rather than in the prose so the paragraph stays readable.
const SHOPIFY_MARK = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEWklEQVR4nO2ZfcxXYxjHPxH1ZB60WDXUX8rb1oomCiGb5qUllYzI/GEIG1vqSYQwf3hZ9EeReawX/rGa/lGUWKjGZpW3vE57eCpEReRn1/Y9272zc+5z3+d3fvXL+m7Xfr9zn+u6zvU9L9d93dcNh/H/x8XACqAD+By4C+jCIYaHgX+BWko+BTYCs4BjaHJMV9B/A23AycB7GaTWAS00KS4B9gP/AGM09pwCT57Q18CX+v8YTYgW4FsF+KjGrnGeziT97gNGitguoAdNhvsV9Fci1Qps09g90lmr48uAt/V/CTBB5wcd7ITQAnQqsNEae0jHnwBHaOwFjRmJ75zvxTKa4RnpdDlIPJisgLYoiBOA3zQ2ChgCPA58n/HRm0yRnwU6ngecDfQFuh1IIskrc6+O79PxXuCbnOBdGS+7JTnnVx2IDNdbmeovZapngZ0BwbuSvI7LPTpjG0miKzAzMugsuUj+3vHoTKs6+COB4brzHRWQqOkbMqz36LxYFQFLje3ArxUF78oAXWOzR+fdqoh82AACiVgZgyer1TQnVYJGPIlEjtM1ipLEsfWS6NlAEiZH6Tr7CvQG1UtkcERQXwCzgQuBU1WunwicB8xRveXq/6lrHB3g+7p6iVwbcBGb/O5QSvZlvB0pu+0RT316vUSSmTpP/gAuCPAzMsPWKmbDKQFEFtZL5PmCC9wY6Gduhu0mnTs9gMjaeoms8Dj/KNCHVcA/ZthbWjecG0Cko14ivokqqVxdWDq9CZgKXK4K9vwceyvpk2ZFSDJpLUvCyvI9Hsf9MmxWp3Q6ndVjWpbJ5spAIoPLEunjcbpfmSiNxYFBmSySzcRA/QllieS9EjU1GSz/p9HNWSQVyXzZ3Bqo31aWyKTAgi8LtwfM1k9L9+5AIi+XJdJW4NjWIz6MzZjNXbEqwDAjkMj7ZYkUvSI2U/cv8PFgwIJpTiCRn8sSWRXgfLNTihd1WdJyZ6qRFyLHlyGyNdC5rSUGevy8mmN3s86/FEHknFgSXQs+1vQa5SfgpBxfSZ8rr6JdGkHk+lgi/T3OtmodMUMdlGR8XI6v2Tl+rtD5NyOIzIwlklWtJmJdwQT95Hyu5/1dmONnhM6viSDySiyRWzzOrINYxbeWlBwbIoisiyWS9zrszpnR8zDKE9Rp0vksgkhnLJF2T41lRV4IemjNkReU9XcNP0QQqam/HIysnSaXzCMFPdmezvZBUQfll0giQ2OIZC2EshY7T6l/O1ANh2FKt9sD7JPq2VfG1DLkhlAS3XXXaw2UPc61Ym1nhRIZ0GASbt3Uq4RtewyRrC3mKsX2TpJ5KNb2AyIwTL2k17U3WDUx23c3nFHCdgd1oFUz8VQVeR8HLJxC7urQErarqRhWWJ6prontmbwVmK1MVjr780W624DXtJIc7vSLGw6b6K5SGl7ubFW78oZ0r06N/6457An5sN5xU6GPqt0HdHef1PilelVvA85ytrMPg0Md/wHVpDbiyN4oLwAAAABJRU5ErkJggg==';

/**
 * Inline brand link: small logo + label that behave as one word.
 * Icon is decorative (alt=""), so screen readers announce just the label.
 */
function brand_link(string $href, string $label, string $icon = SHOPIFY_MARK): string {
    return '<a class="brand-link" href="' . e($href) . '" target="_blank" rel="noopener">'
         . '<img src="' . $icon . '" alt="" width="20" height="20" loading="lazy" decoding="async">'
         . '<span>' . e($label) . '</span></a>';
}

// ---- Table of contents, grouped into four reading stages. Inner keys are
//      heading ids; values are both the TOC label and the rendered <h2> text,
//      so the sidebar and the headings can never drift apart. ----
$TOC_GROUPS = [
    'Start here' => [
        'what-changed'    => 'Native B2B Features in Shopify for All Plans',
        'basic-vs-plus'   => 'Basic vs Plus: the real differences',
    ],
    'Choosing themes' => [
        'theme'           => 'Which theme should you choose?',
    ],
    'Choosing apps' => [
        'essential-apps'  => 'Apps you genuinely need',
        'nice-apps'       => 'Apps that are nice to have'
    ]
];

// Flat id => label map, so h2() below stays a simple lookup.
$TOC = array_merge(...array_values($TOC_GROUPS));

/** Render an <h2> from the TOC so heading text and anchor text always match. */
function h2(array $toc, string $id): string {
    return '<h2 id="' . e($id) . '">' . e($toc[$id] ?? $id) . '</h2>';
}

// ---- B2B features by plan, transcribed from Shopify's own plan-features page:
//      help.shopify.com/en/manual/b2b/getting-started/plan-features
//      Grouped exactly as Shopify groups them. true = included, false = not,
//      a string prints as-is. Order of plans matches $B2B_PLANS. ----
$B2B_PLANS = ['Basic', 'Grow', 'Advanced', 'Plus'];

$B2B_PLAN_FEATURES = [
    'Companies and customers' => [
        'Companies'                                                     => [true, true, true, true],
        'Company locations'                                             => [true, true, true, true],
        'Location-level permissions (location admin and ordering only)' => [true, true, true, true],
    ],
    'Catalogs and products' => [
        'Number of B2B catalogs (assignable to B2B markets)'            => ['3', '3', '3', 'Unlimited'],
        'Direct company catalogs (assigned to a company or location)'    => [false, false, false, true],
        'Quantity rules (minimum, maximum, and increment)'              => [true, true, true, true],
        'Quantity price breaks'                                         => [true, true, true, true],
    ],
    'Payments' => [
        'Net terms'                                                     => [true, true, true, true],
        'Payment reminders'                                             => [true, true, true, true],
        'Automated Clearing House (United States only)'                 => [true, true, true, true],
        'Vaulted credit cards'                                          => [true, true, true, true],
        'Deposit requirements (companies)'                              => [false, false, false, true],
        'Deposit requirements (draft orders)'                           => [false, false, false, true],
        'Partial payments'                                              => [false, false, false, true],
        'Payment requests per fulfillment'                              => [false, false, false, true],
    ],
    'Checkout and orders' => [
        'Draft order to invoice'                                        => [true, true, true, true],
        'Checkout to draft'                                             => [true, true, true, true],
        'Easy reorders'                                                 => [true, true, true, true],
        'PO numbers'                                                    => [true, true, true, true],
        'Contextual checkout (using Shopify Markets)'                   => [false, false, true, true],
    ],
    'Sales staff' => [
        'Sales staff permissions'                                       => [true, true, true, true],
    ],
    'Storefront' => [
        'Trade theme'                                                   => [true, true, true, true],
        'Quick order list'                                              => [true, true, true, true],
        'Contextual storefront (using Shopify Markets)'                 => [false, false, true, true],
    ],
    'Complementary B2B features' => [
        'Shopify Flow (using B2B objects)'                              => [true, true, true, true],
    ],
];

/**
 * One availability cell. The glyph is decorative — the word beside it is what
 * a screen reader announces, so the column never reads as a bare tick. Plain
 * numbers stay in body ink; anything wordier ("Unlimited") is the exception
 * worth colouring.
 */
function plan_cell($v): string {
    if ($v === true)  return '<span class="sr-only">Included</span><span class="mk yes" aria-hidden="true">&#10003;</span>';
    if ($v === false) return '<span class="sr-only">Not included</span><span class="mk no" aria-hidden="true">&#10007;</span>';
    $s = (string) $v;
    return ctype_digit($s) ? e($s) : '<span class="mk-txt">' . e($s) . '</span>';
}

/**
 * Feature label, split at the first parenthetical so the qualifier drops to a
 * quieter second line instead of padding out the column.
 */
function feature_label(string $s): string {
    if (preg_match('/^(.*?)\s+\((.+)\)$/', $s, $m)) {
        return '<span class="ft">' . e($m[1]) . '</span><span class="fs">(' . e($m[2]) . ')</span>';
    }
    return '<span class="ft">' . e($s) . '</span>';
}

/** Tier glyph above each plan name. Decorative, so hidden from assistive tech. */
function plan_icon(string $plan): string {
    $svg = [
        'Basic'    => '<circle cx="9" cy="9" r="5.6" fill="none" stroke="currentColor" stroke-width="2.8"/>',
        'Grow'     => '<rect x="1.6" y="10" width="3.4" height="6.4" rx="1"/><rect x="7.3" y="6" width="3.4" height="10.4" rx="1"/><rect x="13" y="1.8" width="3.4" height="14.6" rx="1"/>',
        'Advanced' => '<path d="M9 1.4l2.2 4.7 5 .6-3.7 3.5 1 5.1L9 12.8 3.5 15.3l1-5.1L.8 6.7l5-.6z"/>',
        'Plus'     => '<path d="M9 1.6l7.4 6.2L9 16.4 1.6 7.8z"/>',
    ];
    return '<svg class="pl-ico" viewBox="0 0 18 18" aria-hidden="true" focusable="false">'
         . ($svg[$plan] ?? '') . '</svg>';
}

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

// ---- Answer bank. NOT rendered and NOT emitted as schema.
//      It was FAQPage structured data while no FAQ existed on the page, which
//      breaks Google's "marked-up content must be visible" rule — and FAQ rich
//      results have been limited to government and health sites since 2023, so
//      it bought nothing. Kept here as raw material: work an answer into the
//      prose, or give it its own heading, and it earns its place honestly. ----
$FAQS = [
    ['q' => 'Can you run a wholesale business on the Shopify Basic plan?',
     'a' => 'Yes. Since 2 April 2026, Shopify\'s native B2B features work on Basic, Grow and Advanced — not just Plus. On Basic you get company accounts, wholesale price lists, minimum order quantities, net payment terms and a B2B checkout at no extra cost beyond the plan itself.'],
    ['q' => 'How many B2B catalogs do you get on the Shopify Basic plan?',
     'a' => 'Three active catalogs across all your B2B markets. Shopify Plus gets unlimited catalogs. Three catalogs means three pricing tiers — for most merchants starting out, that is enough.'],
    ['q' => 'Do you need Shopify Plus to sell wholesale?',
     'a' => 'No, not to start. You need Plus for four specific things: more than three pricing tiers, pricing negotiated with one individual company, taking a deposit upfront, and recording partial payments against an invoice. If none of those describe your business, Basic is enough.'],
    ['q' => 'What is the best free Shopify theme for a wholesale store?',
     'a' => 'Trade, built by Shopify specifically for B2B. It is free, includes a quick order list for bulk ordering, hides prices from logged-out visitors, and is designed around Shopify\'s native B2B features. Its weakness is that it is B2B-only, so it suits DTC crossover poorly.'],
    ['q' => 'Can Shopify hide prices from customers who are not logged in?',
     'a' => 'Yes. The free Trade theme gates pricing behind customer login natively, so only approved wholesale buyers see your trade prices. Note that it hides the price but does not add a "Request a Quote" button in its place — that requires an app.'],
    ['q' => 'Can you set different prices for different customers on Shopify Basic?',
     'a' => 'You can set different prices for up to three customer groups using catalogs. You cannot set a price for one individual company — catalogs on Basic can only be attached to B2B markets, not to a specific company location. Per-company pricing requires Shopify Plus or a wholesale pricing app.'],
    ['q' => 'Does Shopify have a native Request a Quote feature?',
     'a' => 'Partly. Draft orders let you build a quote with custom prices, lock those prices and send a payable invoice, and checkout-to-draft lets a buyer submit a cart for approval. But native quoting is always merchant-initiated — there is no buyer-facing "Request a Quote" button, no counter-offers, and no quote expiry without an app.'],
    ['q' => 'Do you still need a wholesale app if you use native Shopify B2B?',
     'a' => 'Not at first. Native B2B plus the Trade theme, Shopify Forms and Shopify Flow will carry your first twenty to thirty wholesale accounts. Buy an app when you hit a specific wall — a fourth price tier, invoicing your accounts department will accept, or freight rules Shopify\'s shipping settings cannot express.'],
    ['q' => 'Why are my B2B catalogs not working on the Shopify Basic plan?',
     'a' => 'Almost always because the store is not on new Shopify Markets. On Basic, Grow and Advanced, B2B catalogs are attached to B2B markets, so the Markets migration is a hard prerequisite. Merchants routinely lose days to this before finding it.'],
    ['q' => 'How much does it cost to run wholesale on Shopify Basic?',
     'a' => 'The realistic floor is the Basic plan alone, because native B2B, the Trade theme, Shopify Forms and Shopify Flow are all included. A typical growing wholesale stack adds roughly $100–200 per month for a pricing app, invoicing and shipping rules. Compare that annual total against Shopify Plus before upgrading.'],
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

  <!-- Primary SEO -->
  <title><?= e($CONFIG['page_title']) ?></title>
  <meta name="description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="keywords" content="<?= e($CONFIG['page_keywords']) ?>" />
  <meta name="author" content="<?= e($POST['author']['name']) ?>" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="theme-color" content="#0e1210" />
  <meta name="color-scheme" content="light" />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />

  <!-- Open Graph -->
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

  <!-- Twitter -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:creator" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:title" content="<?= e($POST['title']) ?>" />
  <meta name="twitter:description" content="<?= e($POST['excerpt']) ?>" />
  <meta name="twitter:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta name="twitter:image:alt" content="<?= e($CONFIG['og_image_alt']) ?>" />

  <!-- Icons -->
  <link rel="icon" type="image/png" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="apple-touch-icon" href="<?= e($CONFIG['logo']) ?>" />

  <!-- Fonts & styles -->
  <?php
    // One request, matching every other page. This used to be two: Geist on
    // display=optional (the headline face — a late swap reflowed the <h1> and
    // resized the hero illustration with it, CLS 0.074) plus Lato on swap for
    // the prose. Now that Lato is the site face everywhere, Geist sans is not
    // used at all and the split has nothing left to protect, so the two
    // families collapse into a single stylesheet.
    //
    // display=swap, not optional: Lato sets the prose, and a cold cache on
    // optional would simply never show it. The hero CLS this once guarded
    // against is unchanged either way — the <h1> was already rendering in Lato.
    $font_css = 'https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Geist+Mono:wght@400;500&display=swap';
  ?>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="<?= e($font_css) ?>" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) ?></script>

  <style>
    html { scroll-padding-top: 90px; }

    :root {
      /* An all-system serif alternative, kept as an escape hatch: Charter is
         what Medium licenses and ships on macOS/iOS, Georgia covers Windows,
         Noto Serif covers Android. Zero bytes and no swap. */
      --f-serif: Charter, 'Bitstream Charter', 'Sitka Text', Georgia, 'Noto Serif', serif;
      /* The shared palette carries no red — only the accent green and the
         amber used for "no" in tables. --red is the negative counterpart to
         --accent; --red-2 is the muted tone for a cross that should read as
         "absent" rather than as a warning. */
      --red: #cf3b34;
      --red-2: #d98079;

      /* Reading face for the article body. Lato now comes from the shared
         stylesheet as --f-sans, so this only exists as the one switch that
         returns the prose to Charter without touching anything else. */
      --f-read: var(--f-sans);
    }

    /* ============ Hero ============ */
    .post-hero { padding: 64px 0 40px; }
    .post-hero-grid {
      display: grid; grid-template-columns: 1.05fr 1fr;
      gap: 56px;
      /* stretch, not center: the art takes its height from the title column so
         the two sides stay flush even if the headline rewraps. */
      align-items: stretch;
    }
    @media (max-width: 900px) { .post-hero-grid { grid-template-columns: 1fr; gap: 36px; } }

    .post-hero h1 {
      font-size: clamp(32px, 4.4vw, 54px); line-height: 1.08;
      letter-spacing: -0.035em; font-weight: 500; margin: 18px 0 0;
    }

    .post-meta {
      display: flex; flex-wrap: wrap; align-items: center; gap: 10px 26px;
      margin-top: 26px;
      font-family: var(--f-mono); font-size: 12.5px; color: var(--muted);
    }
    .post-meta .m { display: inline-flex; align-items: center; gap: 8px; }
    .post-meta svg { width: 15px; height: 15px; color: var(--accent); flex: none; }

    .post-tags { display: flex; flex-wrap: wrap; gap: 8px; margin: 22px 0 0; padding: 0; list-style: none; }

    .crumbs {
      margin-top: 22px; font-size: 13px; color: var(--muted); line-height: 1.6;
    }
    .crumbs a { color: var(--accent); }
    .crumbs a:hover { text-decoration: underline; }
    .crumbs span[aria-hidden] { color: var(--line-2); margin: 0 6px; }

    /* ============================================================
     * Hero art — a 3D scene built entirely from CSS transforms.
     * No library, no image request: the <h1> stays the LCP element and
     * the whole thing costs zero bytes over the wire. Only transform and
     * opacity animate, so it runs on the compositor — no layout, no paint,
     * no CLS. Everything is sized in cqw so the scene scales like an SVG.
     * ============================================================ */
    /* No frame, no border, no card shadow — the scene sits directly on the
       section. The only background is an edgeless accent haze. */
    .hero-art {
      position: relative;
      container-type: inline-size;
      background: radial-gradient(58% 62% at 62% 45%, color-mix(in oklch, var(--accent) 11%, transparent), transparent 72%);
    }
    /* Absolute fill: the height comes from the title column via align-items:stretch,
       so the scene is laid out against whatever that resolves to. */
    .scene {
      position: absolute; inset: 0;
      display: grid;
      grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.8fr);
      gap: 2.2cqw; align-items: center;
      perspective: 90cqw;
      font-family: var(--f-sans);
    }
    /* Flex/grid rather than absolute percentages: the frame height is driven by
       the headline, so a percentage-positioned composition would collide the
       moment the title rewrapped. This adapts instead. */
    .scene .stage { display: flex; flex-direction: column; gap: 2.4cqw; min-width: 0; }
    .scene .feats { display: flex; flex-direction: column; align-items: flex-end; gap: 1.9cqw; min-width: 0; }

    /* ---- Panel: flat and rectangular, facing the reader ---- */
    .scene .panel {
      background: #fff; border-radius: 2.2cqw;
      /* Stacked hard shadows build the extruded slab edge; the last is the cast shadow. */
      box-shadow:
        0 0.8cqw 0 var(--line),
        0 1.6cqw 0 var(--line-2),
        0 2.8cqw 4.5cqw rgba(14,18,16,0.15);
      animation: drift 9s ease-in-out infinite;
    }
    .scene .panel-bar {
      display: flex; align-items: center; gap: 1cqw;
      padding: 1.8cqw 2.4cqw;
      background: var(--accent); border-radius: 2.2cqw 2.2cqw 0 0;
    }
    .scene .panel-bar i { width: 1.5cqw; height: 1.5cqw; border-radius: 50%; background: rgba(255,255,255,0.55); }
    .scene .brandpill {
      margin-left: auto; display: inline-flex; align-items: center;
      background: #fff; border-radius: 999px; padding: 0.7cqw 1.7cqw;
    }
    .scene .brandpill img { height: 2.1cqw; width: auto; display: block; }
    .scene .panel-body { padding: 2.4cqw 2.6cqw 2.6cqw; }
    .scene .panel-title {
      display: flex; align-items: center; justify-content: space-between; gap: 1.5cqw;
      font-size: 2.4cqw; font-weight: 600; color: var(--ink); letter-spacing: -0.01em;
      padding-bottom: 1.8cqw; border-bottom: 1px solid var(--line);
    }
    .scene .panel-title em {
      font-style: normal; font-family: var(--f-mono); font-size: 1.7cqw; letter-spacing: 0.05em;
      color: #a45300; background: #fff4e5; border: 1px solid #f4d9b0;
      border-radius: 999px; padding: 0.5cqw 1.2cqw;
    }
    .scene .rows { list-style: none; margin: 0; padding: 0; }
    .scene .rows li {
      display: grid; grid-template-columns: 1fr auto auto; align-items: center; gap: 1.4cqw;
      padding: 1.7cqw 0; border-bottom: 1px dashed var(--line);
      font-size: 2.1cqw;
    }
    .scene .rows li:last-child { border-bottom: 0; padding-bottom: 0; }
    .scene .rows .nm { color: var(--ink-2); font-weight: 500; }
    .scene .rows .qt {
      font-family: var(--f-mono); font-size: 1.6cqw; color: var(--muted);
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 999px; padding: 0.4cqw 1.1cqw;
    }
    .scene .rows .pr { font-family: var(--f-mono); font-weight: 500; color: var(--accent); }
    .scene .rows .pr.was { color: var(--muted-2); text-decoration: line-through; }

    /* ---- Floating feature chips ---- */
    .scene .chip {
      display: inline-flex; align-items: center; gap: 1.3cqw;
      padding: 1.5cqw 2.1cqw;
      background: #fff; border-radius: 2.2cqw;
      font-size: 2.1cqw; font-weight: 600; color: var(--ink);
      letter-spacing: -0.01em; white-space: nowrap;
      box-shadow:
        0 0.7cqw 0 var(--line-2),
        0 1.6cqw 3cqw rgba(14,18,16,0.16);
    }
    .scene .chip svg { width: 2.6cqw; height: 2.6cqw; flex: none; }
    .scene .chip .ic {
      width: 4.4cqw; height: 4.4cqw; flex: none;
      display: grid; place-items: center; border-radius: 1.3cqw;
      background: color-mix(in oklch, var(--accent) 14%, white); color: var(--accent);
    }
    .scene .chip.amber .ic { background: #fff4e5; color: #a45300; }

    /* Chips sit in a flex column so they can never collide with the panel.
       --dx staggers them horizontally for a scattered, hand-placed look; the
       float keyframes fold that offset in so the two transforms don't fight. */
    /* All offsets are <= 0 so a chip can never push past the right edge. */
    .scene .c1 { --dx: -2.5cqw; animation: float 6.5s ease-in-out infinite; }
    .scene .c2 { --dx: 0;       animation: float 7.5s ease-in-out infinite 0.6s; }
    .scene .c3 { --dx: -4cqw;   animation: float 8.2s ease-in-out infinite 1.2s; }
    .scene .c4 { --dx: -1cqw;   animation: float 7s   ease-in-out infinite 0.3s; }

    /* ---- "B2B" blocks, real 3D cubes ---- */
    .scene .blocks {
      display: flex; gap: 1cqw;
      padding-left: 1.5cqw;
      transform-style: preserve-3d;
    }
    .scene .cube {
      position: relative; width: 8cqw; height: 8cqw;
      transform-style: preserve-3d;
      transform: rotateX(-16deg) rotateY(-22deg);
    }
    .scene .cube.flip { animation: flip 9s ease-in-out infinite; }
    .scene .cube b {
      position: absolute; inset: 0;
      display: grid; place-items: center;
      font-family: var(--f-mono); font-size: 3.4cqw; font-weight: 500;
      color: var(--ink-2); border-radius: 0.8cqw;
      backface-visibility: hidden;
    }
    .scene .cube .fr { transform: translateZ(4cqw); background: #f1eee3; }
    .scene .cube .tp { transform: rotateX(90deg) translateZ(4cqw); background: #faf8f2; }
    .scene .cube .rt { transform: rotateY(90deg) translateZ(4cqw); background: #ded8c6; }

    @keyframes float {
      0%, 100% { transform: translate(var(--dx, 0), 0); }
      50%      { transform: translate(var(--dx, 0), -1.7cqw); }
    }
    @keyframes drift {
      0%, 100% { transform: translateY(0); }
      50%      { transform: translateY(-1.2cqw); }
    }
    /* Slow quarter-turn: the front face reads B, the right face C. */
    @keyframes flip {
      0%, 38%   { transform: rotateX(-16deg) rotateY(-22deg); }
      50%, 88%  { transform: rotateX(-16deg) rotateY(-112deg); }
      100%      { transform: rotateX(-16deg) rotateY(-22deg); }
    }

    /* The scene is decorative — hold it still for anyone who asks. */
    @media (prefers-reduced-motion: reduce) {
      .scene .panel, .scene .chip, .scene .cube { animation: none; }
    }
    /* Stacked layout: cap the width rather than changing the aspect ratio.
       The chip positions are tuned against one geometry — altering the ratio
       shifts the panel relative to them and they start covering the prices. */
    @media (max-width: 900px) {
      /* Stacked: there is no title column to take height from any more, so the
         frame needs an explicit ratio. width:100% is required, not redundant —
         this is a grid item with container-type:inline-size, so auto margins
         alone collapse it to zero because containment stops the contents from
         establishing a width. */
      .hero-art {
        width: 100%; max-width: 560px; margin-inline: auto;
        aspect-ratio: 100 / 56;
      }
    }

    /* ============ Body layout ============ */
    .post-layout {
      display: grid; grid-template-columns: 300px 1fr;
      gap: 64px; align-items: start;
      padding: 8px 0 88px;
    }
    /* Grid tracks default to min-width:auto, so the widest table would otherwise
       inflate the whole column and push the page sideways. Let the track shrink
       and leave horizontal scrolling to .stable-wrap, where it belongs. */
    .post-layout > * { min-width: 0; }
    @media (max-width: 1000px) { .post-layout { grid-template-columns: 1fr; gap: 36px; } }

    /* ---- Left rail ---- */
    .post-rail {
      position: sticky; top: 90px;
      /* On short viewports the card is taller than the space available and the
         share buttons get clipped — let it scroll internally instead. */
      max-height: calc(100vh - 106px);
      overflow-y: auto;
      overscroll-behavior: contain;
      scrollbar-width: thin;
      scrollbar-color: var(--line-2) transparent;
    }
    @media (max-width: 1000px) {
      .post-rail { position: static; max-height: none; overflow: visible; }
    }
    .rail-card {
      background: #fff; border: 1px solid var(--line);
      border-radius: var(--r-lg); padding: 26px 24px;
    }

    .rail-author { display: flex; align-items: center; gap: 14px; }
    .rail-author img {
      width: 52px; height: 52px; flex: none; border-radius: 50%;
      object-fit: cover; border: 2px solid #fff;
      box-shadow: 0 4px 14px rgba(14,18,16,0.14), 0 0 0 1px var(--line);
    }
    .rail-author .n { font-weight: 600; font-size: 16px; letter-spacing: -0.015em; }
    .rail-author .r { color: var(--muted); font-size: 13px; margin-top: 2px; }

    .rail-sep { height: 1px; background: var(--line); margin: 22px 0; border: 0; }

    .rail-label {
      font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.14em;
      text-transform: uppercase; color: var(--muted); margin: 0 0 14px; font-weight: 500;
    }

    /* ---- Reading progress ---- */
    .toc-head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; }
    .toc-head .rail-label { margin: 0; }
    .toc-pct {
      font-family: var(--f-mono); font-size: 12px; font-weight: 500;
      color: var(--accent); font-variant-numeric: tabular-nums;
    }
    .toc-track {
      height: 3px; margin: 10px 0 6px;
      background: var(--paper-3); border-radius: 999px; overflow: hidden;
    }
    .toc-track > span {
      display: block; height: 100%; width: 0;
      background: var(--accent); border-radius: 999px;
      transition: width 160ms linear;
    }

    /* ---- Grouped, self-collapsing contents ---- */
    .toc { list-style: none; margin: 0; padding: 0; }
    .toc-group + .toc-group { border-top: 1px solid var(--line); }

    .toc-group-head {
      width: 100%; display: grid;
      grid-template-columns: auto 1fr auto auto; align-items: center; gap: 10px;
      padding: 12px 0; background: none; border: 0; text-align: left;
      font-family: inherit; color: var(--ink-2); cursor: pointer;
    }
    .toc-group-head:focus-visible { outline: 2px solid var(--accent); outline-offset: 3px; border-radius: 8px; }

    .toc-group-n {
      width: 24px; height: 24px; flex: none;
      display: grid; place-items: center; border-radius: 8px;
      background: var(--paper-2); border: 1px solid var(--line);
      font-family: var(--f-mono); font-size: 10px; color: var(--muted-2);
      transition: background 180ms ease, color 180ms ease, border-color 180ms ease;
    }
    .toc-group-t {
      font-size: 13.5px; font-weight: 500; letter-spacing: -0.01em; line-height: 1.3;
      transition: color 180ms ease;
    }
    .toc-group-meta {
      font-family: var(--f-mono); font-size: 10.5px; color: var(--muted-2);
      font-variant-numeric: tabular-nums;
    }
    .toc-chev { width: 14px; height: 14px; color: var(--muted-2); transform: rotate(180deg); transition: transform 240ms ease; }

    /* Group holding the section on screen. */
    .toc-group.current .toc-group-n { background: var(--accent); color: var(--accent-ink); border-color: transparent; }
    .toc-group.current .toc-group-t { color: var(--accent); }
    /* Groups fully scrolled past. */
    .toc-group.done .toc-group-n {
      background: color-mix(in oklch, var(--accent) 14%, white);
      border-color: color-mix(in oklch, var(--accent) 30%, white);
      color: var(--accent);
    }
    .toc-group.collapsed .toc-chev { transform: rotate(0deg); }

    /* Groups render expanded and JS collapses them, so with no JS the sidebar
       degrades to a plain nested list instead of hiding 11 of the 13 links.
       0fr -> 1fr animates to the panel's natural height with no magic max-height. */
    .toc-panel {
      display: grid; grid-template-rows: 1fr;
      transition: grid-template-rows 260ms ease;
    }
    .toc-group.collapsed .toc-panel { grid-template-rows: 0fr; }
    .toc-items { overflow: hidden; list-style: none; margin: 0; padding: 0; }
    /* Keeps collapsed links out of the tab order and away from screen readers. */
    .toc-group.collapsed .toc-items { visibility: hidden; transition: visibility 0s linear 260ms; }

    .toc-items li:last-child a { margin-bottom: 12px; }
    .toc-items a {
      display: block; margin-left: 11px; padding: 6px 0 6px 15px;
      border-left: 2px solid var(--line);
      font-size: 13px; line-height: 1.45; color: var(--muted);
      transition: color 140ms ease, border-color 140ms ease;
    }
    .toc-items a:hover { color: var(--ink); border-left-color: var(--line-2); }
    .toc-items a.read { color: var(--muted-2); border-left-color: color-mix(in oklch, var(--accent) 30%, var(--line)); }
    .toc-items a.active {
      color: var(--accent); font-weight: 500;
      border-left-color: var(--accent);
      /* Second cue so the active row is not signalled by colour alone. */
      background: linear-gradient(90deg, color-mix(in oklch, var(--accent) 8%, transparent), transparent 70%);
    }

    .share { display: flex; gap: 10px; }
    .share button, .share a {
      width: 40px; height: 40px; border-radius: 50%;
      display: inline-flex; align-items: center; justify-content: center;
      background: #fff; border: 1px solid var(--line); color: var(--muted);
      transition: border-color 150ms ease, color 150ms ease, transform 150ms ease;
    }
    .share button:hover, .share a:hover,
    .share button:focus-visible, .share a:focus-visible {
      border-color: var(--accent); color: var(--accent); transform: translateY(-1px);
    }
    .share svg { width: 17px; height: 17px; }
    .share .copied { color: var(--accent); border-color: var(--accent); }

    /* ---- Prose: serif, full column width ----
       No max-width by request: prose runs the full width of the grid column. */
    .post-body {
      font-family: var(--f-read);
      font-size: 18px; line-height: 1.7; color: var(--ink-2);
    }
    /* Opt out of the global `section` padding — prose sets its own rhythm. */
    .post-body section { padding: 0; }
    .post-body section + section { margin-top: 8px; }
    .post-body p { margin: 0 0 24px; }
    .post-body .intro { font-size: 21px; line-height: 1.65; color: var(--ink-2); }

    /* Anchor offset comes from html{scroll-padding-top} above — adding
       scroll-margin-top here too would double it and land jumps 180px down. */
    /* Headings stay in Geist so structure reads as distinct from the prose. */
    .post-body h2 {
      font-family: var(--f-sans);
      font-size: clamp(26px, 2.6vw, 32px); line-height: 1.2;
      letter-spacing: -0.025em; font-weight: 600; color: var(--ink);
      margin: 52px 0 18px;
    }
    .post-body h3 {
      font-family: var(--f-sans);
      font-size: 21px; line-height: 1.35; font-weight: 600;
      letter-spacing: -0.015em; color: var(--ink); margin: 34px 0 12px;
    }
    .post-body a { color: var(--accent); text-underline-offset: 3px; }
    .post-body a:hover { text-decoration-thickness: 2px; }

    /* Inline brand link: logo + label read as a single word.
       nowrap keeps the mark from being orphaned at the end of a line, and the
       underline lives on the inner span so it runs under the text only. */
    .post-body a.brand-link { white-space: nowrap; text-decoration: none; }
    .post-body a.brand-link span { text-decoration: underline; text-underline-offset: 3px; }
    .post-body a.brand-link:hover span { text-decoration-thickness: 2px; }
    .post-body a.brand-link img {
      /* Overrides the site-wide `img { display: block }` in styles.css, which
         would otherwise push the mark onto a line of its own. */
      display: inline-block;
      /* Sized in em so the mark tracks the prose, not a fixed pixel value.
         1em of a 19px serif overshoots optically — 0.95 matches the cap height.
         The -0.12em nudge drops it off the baseline onto the x-height centre. */
      width: 0.95em; height: 0.95em;
      margin-right: 0.28em;
      vertical-align: -0.12em;
    }
    /* 700, not 600: Charter and Georgia ship only 400 and 700, so an in-between
       weight makes the browser synthesise a smeared fake bold. */
    .post-body strong { color: var(--ink); font-weight: 700; }

    .post-body code {
      font-family: var(--f-mono); font-size: 0.86em;
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 5px; padding: 1px 5px;
    }

    .post-body ul, .post-body ol { margin: 0 0 24px; padding-left: 22px; display: grid; gap: 10px; }
    .post-body li { font-size: 18px; line-height: 1.68; }
    .post-body li::marker { color: var(--accent); }

    /* ============================================================
     * Numbered timeline — styling only, applied to any <ul class="steps">.
     * Numbers come from a CSS counter, so the markup stays a plain list,
     * items can wrap to any height, and each list restarts at 01.
     *
     * Everything below is derived from the four knobs — change --tl-size
     * alone and the disc, the number, the dotted line and the text
     * alignment all rescale together.
     * ============================================================ */
    .steps {
      --tl-size: 25px;    /* disc diameter                              */
      --tl-num: 12px;     /* number size inside the disc                */
      --tl-gap: 18px;     /* vertical space between items               */
      --tl-space: 14px;   /* disc → text gap                            */
      --tl-line: 30px;    /* first text line box (18px × 1.68 ≈ 30)     */
      /* Row height the disc and the first text line share — whichever is
         taller. Both are centred on it, so they always sit on one axis. */
      --tl-row: max(var(--tl-size), var(--tl-line));

      list-style: none;
      counter-reset: tl;
      padding-left: 0!important;
      margin: 28px 0;
      gap: var(--tl-gap);
    }

    /* Deliberately NOT a grid: an item may contain a <strong> lead-in plus
       loose text, and every child of a grid — including each bare text run —
       becomes its own grid item, which drops the text into the narrow disc
       column. Normal flow with an out-of-flow disc keeps any inline markup
       inside an item flowing as one paragraph. */
    .steps > li {
      position: relative;
      padding-left: calc(var(--tl-size) + var(--tl-space));
      /* Text sinks by half the difference when the disc is the taller of
         the two; zero while the disc is smaller than a line of text. */
      padding-top: calc((var(--tl-row) - var(--tl-line)) / 2);
      min-height: var(--tl-size);
    }

    /* Numbered <h3>: the same disc on a heading rather than a list item, for
       runs of steps that carry their own sub-headings. The counter resets per
       section, so each section starts again at 01, and the number lives in CSS
       instead of being typed into the heading text. */
    .post-body section { counter-reset: sh; }
    .post-body h3.step-h {
      --tl-size: 28px;    /* a heading carries a slightly larger disc */
      --tl-num: 13px;
      --tl-space: 14px;
      --tl-line: 28px;    /* first text line box (21px × 1.35 ≈ 28) */
      --tl-row: max(var(--tl-size), var(--tl-line));

      position: relative;
      counter-increment: sh;
      padding-left: calc(var(--tl-size) + var(--tl-space));
      padding-top: calc((var(--tl-row) - var(--tl-line)) / 2);
      min-height: var(--tl-size);
    }
    .post-body h3.step-h::before { content: counter(sh, decimal-leading-zero); }

    /* The disc: counter value, zero-padded (01, 02, …). */
    .steps > li::before {
      counter-increment: tl;
      content: counter(tl, decimal-leading-zero);
    }
    .steps > li::before,
    .post-body h3.step-h::before {
      /* Offsets resolve against the padding box, so padding-top above moves
         the text without moving the disc — both stay centred on --tl-row. */
      position: absolute;
      z-index: 1;
      left: 0;
      top: calc((var(--tl-row) - var(--tl-size)) / 2);

      width: var(--tl-size);
      height: var(--tl-size);
      display: flex;
      align-items: center;
      justify-content: center;

      border-radius: 50%;
      background: var(--accent);
      color: #fff;

      font-family: var(--f-sans);
      font-size: var(--tl-num);
      font-weight: 600;
      line-height: 1;
      letter-spacing: -0.01em;
      font-variant-numeric: tabular-nums;
    }

    @media (max-width: 640px) {
      .steps {
        --tl-size: 22px;
        --tl-num: 11px;
        --tl-gap: 14px;
        --tl-space: 12px;
        --tl-line: 29px;
      }
    }

    /* ---- Screenshots ----
       A pair of admin captures shown side by side. They arrive at slightly
       different ratios, so each is letterboxed into one shared box on the
       admin's own canvas grey (#f1f0f1, sampled from the captures) — the bands
       are invisible and both frames end up the same height. Shrunk this far the
       UI text is small, so each frame links out to the full-size file. */
    .shots {
      /* Per-pair geometry: every capture in a pair shares one ratio and one
         canvas colour, so the two frames always end up the same height. */
      --shot-ratio: 1316 / 784;
      --shot-bg: #f1f0f1;

      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin: 18px 0 4px;
    }
    /* The quantity pair is white-backed and near-identical in ratio
       (2.586 vs 2.581), so the two frames match without any padding. */
    .shots-quantity {
      --shot-ratio: 1306 / 506;
      --shot-bg: #fff;
    }
    .shot { margin: 0; }
    /* The frame lives on the link, not the image: the radius can then clip
       whatever sits inside it, including a trimmed capture. */
    .shot a {
      display: block;
      overflow: hidden;
      text-decoration: none;
      border: 1px solid var(--line);
      border-radius: var(--r-md);
      background: var(--shot-bg);
      transition: transform 0.18s ease, box-shadow 0.18s ease;
    }

    .shot img {
      width: 100%;
      height: auto;
      aspect-ratio: var(--shot-ratio);
      object-fit: contain;
      display: block;
    }

    /* quantity.png was captured with the dimmed page behind the modal: a
       #787878 ring measuring 10px left, 12px right, 11px top, 9px bottom, with
       the modal's own rounded corners inside it. Zoom past the ring and the
       frame's radius does the rounding, with the file left untouched.
       The axes scale separately because the capture is 2.6x wider than tall, so
       one percentage cannot clear both: 1.02 lifts 12.4px off each side (clears
       12), while the same figure would lift only 4.8px off the top (needs 11).
       1.06 vertically clears it with 13.8px, at the cost of stretching the
       image ~4% taller than life — invisible at this size, and the full-res
       original is one click away. */
    .shot-trim img { transform: scale(var(--trim-x, 1.02), var(--trim-y, 1.06)); }

    /* volume-pricing.png carries a much thinner ring (4/6/5/5px), so a single
       uniform figure clears every side and nothing gets stretched. */
    .shots-volume {
      --shot-ratio: 1250 / 782;
      --shot-bg: #fff;
      --trim-x: 1.02;
      --trim-y: 1.02;
    }

    /* ---- Pros / cons lines inside a .steps item ----
       Two columns, not an inline chip: the labels line up as a column of their
       own and the verdicts all start at the same x, so a reader can run down
       the list comparing like with like. Solid fill because these are the
       judgement in the section — they should outrank the body text. */
    .sr-only {
      position: absolute; width: 1px; height: 1px;
      overflow: hidden; clip-path: inset(50%); white-space: nowrap;
    }

    /* ---- Plan-features table ----
       A plain white grid: rules between every row and column, no column tint.
       The only colour is the accent — the tier glyphs, the ticks and the one
       value ("Unlimited") that is an exception rather than a yes or no. */
    .stable-wrap {
      overflow-x: auto;
      margin: 0 0 10px;
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 12px;
    }
    .stable {
      width: 100%;
      min-width: var(--stable-min, 540px);
      border-collapse: collapse;
      font-family: var(--f-sans);
      font-size: 15px;
      text-align: left;
      color: var(--ink-2);
    }
    /* Column rules come from the cells, so they stop at the group bands, which
       span the full width and read as dividers in their own right. */
    .stable thead th + th,
    .stable tbody td { border-left: 1px solid var(--line); }
    .stable thead th { border-bottom: 1px solid var(--line); }
    .stable tbody th, .stable tbody td { border-top: 1px solid var(--line); }
    .stable tbody:first-of-type tr:first-child th { border-top: 0; }

    .stable thead th {
      padding: 16px 20px;
      font-weight: 500;
      color: var(--ink);
      white-space: nowrap;
      background: color-mix(in oklch, var(--accent) 10%, white);
    }
    .pl-ico {
      display: block;
      width: 19px; height: 19px;
      margin: 0 auto 7px;
      fill: var(--accent);
      color: var(--accent);
    }
    .pl-name { font-size: 15px; }

    .stable tbody th, .stable tbody td { padding: 14px 20px; line-height: 1.45; vertical-align: top; }
    .stable tbody th[scope="row"] { font-weight: 400; }
    /* Prose tables read left; only the mark matrix centres its value columns,
       where a tick has nothing to align to but the column itself. */
    .stable-marks { --stable-min: 660px; }
    .stable-marks thead th + th,
    .stable-marks tbody td { text-align: center; vertical-align: middle; }
    .ft { display: block; color: var(--ink); }
    .fs { display: block; margin-top: 2px; font-size: 13.5px; color: var(--muted); }

    /* App-store icon beside a listing name. The icon is now taller than one
       line, so the app column gets a min-width: without it the icon eats the
       horizontal room and the longest name breaks onto a third line. */
    .ft-app { display: flex; align-items: center; gap: 10px; }
    .app-ico {
      flex: 0 0 auto;
      width: 34px; height: 34px;
      border-radius: 8px;
      border: 1px solid var(--line);
      object-fit: cover;
      display: block;
    }
    .stable-apps tbody th[scope="row"] { min-width: 250px; }

    .stable .grp th {
      background: color-mix(in oklch, var(--accent) 10%, white);
      padding: 12px 20px;
      font-weight: 700;
      font-size: 15px;
      color: var(--ink);
      text-align: left;
    }
    .stable .mk { font-size: 17px; line-height: 1; }
    .stable .mk.yes { color: var(--accent); }
    .stable .mk.no { color: var(--red-2); }
    .stable .mk-txt { color: var(--accent); font-weight: 600; }
    .post-body .src { font-size: 14px; color: var(--muted); margin: 0 0 24px; }

    /* ---- Theme comparison cards ----
       One row per theme: identity, who it suits, pros, cons. Each card carries
       its own hue in --c, which drives the rail, the number ring, the name and
       the chip; pros/cons keep a fixed green and red so the verdict reads the
       same way down every card. */
    .tcards { display: grid; gap: 16px; margin: 26px 0 28px; }
    .tcard {
      display: grid;
      /* The article column is narrower than the mockup's canvas, so the tracks
         are weighted towards the two verdict columns, which carry the most
         text; identity keeps just enough for the name and its subtitle. */
      grid-template-columns: 1.12fr 0.95fr 1.05fr 1.15fr;
      border: 1px solid var(--line);
      border-left: 5px solid var(--c);
      border-radius: 16px;
      background: linear-gradient(100deg, color-mix(in oklch, var(--c) 8%, #fff), #fff 52%);
      overflow: hidden;
    }
    .tcard > * { padding: 20px 16px; min-width: 0; }
    .tcard > * + * { border-left: 1px solid var(--line); }

    .tc-id {
      display: grid;
      grid-template-columns: 46px 1fr;
      column-gap: 14px;
      align-content: center;
    }
    .tc-num {
      grid-row: 1 / span 2;
      align-self: center;
      width: 46px; height: 46px;
      display: flex; align-items: center; justify-content: center;
      border: 1.5px solid color-mix(in oklch, var(--c) 40%, white);
      border-radius: 50%;
      font-family: var(--f-mono);
      font-size: 14px;
      color: var(--c);
    }
    .post-body .tc-name {
      margin: 0;
      font-family: var(--f-sans);
      font-size: 22px;
      line-height: 1.2;
      letter-spacing: -0.01em;
      color: var(--c);
      scroll-margin-top: 0;
    }
    .post-body .tc-name a { color: inherit; text-decoration: none; }
    .post-body .tc-name a:hover { text-decoration: underline; text-underline-offset: 3px; }
    .post-body .tc-sub {
      margin: 5px 0 0;
      font-size: 14px;
      line-height: 1.45;
      color: var(--muted);
    }

    .tc-chip {
      display: inline-block;
      background: color-mix(in oklch, var(--c) 14%, white);
      color: color-mix(in oklch, var(--c) 82%, black);
      border-radius: 8px;
      padding: 5px 12px;
      font-size: 12.5px;
      font-weight: 600;
    }
    .post-body .tc-cell p { margin: 11px 0 0; font-size: 15px; line-height: 1.5; }
    .post-body .tc-h {
      margin: 0;
      font-size: 15.5px;
      font-weight: 700;
    }
    .tc-h.is-pro { color: #2f8f4e; }
    .tc-h.is-con { color: #cf3b34; }

    .tc-line { display: grid; grid-template-columns: 18px 1fr; column-gap: 9px; }
    .tc-ico {
      width: 18px; height: 18px;
      margin-top: 2px;
      display: inline-flex; align-items: center; justify-content: center;
      border: 1.5px solid currentColor;
      border-radius: 50%;
      font-size: 9px;
      line-height: 1;
    }
    .tc-ico.is-pro { color: #2f8f4e; }
    .tc-ico.is-con { color: #cf3b34; }
    .tc-ico.is-pro::before { content: "\2713"; }
    .tc-ico.is-con::before { content: "\2715"; }

    /* Four columns need room. Below that, identity and "best for" share a row
       and pros/cons share the next; narrower still, everything stacks and the
       dividers move from the side to the top. */
    @media (max-width: 900px) {
      .tcard { grid-template-columns: 1fr 1fr; }
      .tcard > *:nth-child(3) { border-left: 0; }
      .tcard > *:nth-child(n + 3) { border-top: 1px solid var(--line); }
    }
    @media (max-width: 560px) {
      .tcard { grid-template-columns: 1fr; }
      .tcard > * + * { border-left: 0; border-top: 1px solid var(--line); }
      .tcard > * { padding: 16px; }
    }

    /* A lone capture takes the whole column rather than half of it: this one is
       a seven-row order table, and half the width would leave the rows
       unreadable with an empty half beside them. No ring to trim. */
    .shots-single {
      grid-template-columns: 1fr;
      --shot-ratio: 2326 / 1246;
      --shot-bg: #fff;
    }

    /* The signup modal sits on a near-black backdrop with rounded corners, and
       1.05 is the smallest uniform zoom that clears both the 16-20px ring and
       the corner arcs — 1.06 leaves a margin, with no stretching. The ratio is
       the signup's, so the slightly wider admin capture letterboxes by ~3px a
       side into #f1f0f1, which is exactly its own canvas colour. */
    /* catalog-3.png is a wide, short banner capture on Shopify's own grey
       page background — no trim, the ratio just has to match the file. */
    .shots-catalog {
      --shot-ratio: 1992 / 300;
      --shot-bg: #f1f0f1;
      margin: 18px 0 24px!important;
    }

    .shots-request {
      --shot-ratio: 1156 / 966;
      --shot-bg: #f1f0f1;
      --trim-x: 1.06;
      --trim-y: 1.06;
    }

    /* ---- Live volume-pricing block ----
       The storefront side is rebuilt in markup rather than screenshotted: the
       prices stay searchable and selectable, and it renders sharp at any zoom.
       Sizing is in cqw against the figure, so it scales exactly like the
       capture next to it and both frames stay the same height. */
    .shot-live { container-type: inline-size; }
    .vp {
      /* Side by side the row already has a height, and matching it exactly
         beats the ratio — the capture's own 1px frame makes it a hair taller.
         Stacked, there is no row to match and the ratio takes over. */
      height: 100%;
      aspect-ratio: var(--shot-ratio);
      display: flex;
      flex-direction: column;
      gap: 2.4cqw;
      font-family: var(--f-sans);
      color: #3d3d3d;
    }
    .vp-card {
      border: 1px solid var(--line);
      border-radius: var(--r-md);
      background: #fff;
      overflow: hidden;          /* lets the radius clip the striped rows */
    }
    .vp-tiers-card { flex: 1; display: flex; }
    .vp-tiers {
      width: 100%;
      height: 100%;              /* rows then split the frame evenly */
      border-collapse: collapse;
      table-layout: fixed;
      font-size: clamp(10px, 3.3cqw, 15px);
    }
    .vp-tiers caption {
      position: absolute; width: 1px; height: 1px;
      overflow: hidden; clip-path: inset(50%); white-space: nowrap;
    }
    .vp-tiers th, .vp-tiers td {
      padding: 0 4.4cqw;
      font-weight: 400;
      line-height: 1.4;
      white-space: nowrap;
    }
    .vp-tiers th { text-align: left; }
    .vp-tiers td { text-align: right; }
    /* Zebra from the first row down, matching the storefront block. */
    .vp-tiers tr:nth-child(odd) { background: #f7f7f7; }
    .vp-min {
      padding: 3.4cqw 4.4cqw;
      font-size: clamp(10px, 3.3cqw, 15px);
      line-height: 1.4;
    }
    /* Half of an already-narrow column is unreadable — stack on small screens. */
    @media (max-width: 700px) {
      .shots { grid-template-columns: 1fr; gap: 16px; margin: 14px 0 2px; }
    }

    .post-body blockquote {
      margin: 28px 0; padding: 20px 24px;
      background: var(--paper-2); border-left: 3px solid var(--accent);
      border-radius: 0 12px 12px 0; font-size: 18px; color: var(--ink-2);
    }
    .post-body blockquote p:last-child { margin-bottom: 0; }

    /* Badge and verdict helpers, shared by every table on the page. */
    .stable .plus {
      font-family: var(--f-mono); font-size: 10.5px; letter-spacing: 0.06em;
      background: var(--ink); color: var(--paper); border-radius: 999px; padding: 3px 8px; white-space: nowrap;
    }
    .stable .yes { color: var(--accent); font-weight: 600; }
    .stable .no { color: var(--red); font-weight: 600; }
    .stable td strong { color: var(--ink); }

    .callout {
      margin: 28px 0; padding: 22px 24px;
      background: color-mix(in oklch, var(--accent) 7%, white);
      border: 1px solid color-mix(in oklch, var(--accent) 28%, white);
      border-radius: 14px; font-size: 18px;
    }
    .callout p:last-child { margin-bottom: 0; }
    .callout .k {
      display: block; font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--accent); margin-bottom: 8px;
    }

    /* ---- Tip callout ----
       Outline box with the bulb straddling the top-left corner; the bulb
       itself is emitted by tip_bulb(). Overrides .callout's tinted fill, so
       the two must be used together: class="callout callout-tip". */
    .callout-tip {
      position: relative;
      margin: 75px 0 30px;
      padding: 26px 30px 26px 34px;
      background: transparent;
      border: 1px solid color-mix(in oklch, var(--accent) 72%, white);
      border-radius: 10px;
    }
    .tip-bulb {
      position: absolute;
      top: -32px;
      left: -28px;
      width: 62px;
      height: 62px;
    }

    .mt-6 {
      margin-top: 68px;
    }

    .tip-k { font-weight: 700; color: var(--ink); }
    /* Tucked inside the box on small screens — hanging off the corner would
       push past the viewport edge. */
    @media (max-width: 560px) {
      .callout-tip { margin-top: 40px; padding: 24px 20px; }
      .tip-bulb { top: -30px; left: 6px; width: 54px; height: 54px; }
    }

    .checklist { list-style: none; padding-left: 0; }
    .checklist li { display: grid; grid-template-columns: 22px 1fr; gap: 10px; align-items: start; font-size: 18px; line-height: 1.6; }
    .checklist li::before {
      content: ""; margin-top: 7px; width: 14px; height: 14px;
      border: 1.5px solid var(--line-2); border-radius: 4px;
    }

    /* ---- FAQ ---- */
    .faq { display: grid; gap: 10px; margin: 24px 0 0; }
    .faq details {
      background: #fff; border: 1px solid var(--line); border-radius: 14px;
      transition: border-color 150ms ease;
    }
    .faq details[open], .faq details:hover { border-color: var(--line-2); }
    /* The question is a heading, so it stays in Geist; the answer is prose and
       inherits the serif. */
    .faq summary {
      cursor: pointer; list-style: none; padding: 18px 22px;
      font-family: var(--f-sans);
      font-size: 18px; font-weight: 600; color: var(--ink); letter-spacing: -0.015em;
      display: flex; align-items: flex-start; justify-content: space-between; gap: 16px;
      line-height: 1.45;
    }
    .faq summary::-webkit-details-marker { display: none; }
    .faq summary::after {
      content: "+"; font-family: var(--f-mono); font-size: 20px; color: var(--accent);
      line-height: 1; flex: none; transition: transform 180ms ease;
    }
    .faq details[open] summary::after { transform: rotate(45deg); }
    .faq .a { padding: 0 22px 20px; color: var(--muted); font-size: 17.5px; line-height: 1.66; }

    /* ---- Related ---- */
    /* .blog/.post/.cover-art now live in assets/styles.css: /blogs, the
       homepage row and these cards all render the same component. */

    .sec-head { display: grid; grid-template-columns: 1fr auto; gap: 24px; align-items: end; margin-bottom: 32px; }
    @media (max-width: 820px) { .sec-head { grid-template-columns: 1fr; } }

    @media (prefers-reduced-motion: reduce) {
      .toc-items a, .toc-group-n, .toc-group-t, .toc-chev, .toc-panel, .toc-track > span,
      .share button, .share a, .faq summary::after { transition: none; }
    }
  </style>
  <?php require __DIR__ . '/inc/crisp.php'; ?>
</head>
<body id="top">

<a href="#main" class="skip-link">Skip to content</a>

<!-- ============== NAV ============== -->
<header role="banner">
  <nav class="nav" aria-label="Primary">
    <div class="wrap nav-inner">
      <a href="/" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
        <span class="brand-mark">
          <!-- Both dimensions, so the browser can reserve the slot from the
               1346x244 source before the file lands. height alone yields no
               aspect ratio, and the sticky header shifted on every cold load. -->
          <img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" width="176" height="32" />
        </span>
      </a>
      <div class="nav-links">
        <a href="/#saas">SaaS</a>
        <a href="/#apps">Shopify Apps</a>
        <a href="/blogs" class="active">Blog</a>
      </div>
      <div class="nav-actions">
        <a href="/#contact" class="btn btn-accent btn-sm nav-cta">Contact →</a>
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
        <div class="eyebrow">Guide · Shopify B2B</div>
        <h1 id="post-title"><?= e($POST['h1']) ?></h1>

        <div class="post-meta">
          <span class="m">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 11h18"/></svg>
            <?= e(date('j F Y', strtotime($POST['published']))) ?>
          </span>
          <?php if ($POST['modified'] !== $POST['published']): ?>
          <!-- Shown only once it means something. dateModified is in the JSON-LD
               either way; on a post about a platform change, the reader deserves
               to see it too. -->
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

      <!-- Decorative 3D scene: the article's central idea (one product, three
           prices) surrounded by the native B2B features it depends on. -->
      <div class="hero-art" aria-hidden="true">
        <div class="scene">
          <div class="stage">
          <div class="panel">
            <div class="panel-bar">
              <i></i><i></i><i></i>
              <!-- Same file the nav already loads, so this costs no extra request. -->
              <span class="brandpill"><img src="/assets/dqlab.png" alt="" width="1346" height="244" decoding="async" /></span>
            </div>
            <div class="panel-body">
              <div class="panel-title">Cotton T-Shirt <em>3 of 3</em></div>
              <ul class="rows">
                <li><span class="nm">Gold Distributor</span><span class="qt">24+</span><span class="pr">$30</span></li>
                <li><span class="nm">Silver Dealer</span><span class="qt">12+</span><span class="pr">$37</span></li>
                <li><span class="nm">Retail visitor</span><span class="qt">any</span><span class="pr was">$50</span></li>
              </ul>
            </div>
          </div>

          <div class="blocks">
            <div class="cube"><b class="tp"></b><b class="rt"></b><b class="fr">B</b></div>
            <div class="cube"><b class="tp"></b><b class="rt"></b><b class="fr">2</b></div>
            <div class="cube flip"><b class="tp"></b><b class="rt">C</b><b class="fr">B</b></div>
          </div>
          </div>

          <div class="feats">
          <span class="chip c1">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M5 20v-6M12 20V8M19 20v-9"/></svg></span>
            Volume pricing
          </span>

          <span class="chip c2">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M3 21h18M5 21V4h9v17M14 10h5v11M8 8h3M8 12h3M8 16h3"/></svg></span>
            Companies
          </span>

          <span class="chip c3">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linejoin="round" aria-hidden="true"><path d="M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8"/></svg></span>
            Quantity rules
          </span>

          <span class="chip c4 amber">
            <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M8 3v4M16 3v4M3 11h18"/></svg></span>
            Net 30 terms
          </span>
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
          <!-- 112px source for a 52px slot (2x for retina). The shared 778px
               original is 140KB, which is six times the bytes for no gain here. -->
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

      <p>
        If you are planning to start a <strong>B2B</strong> or <strong>wholesale</strong> business alongside DTC on Shopify, 
        you no longer need Shopify Plus or expensive apps. 
        The native B2B features Shopify opened to all plans on April 2, 2026, 
        are more than enough to run your wholesale business smoothly, 
        no matter which paid plan you are on.
      </p>

      <p>
        In this article, we will mainly cover exactly what native features Shopify offers, 
        which theme works best, the apps you genuinely need, and the ones that are just nice to have.
      </p>

      <section>
        <?= h2($TOC, 'what-changed') ?>
        <hr />
        <p>Shopify's native B2B module is available on Basic, Grow, Advanced, and Plus as of April 2, 2026. 
          A merchant on the cheapest paid plan can now do all of this without upgrading:</p>
        <ul class="steps">
          <li>
            <strong>Company profiles: </strong>
            <p>Manage multiple buyers and locations with unique permission levels,
            payment terms, tax exemptions, and order submission settings.</p>

            <p>Store Admin > Customers > Companies:</p>
            
            <div class="shots">
              <figure class="shot">
                <a href="/assets/company-1.png" target="_blank" rel="noopener noreferrer" aria-label="Open the New company screenshot full size">
                  <img src="/assets/company-1.png" width="1316" height="784" loading="lazy" decoding="async"
                       alt="Shopify admin: the New company form with Company name, Company ID and Main contact fields." />
                </a>
              </figure>
              <figure class="shot">
                <a href="/assets/company-2.png" target="_blank" rel="noopener noreferrer" aria-label="Open the company Location screenshot full size">
                  <img src="/assets/company-2.png" width="1316" height="706" loading="lazy" decoding="async"
                       alt="Shopify admin: a company Location with shipping address, billing address toggle and Location ID." />
                </a>
              </figure>
            </div>
          </li>
          <li>
            <strong>Quantity rules: </strong>
            <p>Create customer-specific conditional rules for products and variants, 
              including minimums and maximums, case packs, and increments.</p>
            <p>Store Admin > Markets > Catalogs > + Add rules</p>
            <div class="shots shots-quantity">
              <figure class="shot shot-trim">
                <a href="/assets/quantity.png" target="_blank" rel="noopener noreferrer" aria-label="Open the quantity rules editor screenshot full size">
                  <img src="/assets/quantity.png" width="1262" height="488" loading="lazy" decoding="async"
                       alt="Shopify admin: the Edit quantity rules and volume pricing dialog with Increment, Minimum and Maximum fields." />
                </a>
              </figure>
              <figure class="shot">
                <a href="/assets/quantity-22.png" target="_blank" rel="noopener noreferrer" aria-label="Open the storefront quantity limit screenshot full size">
                  <img src="/assets/quantity-22.png" width="1306" height="506" loading="lazy" decoding="async"
                       alt="B2B storefront: a variant order table with a Quantity limit for this variant callout showing Min 5 and Max 50." />
                </a>
              </figure>
            </div>
          </li>
          <li>
            <strong>Volume pricing: </strong>
            <p>Easily implement quantity price breaks to get your B2B customers buying in larger quantities.</p>
            <p>Store Admin > Markets > Catalogs > + Add rules</p>
            <div class="shots shots-volume">
              <figure class="shot shot-trim">
                <a href="/assets/volume-pricing.png" target="_blank" rel="noopener noreferrer" aria-label="Open the volume pricing editor screenshot full size">
                  <img src="/assets/volume-pricing.png" width="1250" height="782" loading="lazy" decoding="async"
                       alt="Shopify admin: the Volume pricing section with a minimum of 5 at $59.00 and breaks at 10, 20 and 30 units." />
                </a>
              </figure>
              <figure class="shot shot-live">
                <div class="vp">
                  <div class="vp-card vp-tiers-card">
                    <table class="vp-tiers">
                      <caption>Volume pricing shown to the buyer on the storefront</caption>
                      <tbody>
                        <tr><th scope="row">5+</th><td>$59.00 USD/ea</td></tr>
                        <tr><th scope="row">10+</th><td>$57.00 USD/ea</td></tr>
                        <tr><th scope="row">20+</th><td>$56.00 USD/ea</td></tr>
                        <tr><th scope="row">30+</th><td>$55.00 USD/ea</td></tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="vp-card vp-min">Min 5</div>
                </div>
              </figure>
            </div>
          </li>
          <li>
            <strong>Company account requests: </strong>
            <p>Add an account request form to your store with Shopify Forms. 
              Buyer details save as a company account automatically, 
              so they can buy wholesale as soon as you approve.</p>
            <p>Install <a href="https://apps.shopify.com/shopify-forms" target="_blank" rel="noopener noreferrer">Shopify form App</a> > Store Admin > Customers > Companies > Select company > Approve</p>

            <div class="shots shots-request">
              <figure class="shot shot-trim">
                <a href="/assets/company-signup.png" target="_blank" rel="noopener noreferrer" aria-label="Open the storefront sign up form screenshot full size">
                  <img src="/assets/company-signup.png" width="1156" height="966" loading="lazy" decoding="async"
                       alt="B2B storefront: a Shopify Forms sign up modal asking for company name, first name, last name and email." />
                </a>
              </figure>
              <figure class="shot">
                <a href="/assets/company-request.png" target="_blank" rel="noopener noreferrer" aria-label="Open the company account approval screenshot full size">
                  <img src="/assets/company-request.png" width="1322" height="1086" loading="lazy" decoding="async"
                       alt="Shopify admin: the submitted company awaiting approval, with an Ordering is not approved banner and a Manage permissions button." />
                </a>
              </figure>
            </div>
          </li>
          <li>
            <strong>Quick bulk ordering: </strong>
            <p>Show buyers a list of all products and variants so they can order sizes, 
              colours and more from the product page or a separate order form.</p>

            <div class="shots shots-single">
              <figure class="shot">
                <a href="/assets/bulk.png" target="_blank" rel="noopener noreferrer" aria-label="Open the bulk order form screenshot full size">
                  <img src="/assets/bulk.png" width="2326" height="1246" loading="lazy" decoding="async"
                       alt="B2B storefront: a bulk order form listing seven pillow variants with quantity steppers, per-unit prices and a $1,121.00 product subtotal." />
                </a>
              </figure>
            </div>
          </li>
        </ul>
      </section>

      <section>
        <?= h2($TOC, 'basic-vs-plus') ?>
        <hr />
        
        <p>
          Every B2B feature, by plan
        </p>
        <div class="stable-wrap">
          <table class="stable stable-marks">
            <caption class="sr-only">Shopify B2B features available on the Basic, Grow, Advanced and Plus plans</caption>
            <thead>
              <tr>
                <th scope="col">Feature</th>
                <?php foreach ($B2B_PLANS as $plan): ?>
                  <th scope="col"><?= plan_icon($plan) ?><span class="pl-name"><?= e($plan) ?></span></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <?php foreach ($B2B_PLAN_FEATURES as $group => $rows): ?>
              <tbody>
                <tr class="grp"><th scope="colgroup" colspan="<?= count($B2B_PLANS) + 1 ?>"><?= e($group) ?></th></tr>
                <?php foreach ($rows as $feature => $vals): ?>
                  <tr>
                    <th scope="row"><?= feature_label($feature) ?></th>
                    <?php foreach ($vals as $v): ?>
                      <td><?= plan_cell($v) ?></td>
                    <?php endforeach; ?>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            <?php endforeach; ?>
          </table>
        </div>
        <p class="src">
          Source:
          <a href="https://help.shopify.com/en/manual/b2b/getting-started/plan-features" target="_blank" rel="noopener noreferrer">Shopify Help Center — B2B features by plan</a>
        </p>
      </section>

      <section>
        <?= h2($TOC, 'theme') ?>
        <hr />
        <p>The three themes worth considering, in order of fit:</p>
        <div class="tcards">

          <article class="tcard" style="--c: #3d8f4e;">
            <div class="tc-id">
              <span class="tc-num" aria-hidden="true">01</span>
              <h3 class="tc-name"><a href="https://themes.shopify.com/themes/trade" target="_blank" rel="noopener noreferrer">Trade</a></h3>
              <p class="tc-sub">Shopify's free B2B theme</p>
            </div>
            <div class="tc-cell">
              <span class="tc-chip">Best for</span>
              <p>B2B and wholesale business</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-pro">Pros</p>
              <p class="tc-line"><span class="tc-ico is-pro" aria-hidden="true"></span>Default features activated for B2B and wholesale.</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-con">Cons</p>
              <p class="tc-line"><span class="tc-ico is-con" aria-hidden="true"></span>Purely B2B. If you also sell to regular customers, the store may look too simple.</p>
            </div>
          </article>

          <article class="tcard" style="--c: #2f5cc4;">
            <div class="tc-id">
              <span class="tc-num" aria-hidden="true">02</span>
              <h3 class="tc-name"><a href="https://themes.shopify.com/themes/warehouse" target="_blank" rel="noopener noreferrer">Warehouse</a></h3>
              <p class="tc-sub"><strong>$320 USD</strong>, built for large catalogs</p>
            </div>
            <div class="tc-cell">
              <span class="tc-chip">Best for</span>
              <p>Large catalogs and experienced B2B buyers.</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-pro">Pros</p>
              <p class="tc-line"><span class="tc-ico is-pro" aria-hidden="true"></span>Many variants, deep filtering and B2B buyers who already know how to setup.</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-con">Cons</p>
              <p class="tc-line"><span class="tc-ico is-con" aria-hidden="true"></span>B2B behaviour is not built in: gated pricing and quick ordering come from native B2B and apps.</p>
            </div>
          </article>

          <article class="tcard" style="--c: #6a4bc4;">
            <div class="tc-id">
              <span class="tc-num" aria-hidden="true">03</span>
              <h3 class="tc-name"><a href="https://themes.shopify.com/themes/empire" target="_blank" rel="noopener noreferrer">Empire</a></h3>
              <p class="tc-sub"><strong>$360 USD</strong>, one storefront for retail and wholesale</p>
            </div>
            <div class="tc-cell">
              <span class="tc-chip">Best for</span>
              <p>Retail and wholesale from the same store.</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-pro">Pros</p>
              <p class="tc-line"><span class="tc-ico is-pro" aria-hidden="true"></span>
              Customizable for wholesale and subscription brands.</p>
            </div>
            <div class="tc-cell">
              <p class="tc-h is-con">Cons</p>
              <p class="tc-line"><span class="tc-ico is-con" aria-hidden="true"></span>Built for DTC first, so every wholesale features you have to add rather than inherit.</p>
            </div>
          </article>

        </div>
        
        <div class="callout callout-tip">
          <?= tip_bulb() ?>
          <p>
            <span class="tip-k">Worth knowing:</span>
            Trade hides the price from logged-out visitors. If you want
            a <strong>"Request a Quote" button</strong> where the price used to be, then you have to use an app.
          </p>
        </div>
      </section>

      <section>
        <?= h2($TOC, 'essential-apps') ?>
        <hr />
        <p>
          Before installing anything, turn on the free tools — a surprising number of merchants pay
          for apps that duplicate them.
        </p>
        <div class="stable-wrap">
          <table class="stable">
            <thead><tr><th scope="col">Free tool</th><th scope="col">What it covers</th></tr></thead>
            <tbody>
              <tr><th scope="row"><span class="ft">Customer accounts</span></th><td>The foundation. Nothing in B2B works without it.</td></tr>
              <tr><th scope="row"><span class="ft">Shopify Forms</span></th><td>Wholesale application form with an approval gate</td></tr>
              <tr><th scope="row"><span class="ft">Shopify Flow</span></th><td>Order tagging, payment reminders, new-company alerts</td></tr>
              <tr>
                <th scope="row">
                  <span class="ft">Hide price & Quick order</span>
                </th>
                <td>By default <strong>Trade</strong> theme provides those features.</td>
              </tr>
            </tbody>
          </table>
        </div>
        <p>Now the paid ones that earn their keep.</p>

        <h3 class="step-h">Request a Quote (RFQ) with buyer negotiation</h3>
        <p>The single most common reason to buy an app. Skip it entirely if three tiers covers you.</p>
        <div class="stable-wrap">
          <table class="stable">
            <thead>
              <tr>
                <th scope="col">App</th>
                <th scope="col">Best for</th>
                <th scope="col">Pricing</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-request-for-quote.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/request-for-quote" target="_blank" rel="noopener noreferrer">SA Request a Quote, Hide Price</a>
                  </span>
                </th>
                <td>Request quotes for D2C & B2B customers. Works with Globo Product Options Hubspot, Omnisend Mailchimp, Klaviyo</td>
                <td>Start from $16.99/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                    <span class="ft ft-app">
                      <img class="app-ico" src="/assets/app-request-for-quote-by-omega.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/request-for-quote-by-omega" target="_blank" rel="noopener noreferrer">QS Request a Quote, Hide Price</a>
                  </span>
                </th>
                <td>B2B growth: Sync quote & contact data to Mailchimp, HubSpot & your CRM</td>
                <td>Start from $16.99/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-storeify-request-a-quote.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/storeify-request-a-quote" target="_blank" rel="noopener noreferrer">S:Request a Quote & Hide Price</a>
                  </span>
                </th>
                <td>Automatically accept quotes, create draft orders, and send invoices.</td>
                <td>Start from $12.99/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-dlab-request-quote-hide-price.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/dlab-request-quote-hide-price" target="_blank" rel="noopener noreferrer">DQLab Request Quote Hide Price</a>
                  </span>
                </th>
                <td>Request quote with unlimited negotiation. Invoice with PDF attachment & signature</td>
                <td>Free and $9/m</td>
              </tr>
            </tbody>
          </table>
        </div>

        <h3 class="step-h">Advanced Catalog & Pricing</h3>
        <p>
          On Basic, Grow, and Advanced plans, you can assign up to 3 active catalogs across all your B2B markets. 
          Assigning 3 catalogs to one market uses the full limit. On the Shopify Plus plan, 
          you can create an unlimited number of catalogs and also assign them directly to specific company locations.
        </p>

        <div class="shots shots-single shots-catalog">
          <figure class="shot">
            <a href="/assets/catalog-3.png" target="_blank" rel="noopener noreferrer" aria-label="Open the catalog limit error screenshot full size">
              <img src="/assets/catalog-3.png" width="1992" height="300" loading="lazy" decoding="async"
                   alt="Shopify admin, New catalog: an error banner reading “Only 3 active catalogs can be assigned to active B2B markets. Set a catalog to draft or upgrade your plan to add more.”" />
            </a>
          </figure>
        </div>

        <p>Here is the list of apps which are providing customer group based pricing, 
          volume pricing with discounts. Some apps also giving company registration with approval system.</p>

        <div class="stable-wrap">
          <table class="stable stable-apps">
            <thead>
              <tr>
                <th scope="col">App</th>
                <th scope="col">Best for</th>
                <th scope="col">Pricing</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <!-- Self-hosted copy of the App Store listing icon, resized
                         96px for a 34px slot. Hotlinking cdn.shopify.com meant a
                         third-party connection for one small image, and a listing
                         change could swap it under us without warning. -->
                    <img class="app-ico" src="/assets/app-bss-b2b.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/b2b-solution-custom-pricing" target="_blank" rel="noopener noreferrer">BSS B2B Wholesale Pricing</a>
                  </span>
                </th>
                <td>Centralized B2B wholesale with custom pricing, price lists, net terms, tax control and approvals</td>
                <td>Start from $29/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-clay-b2b-wholesale-form.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/clay-b2b-wholesale-form" target="_blank" rel="noopener noreferrer">Clay • B2B Wholesale Pricing</a>
                  </span>
                </th>
                <td>Offering wholesale & B2B buyers unlimited B2B catalogs</td>
                <td>Start from $25/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-massy-b2b-wholesale-pricing-discount.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/massy-b2b-wholesale-pricing-discount" target="_blank" rel="noopener noreferrer">Massy B2B Wholesale Pricing</a>
                  </span>
                </th>
                <td>For B2B & wholesale merchants: Custom Price Lists, Volume Discounts, MOQ limits, B2B Login</td>
                <td>Start from $29/month with a FREE plan</td>
              </tr>
              <tr>
                <th scope="row">
                  <span class="ft ft-app">
                    <img class="app-ico" src="/assets/app-request-for-quote.png"
                         width="34" height="34" loading="lazy" decoding="async" alt="" />
                    <a href="https://apps.shopify.com/wholesale-sami" target="_blank" rel="noopener noreferrer">Sami B2B Wholesale Pricing</a>
                  </span>
                </th>
                <td>Set wholesale pricing by customer group, product, variant, market, or quantity</td>
                <td>Start from $24.90/month with a FREE plan</td>
              </tr>
            </tbody>
          </table>
        </div>

        <h3 class="step-h">B2B registration & approval</h3>
        <p>
          Shopify lets you manage Companies, but a polished public “Apply for a wholesale account” form + approval workflow may require an app.
        </p>

        <h3 class="step-h">Accounting sync</h3>
        <p>
          Selling on Net 30 means selling on credit, and in wholesale, cash flow <em>is</em> the business. Xero or
          QuickBooks connectors sync orders and invoices automatically. If you use local accounting software, plan
          the CSV export or API integration early rather than as an afterthought.
        </p>

        <h3 class="step-h">Bulk and CSV ordering</h3>
        <p>
          When a dealer orders 120 SKUs at once, clicking through product pages is impossible. 
          The Trade theme's quick order list helps, but has no CSV upload.
        </p>
      </section>

      <section>
        <?= h2($TOC, 'nice-apps') ?>
        <hr />
        <p>
          <strong>These are not day-one purchases.</strong> Add them when the pain is real and you can point at the
          hour it costs you each week.
        </p>
        <ul>
          <li><strong>Credit limits and customer ledger.</strong> "Block new orders if this account owes more than $5,000" does not exist natively. At small scale a Shopify Flow alert is a workable substitute.</li>
          <li><strong>Deposits and partial payments.</strong> Plus-only natively; <strong>Downpay</strong> is the closest workaround for deposits.</li>
          <li><strong>Tax exemption and VAT validation.</strong> Native B2B handles exemption at the company-location level; automated validation needs an app.</li>
          <li><strong>Content gating.</strong> Restricting specific collections or pages to approved dealers — <strong>Locksmith</strong> is the standard answer.</li>
          <li><strong>ERP connector.</strong> If inventory lives outside Shopify you will oversell without a sync. Cin7, Brightpearl, Unleashed, Linnworks, NetSuite.</li>
          <li><strong>Search and filtering.</strong> Wholesale buyers do not browse, they search by SKU. Past roughly 500 products, consider a dedicated search app.</li>
          <li><strong>Reporting.</strong> Which dealer buys what, and which SKUs move in which channel. Basic's reporting is thin.</li>
        </ul>
      </section>

      <!-- A div, not a section: this closes the article rather than starting a
           new part of it, and a <section> with no heading names no region. -->
      <div class="post-outro">
        <div class="callout callout-tip">
          <?= tip_bulb() ?>
          <p>
            <span class="tip-k">Wrapping up:</span>
            Start on Basic. Native B2B, the Trade theme, Shopify Forms and Shopify Flow will carry your first
            twenty to thirty wholesale accounts — buy an app when you can point at the wall you have hit, not before.
          </p>
          <p>
            Explore our all <a href="/#apps">Shopify Apps</a> to see how we support brands
            like yours. Or, if you want to discuss a project,
            <a href="#contact">get in touch with our team</a>.
          </p>
        </div>
      </div>

    </article>
  </div>
</div>

<!-- ============== RELATED ============== -->
<section id="blog" aria-labelledby="related-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Keep reading</div>
        <h2 id="related-title" style="margin-top:14px">Related guides.</h2>
      </div>
      <div class="eyebrow">More on B2B &amp; quote-based selling</div>
    </div>
    <div class="blog">
      <!-- The app is a product page, not a post, so it is not in the registry —
           but it is the most useful next click from this article, so it keeps
           its hand-written card and the first slot. -->
      <a class="post" href="/dqlab-request-quote-hide-price">
        <div class="cover-art cv-form" aria-hidden="true">
          <span class="cv-eyebrow">Shopify app · Deque Lab</span>
          <span class="cv-title">Hide the price, collect a <b>quote</b></span>
          <div class="cv-card cv-form">
            <span class="cv-field"></span><span class="cv-field short"></span>
            <span class="cv-field"></span><span class="cv-btn">Request a quote</span>
          </div>
          <span class="cv-foot">Free plan · $9/month</span>
        </div>
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
        <h5>SaaS</h5>
        <ul>
          <li><a href="/#saas-alliances">Alliances PRO</a></li>
          <li><a href="/#saas-planner">Muslim Daily Planner</a></li>
          <li><a href="/#saas">All SaaS products</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Shopify apps">
        <h5>Shopify Apps</h5>
        <ul>
          <li><a href="/dqlab-request-quote-hide-price">Request a Quote &amp; Hide Price</a></li>
          <li><a href="/#app-appointment">Appointment Booking</a></li>
          <li><a href="/#apps">All Shopify apps</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Connect with us">
        <h5>Connect</h5>
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
