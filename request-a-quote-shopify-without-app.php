<?php
declare(strict_types=1);

/* Deque Lab — Shopify quote form tutorial. Metadata comes from the post registry. */

require __DIR__ . '/inc/lib.php';
require __DIR__ . '/inc/views.php';
require __DIR__ . '/inc/posts.php';

// ---- Post metadata (drives markup + JSON-LD) ----
// Registry, not literals: /blogs, the homepage row and the related cards render
// from the same entry, so a title can never say one thing in a listing and
// another on the article. Edit inc/posts.php to change it.
$POST = dq_post('request-a-quote-shopify-without-app');

// Page metadata targets the no-app implementation tutorial.
$CONFIG['page_title']       = $POST['title'];
$CONFIG['page_description'] = $POST['excerpt'];
$CONFIG['page_keywords'] = 'Shopify request a quote without an app, Shopify quote form, Liquid contact form, Shopify custom product template';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/' . $POST['slug'];
// Reuse the RFQ guide artwork for social previews.
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/og-request-a-quote-shopify.jpg';
$CONFIG['og_image_alt']     = 'Shopify Request a Quote (RFQ) — a storefront with a Request a Quote form beside a quote summary listing three products and a Submit Quote Request button.';

// Route implementation questions through the shared contact form.
$contact_default_interest = 'DQ: B2B Request a Quote (RFQ)';
$contact_eyebrow = 'Selling on quotes?';
$contact_heading = 'Not sure quoting<br>is worth an app?';
$contact_lede    = 'Tell us what you sell and how often you negotiate. We will tell you honestly whether draft orders are enough, or which quoting app fits — usually within a business day.';

require __DIR__ . '/inc/contact-process.php';

// Running total for the "times read" meta. Returns 0 if unavailable — we hide it then.
$view_count = dq_views_record($POST['slug']);

// Same decorative callout bulb as the original RFQ guide.
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

$TOC_GROUPS = ['Start here' => ['summary' => 'Summary',
'what-it-does' => 'What does a no-app Shopify quote form actually do?',
'approaches' => 'What is the best way to add a quote button without an app?'],
'Build the form' => ['setup' => 'How to add a Request a Quote form to a Shopify product page',
'draft-orders' => 'How do you turn the request into an order?'],
'Choose your workflow' => ['limitations' => 'What are the problems with a no-app quote form?',
'apps' => 'Why use a third-party Shopify quote app?',
'decision' => 'When is the no-app method enough?',
'seo' => 'Will a Request a Quote button affect SEO?',
'faq' => 'Frequently asked questions',
'next-steps' => 'Choose the setup that matches your sales process'],
'References' => ['sources' => 'Sources and implementation notes']];
$TOC = array_merge(...array_values($TOC_GROUPS));
function h2(array $toc, string $id): string { return '<h2 id="' . e($id) . '">' . e($toc[$id]) . '</h2>'; }
$FAQS = [['q' => 'Can I add Request a Quote to Shopify without an app?',
'a' => 'Yes. You can add a native contact form to a product template using Liquid. The form emails the request, and your team handles the sales process afterward.'],
['q' => 'Can customers submit a quote request without leaving the product page?',
'a' => 'They can fill in the form on the product page. The native submission can reload or redirect the page and may involve an anti-spam challenge. This tutorial does not promise an AJAX submission without navigation.'],
['q' => 'Does a contact form create a Shopify draft order?',
'a' => 'The example here does not. Your team creates the draft order after reviewing the request and agreeing on the details.'],
['q' => 'Can I keep Add to Cart and Request a Quote together?',
'a' => 'Yes. That can suit a store offering standard purchases alongside custom or bulk inquiries. Explain when customers should use each option.'],
['q' => 'Can the form automatically capture the selected variant?',
'a' => 'Yes, with additional theme-specific integration. The starter code asks for specifications instead. Reliable automatic capture must follow variant changes made after page load.'],
['q' => 'Do I need Shopify Plus for this form?',
'a' => 'This approach uses a theme contact form rather than a Plus-only B2B checkout feature. You need a store with an Online Store theme that supports the implementation.'],
['q' => 'Is an app always better than custom code?',
'a' => 'No. A simple form can meet a simple need. An app becomes useful when its workflow features save enough time or prevent enough errors to justify the cost and dependency.']];
$SOURCES = [['Shopify contact form documentation',
'https://shopify.dev/docs/storefronts/themes/customer-engagement/add-contact-form'],
['Shopify contact-page help',
'https://help.shopify.com/en/manual/online-store/themes/customizing-themes/common-customizations/add-contact-page'],
['Shopify draft orders and invoices',
'https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders'],
['Google product structured-data requirements',
'https://developers.google.com/search/docs/appearance/structured-data/product'],
['Shopify product templates',
'https://help.shopify.com/en/manual/online-store/themes/theme-structure/templates'],
['Shopify theme updates',
'https://help.shopify.com/en/manual/online-store/themes/managing-themes/updating-themes']];
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
      .post-body .quote-code { max-width: 100%; overflow-x: auto; padding: 24px; border: 1px solid var(--line); border-radius: 12px; background: var(--paper); font-size: 13px; line-height: 1.7; }
    .post-body .quote-code code { padding: 0; background: transparent; font-size: inherit; white-space: pre; }
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
        <div class="eyebrow">Guide · No-app quote form</div>
        <nav class="crumbs" aria-label="Breadcrumb"><a href="/">Home</a><span aria-hidden="true">/</span><a href="/blogs">Blog</a><span aria-hidden="true">/</span><span>Quote without an app</span></nav>
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
                Email inquiry
              </span>

              <span class="chip c4 amber">
                <span class="ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 2h9l5 5v15H6z"/><path d="M15 2v5h5M9.5 13h5M9.5 17h5"/></svg></span>
                Manual order
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
    <section>
    <?= h2($TOC, 'summary') ?>

    <div class="callout callout-tip exec">
      <?= tip_bulb() ?>
      <ul class="ul-mb-1">
        <li>
          <strong>No app needed:</strong> 
          Request a quote button using Shopify’s basic contact form.
        </li>
        <li>
          <strong>Get emails:</strong> 
          The customer's request goes straight to your inbox.
        </li>
        <li>
          <strong>Make the sale:</strong>
          Agree on a price and create a Draft Order yourself.
        </li>
      </ul>
    </div>

    <p>
      If you just sell one Shopify B2B product at a time, this solution is fine. 
      But if you sell many request a quote products, negotiate prices, or want automatic orders, 
      you need a dedicated quote app.
    </p>

    <p>
      This guide explains the setup, the work it leaves to your team, and how to decide whether an app is worth using.
    </p>
</section>

<section>
<?= h2($TOC, 'what-it-does') ?>
<p>A basic quote form collects an inquiry. It can ask for the buyer’s contact details, product requirements, quantity, and delivery location. Shopify supports a native Liquid contact form with custom fields. <a href="https://shopify.dev/docs/storefronts/themes/customer-engagement/add-contact-form">Shopify contact form documentation</a></p>
<p>The example in this guide sends an email. It does not automatically calculate a price, reserve inventory, create a quote record, or create an order.</p>
<p>For example, a buyer might request 200 printed tote bags delivered to Manchester. Your team still needs to check the printing requirements, stock, delivery cost, and lead time before replying with an offer.</p>
</section><section>
<?= h2($TOC, 'approaches') ?>
<p>For a simple product inquiry, I recommend a button-style disclosure that opens a form on the same product page. It keeps the product context visible and avoids sending the customer to a separate general contact page.</p>
<p>This is an implementation recommendation, not a claim that every store will convert better with it. Test it with your own customers.</p>
<div class="stable-wrap" tabindex="0" role="region" aria-label="Quote form approaches"><table class="stable"><thead><tr><th scope="col">Approach</th><th scope="col">Suitable use</th><th scope="col">Main limitation</th></tr></thead><tbody>
<tr><th scope="row">Product button linking to a contact page</th><td>Fastest starting point</td><td>Product details must be entered or passed to the form</td></tr>
<tr><th scope="row">Expandable form on the product page</th><td>Simple product-specific inquiries</td><td>Quote processing remains manual</td></tr>
<tr><th scope="row">Custom modal form</th><td>Stores that need an overlay design</td><td>Requires focus management, keyboard support, and more testing</td></tr>
<tr><th scope="row">Custom quote backend</th><td>Unusual workflows with development resources</td><td>You own development, hosting, security, and maintenance</td></tr>
</tbody></table></div>
<p>A standalone theme section is a practical starting point. It avoids depending on the internal markup of the theme’s purchase form. Placing the button directly beside Add to Cart requires theme-specific integration.</p>
</section>
<section>
<?= h2($TOC, 'setup') ?>
<h3>1. Work in a duplicate theme</h3>
<p>In the theme editor, open <b>Products → Create template</b>, name it <code>quote</code>, and base it on your default product template. Online Store 2.0 uses <code>product.quote.json</code>; Liquid markup belongs in a section, not in that JSON file. Vintage themes need a theme-specific Liquid template adaptation. <a href="https://help.shopify.com/en/manual/online-store/themes/theme-structure/templates">Shopify product templates</a>.</p>
<p>Create a duplicate of your theme before editing it. Use its preview to check the form before publishing.</p>
<p>The example below is designed for a Shopify Online Store 2.0 product template that supports adding sections. It is not a headless storefront implementation.</p>
<p>If only some products need quotes, use a separate product template for those products. A change to the default product template can affect every product assigned to it.</p>
<h3>2. Create a quote section</h3>
<p>In your theme’s code editor, create a file in the Sections directory named product-quote-form.liquid and paste the following code.</p>
<p>This example deliberately asks customers to type the required variant or specifications. It does not pretend to stay synchronized with every theme’s variant picker. Product title, product ID, and product URL are included automatically.</p>
<pre class="quote-code" tabindex="0" aria-label="Liquid code for product-quote-form.liquid"><code>{% if product != blank %}
  {% assign quote_id = &#x27;ProductQuote-&#x27; | append: section.id %}
  &lt;section id=&quot;{{ quote_id }}-wrapper&quot; class=&quot;product-quote&quot;&gt;
    {% form &#x27;contact&#x27;, id: quote_id %}
      &lt;details {% if form.posted_successfully? or form.errors %}open{% endif %}&gt;
        &lt;summary&gt;Request a quote&lt;/summary&gt;
        &lt;div class=&quot;product-quote__fields&quot;&gt;
          {% if form.posted_successfully? %}
            &lt;p role=&quot;status&quot;&gt;Thank you. Your request has been sent.&lt;/p&gt;
          {% else %}
            {% if form.errors %}
              &lt;div role=&quot;alert&quot;&gt;{{ form.errors | default_errors }}&lt;/div&gt;
            {% endif %}

            &lt;p&gt;Request a quote for &lt;strong&gt;{{ product.title | escape }}&lt;/strong&gt;.&lt;/p&gt;
            &lt;p&gt;Tell us what you need. We will reply by email.&lt;/p&gt;

            &lt;input type=&quot;hidden&quot; name=&quot;contact[Request type]&quot; value=&quot;Product quote&quot;&gt;
            &lt;input type=&quot;hidden&quot; name=&quot;contact[Product]&quot; value=&quot;{{ product.title | escape }}&quot;&gt;
            &lt;input type=&quot;hidden&quot; name=&quot;contact[Product ID]&quot; value=&quot;{{ product.id }}&quot;&gt;
            &lt;input type=&quot;hidden&quot; name=&quot;contact[Product URL]&quot; value=&quot;{{ canonical_url | escape }}&quot;&gt;

            &lt;label for=&quot;{{ quote_id }}-name&quot;&gt;Name *&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-name&quot; name=&quot;contact[name]&quot; type=&quot;text&quot;
              autocomplete=&quot;name&quot; required value=&quot;{{ form.name | escape }}&quot;&gt;

            &lt;label for=&quot;{{ quote_id }}-email&quot;&gt;Email *&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-email&quot; name=&quot;contact[email]&quot; type=&quot;email&quot;
              autocomplete=&quot;email&quot; required value=&quot;{{ form.email | escape }}&quot;&gt;

            &lt;label for=&quot;{{ quote_id }}-company&quot;&gt;Company&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-company&quot; name=&quot;contact[Company]&quot; type=&quot;text&quot;
              autocomplete=&quot;organization&quot;&gt;

            &lt;label for=&quot;{{ quote_id }}-quantity&quot;&gt;Quantity requested *&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-quantity&quot; name=&quot;contact[Quantity]&quot; type=&quot;number&quot;
              min=&quot;1&quot; step=&quot;1&quot; required&gt;

            &lt;label for=&quot;{{ quote_id }}-specs&quot;&gt;Variant or specifications *&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-specs&quot; name=&quot;contact[Specifications]&quot; type=&quot;text&quot;
              placeholder=&quot;Size, color, material, or N/A&quot; required&gt;

            &lt;label for=&quot;{{ quote_id }}-destination&quot;&gt;Delivery country and postal code&lt;/label&gt;
            &lt;input id=&quot;{{ quote_id }}-destination&quot; name=&quot;contact[Delivery destination]&quot;
              type=&quot;text&quot;&gt;

            &lt;label for=&quot;{{ quote_id }}-message&quot;&gt;Project details *&lt;/label&gt;
            &lt;textarea id=&quot;{{ quote_id }}-message&quot; name=&quot;contact[body]&quot; rows=&quot;5&quot;
              placeholder=&quot;Customization, delivery date, or other requirements&quot;
              required&gt;{{ form.body | escape }}&lt;/textarea&gt;

            &lt;p&gt;Submitting this form requests a quote. It does not place an order.&lt;/p&gt;
            {% if shop.privacy_policy.url != blank %}
              &lt;p&gt;&lt;a href=&quot;{{ shop.privacy_policy.url }}&quot;&gt;Read our privacy policy&lt;/a&gt;&lt;/p&gt;
            {% endif %}
            &lt;button type=&quot;submit&quot;&gt;Send quote request&lt;/button&gt;
          {% endif %}
        &lt;/div&gt;
      &lt;/details&gt;
    {% endform %}
  &lt;/section&gt;

  &lt;style&gt;
    #{{ quote_id }}-wrapper { max-width: 760px; margin: 28px auto; padding: 0 20px; }
    #{{ quote_id }}-wrapper summary,
    #{{ quote_id }}-wrapper button {
      padding: 14px 20px; border: 1px solid #222; border-radius: 6px;
      background: #222; color: #fff; cursor: pointer; font: inherit;
    }
    #{{ quote_id }}-wrapper summary { width: fit-content; }
    #{{ quote_id }}-wrapper .product-quote__fields {
      display: grid; gap: 12px; margin-top: 20px;
    }
    #{{ quote_id }}-wrapper input:not([type=&quot;hidden&quot;]),
    #{{ quote_id }}-wrapper textarea {
      width: 100%; box-sizing: border-box; padding: 12px;
      border: 1px solid #777; border-radius: 4px;
      background: #fff; color: #222; font: inherit;
    }
    #{{ quote_id }}-wrapper :focus-visible { outline: 3px solid #2367d1; outline-offset: 3px; }
  &lt;/style&gt;
{% endif %}

{% schema %}
{
  &quot;name&quot;: &quot;Product quote form&quot;,
  &quot;tag&quot;: &quot;div&quot;,
  &quot;enabled_on&quot;: { &quot;templates&quot;: [&quot;product&quot;] },
  &quot;settings&quot;: [],
  &quot;presets&quot;: [{ &quot;name&quot;: &quot;Product quote form&quot; }]
}
{% endschema %}</code></pre>
<p>The disclosure uses native HTML and needs no custom JavaScript. The trigger looks like a button, but its HTML element is a summary, which opens the form inside details.</p>
<p>The Liquid form tag and contact[...] field naming follow Shopify’s documented contact-form mechanism. This is an original implementation example; it has not been tested in your store. <a href="https://shopify.dev/docs/storefronts/themes/customer-engagement/add-contact-form">Shopify contact form documentation</a></p>
<h3>3. Add the section to your product template</h3>
<p>Open the duplicate theme’s visual editor, select the product template, and choose Add section. Select Product quote form and place it beneath the main product information section. Save and preview.</p>
<p>This creates the button and form on the product page. It does not require a separate quote page.</p>
<h3>Optional: hide the price and purchase buttons</h3><p>On the quote template, hide or remove the Price and Buy buttons blocks if your theme supports it. Keep them if buyers should choose between buying and requesting a bulk quote. Do not comment out price or cart code globally in a shared <code>main-product.liquid</code> file: that can affect other templates too. If you need code changes, scope them to <code>template.suffix == 'quote'</code> and adapt them to the theme.</p><p>After testing and publishing the theme, open each intended product in Shopify admin and select <b>quote</b> under <b>Theme template</b>. The assignment list uses templates from the published theme; preview the duplicate before this step.</p>
<p>For a button beside the price or purchase controls, have a developer adapt the code to your theme’s product blocks. Never nest this contact form inside an existing product purchase form: nested HTML forms are invalid and can submit the wrong data.</p>
<h3>4. Check where requests are delivered</h3>
<p>Shopify sends native contact-form submissions to the store’s sender email address, configured in Notifications settings. Shopify also documents that the contact-form email subject cannot be customized. A field named Request type adds information to the message; it does not change the subject. <a href="https://help.shopify.com/en/manual/online-store/themes/customizing-themes/common-customizations/add-contact-page">Shopify contact-page help</a></p>
<h3>5. Test the complete customer journey</h3>
<p>Before publishing, check the following:</p>
<ul><li>Submit a request and confirm that the email arrives with the correct product and customer details.</li><li>Submit invalid or missing required information and inspect the errors.</li><li>Check the confirmation after submission and any anti-spam challenge.</li><li>Test mobile layout, keyboard navigation, and the open/close control.</li><li>Confirm that Add to Cart and accelerated checkout still work if you keep them.</li><li>Check products with multiple variants and your other storefront languages.</li><li>Check which custom fields need to be entered again after an unsuccessful submission.</li></ul>
<p>This starter preserves the standard name, email, and message values where Shopify supplies them. It does not implement persistent recovery of every custom field. The integer quantity input assumes products sold in whole units; adapt it for fractional quantities.</p>
</section><section>
<?= h2($TOC, 'draft-orders') ?>
<p>A workable manual process is:</p>
<ol><li>Review the email and confirm the requested variant, quantity, customization, and destination.</li><li>Check availability and prepare pricing, shipping, and delivery expectations.</li><li>Send your offer and agree on any changes by email.</li><li>Create a Shopify draft order with the correct customer, items, and agreed pricing.</li><li>Send its invoice with a checkout link.</li><li>Check payment and fulfillment status in Shopify.</li></ol>
<p>Shopify draft orders support manual sales and invoices with secure checkout links. A paid draft becomes a regular order. The contact form in this tutorial does not automatically perform those steps. <a href="https://help.shopify.com/en/manual/fulfillment/managing-orders/create-orders">Shopify draft orders and invoices</a></p>
</section><section>
<?= h2($TOC, 'limitations') ?>
<h3>1. Email becomes your quote management system</h3>
<p>This implementation has no quote dashboard, owner assignment, request number, or status history. As requests grow, it becomes easier to miss a reply or lose track of the latest offer.</p>
<p>A shared inbox and a spreadsheet can help, but someone must maintain them.</p>
<h3>2. Product and variant details can be incomplete</h3>
<p>A buyer might type “large blue” without enough information to identify the correct SKU. This tutorial intentionally uses a free-text specification field, so your team must verify it.</p>
<p>A more advanced implementation can capture variant IDs from the active product selection. That requires integration with the theme’s variant behavior, including changes made after the page loads. Reading an initial Liquid value alone does not guarantee that it stays current.</p>
<h3>3. There is no multi-product quote basket</h3>
<p>A single-product inquiry is manageable. A buyer requesting 20 different products needs a better way to collect products, variants, and quantities into one request.</p>
<p>You can build that yourself, but it is substantially more work than adding a contact form.</p>
<h3>4. Negotiation has no structured record</h3>
<p>Email works for a small number of revisions. More complicated deals may need versioned offers, line-item adjustments, attachments, an expiry date, and an acceptance history.</p>
<p>The starter form does not provide these features. Sending another email does not automatically update a structured quote record.</p>
<h3>5. Creating draft orders takes time</h3>
<p>You must transfer the agreed details into Shopify. Re-entering quantities, addresses, and prices creates opportunities for mistakes.</p>
<p>An integration can reduce this work, but it still needs careful handling of taxes, shipping, currency, stock, and pricing changes.</p>
<h3>6. Follow-ups depend on your process</h3>
<p>The form does not remind a salesperson to reply or contact a buyer who has gone quiet. You need a separate routine, task system, or automation.</p>
<h3>7. Hiding a button is not purchase protection</h3>
<p>Adding this form leaves the existing purchase options in place. That is useful when shoppers should be able to buy retail quantities or request a bulk quote.</p>
<p>For quote-only products, removing the visible Buy buttons block is a separate customization. Do not assume that hiding Add to Cart or a price with CSS prevents purchases or keeps pricing private. Check quick-add cards, sticky purchase controls, cart routes, product data, structured data, and other sales channels. Enforcement needs a solution appropriate to the store’s actual architecture.</p>
<h3>8. Maintenance remains your responsibility</h3>
<p>A standalone section reduces some coupling to the theme, but redesigns and theme migrations still need testing. You also own accessibility, translations, field changes, and troubleshooting.</p>
<p>Updates do not always erase custom code. Shopify can carry compatible changes forward, but conflicting edits may need to be reapplied. Keep a backup and check the updated theme before publishing. <a href="https://help.shopify.com/en/manual/online-store/themes/managing-themes/updating-themes">Shopify theme updates</a>.</p>
<p>The absence of an app subscription does not mean the setup has no operating cost.</p>
<h3>9. Email and spam handling need attention</h3>
<p>Shopify analyzes the contact message field for spam and can mark suspicious messages with a spam prefix. Review filtering so legitimate requests are not overlooked. <a href="https://help.shopify.com/en/manual/online-store/themes/customizing-themes/common-customizations/add-contact-page">Shopify contact-page help</a></p>
<p>Adding a file input alone is also not a complete attachment solution. This tutorial does not include file uploads, storage, or attachment handling.</p>
<h3>10. Submitted details are untrusted input</h3>
<p>Customers can edit hidden fields and bypass browser validation. Check product IDs, specifications, and quantities against your catalog before making an offer. If you later automate pricing or order creation, validate those values in a trusted backend. Never put Shopify Admin API credentials in theme JavaScript.</p>
</section><section>
<?= h2($TOC, 'apps') ?>
<p>You do not need an app just to display a button. The reason to consider one is the workflow after the customer submits the request.</p>
<p>Depending on the app and plan, a quote tool may provide a quote basket, request dashboard, notifications, quote documents, negotiations, reminders, and draft-order integration. Verify the exact features before choosing one; these are evaluation criteria, not features guaranteed by every quote app.</p>
<div class="stable-wrap" tabindex="0" role="region" aria-label="Manual form versus quote app"><table class="stable"><thead><tr><th scope="col">Requirement</th><th scope="col">This no-app form</th><th scope="col">What to verify in an app</th></tr></thead><tbody>
<tr><th scope="row">Product-page request button</th><td>Included</td><td>Placement and theme compatibility</td></tr>
<tr><th scope="row">Product context</th><td>Title, ID, URL; typed specifications</td><td>Accurate variant and SKU capture</td></tr>
<tr><th scope="row">Multi-product request</th><td>Not included</td><td>Quote basket and quantity editing</td></tr>
<tr><th scope="row">Quote tracking</th><td>Manual inbox process</td><td>Statuses, owners, and history</td></tr>
<tr><th scope="row">Negotiation</th><td>Email replies</td><td>Revised offers and acceptance records</td></tr>
<tr><th scope="row">PDF quote</th><td>Create separately</td><td>Templates and document export</td></tr>
<tr><th scope="row">Follow-up reminders</th><td>Separate process</td><td>Timing and automation controls</td></tr>
<tr><th scope="row">Order creation</th><td>Manual draft order</td><td>Draft-order or checkout integration</td></tr>
<tr><th scope="row">Price visibility</th><td>Separate customization</td><td>Where prices remain accessible</td></tr>
<tr><th scope="row">Data ownership</th><td>Email records</td><td>Export options and uninstall behavior</td></tr>
</tbody></table></div>
<p>Apps have trade-offs too: recurring fees, dependency on the provider, theme conflicts, feature limits, and possible storefront performance impact. Test the actual app in your theme and run a complete quote-to-payment journey before relying on it.</p>
<p>A custom backend is another option if your workflow is unusual and you have the resources to maintain it. You are not limited to either an inbox or a public app.</p>
</section><section>
<?= h2($TOC, 'decision') ?>
<p>Use it when requests are occasional, buyers usually ask about one product, and your team can reliably handle pricing and follow-ups by email.</p>
<p>Consider an app when requests involve many products, several people handle sales, customers frequently negotiate, or manual order creation delays replies.</p>
<p>There is no universal number of requests that makes an app necessary. Measure the work. For example, 30 requests per month taking 12 minutes of administration each equals six hours of work. This is an illustration, not a Shopify benchmark. Compare your actual time and error rate against the app’s cost and capabilities.</p>
</section><section>
<?= h2($TOC, 'seo') ?>
<p>The presence of a quote button does not, by itself, determine whether a product page can rank. Keep useful product descriptions, specifications, images, and internal links accessible.</p>
<p>If you remove public pricing, review the product’s structured data and any shopping feeds. Do not put a fake zero price in markup to represent “contact us for a quote.” Product search features have specific data requirements, so changing the offer information can affect eligibility. Organic indexing and product rich-result eligibility are different questions. <a href="https://developers.google.com/search/docs/appearance/structured-data/product">Google product structured-data requirements</a></p>
</section><section><?= h2($TOC, 'faq') ?>
<div class="faq"><?php foreach ($FAQS as $i => $faq): ?><details<?= $i === 0 ? ' open' : '' ?>><summary><?= e($faq['q']) ?></summary><div class="a"><?= e($faq['a']) ?></div></details><?php endforeach; ?></div>
</section><section>
<?= h2($TOC, 'next-steps') ?>
<p>Start with a product-page form if collecting an inquiry is all you need. Move to a dedicated quote workflow when tracking, negotiation, and order preparation become difficult to manage manually.</p>
<p>For a wider explanation of quote workflows, read our <a href="/request-a-quote-shopify">Shopify Request a Quote guide</a>.</p>
<p>Need to manage more than the initial inquiry? Explore <a href="/dq-b2b-request-quote-rfq">DQ: B2B Request a Quote (RFQ)</a> and compare its current workflow with the manual setup above.</p></section>
<section><?= h2($TOC, 'sources') ?><p>References checked on 10 September 2026. The Liquid example has not been installed or submitted on a live Shopify store.</p><ul><?php foreach ($SOURCES as [$label, $url]): ?><li><a href="<?= e($url) ?>"><?= e($label) ?></a></li><?php endforeach; ?></ul></section>
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
      <?php
        // Every other post, newest first — the registry is the source, so a new
        // article appears here the moment it is added, with no edit to this file.
        foreach (array_slice(array_filter(dq_posts(null, true), static fn(array $p): bool =>
            $p['slug'] !== $POST['slug']), 0, 3) as $rel) {
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
          <li><a href="/#saas">All SaaS products</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Shopify apps">
        <h2>Shopify Apps</h2>
        <ul>
          <li><a href="/dq-b2b-request-quote-rfq">DQ: B2B Request a Quote (RFQ)</a></li>
          <li><a href="/#app-preorder">PreOrder (acquired)</a></li>
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
