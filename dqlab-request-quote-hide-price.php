<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — DQ: Request a Quote & Hide Price (landing page)
 * ============================================================ */

require __DIR__ . '/inc/lib.php';

// Page-level SEO/AEO config (overrides base).
$CONFIG['page_title']       = 'Request a Quote & Hide Price for Shopify — DQLab';
$CONFIG['page_description'] = 'DQLab Request a Quote & Hide Price lets Shopify merchants hide prices and the add-to-cart button, collect quote requests with custom fields, and approve offers from the admin. Free plan available.';
$CONFIG['page_keywords']    = 'request a quote Shopify, hide price Shopify, hide add to cart, quote request app, B2B quote Shopify, call for price, DQLab, Deque Lab';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/dqlab-request-quote-hide-price';
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/dq_request_quote.png';

// Preselect this app in the shared contact form.
$contact_default_interest = 'DQ: Request a Quote / Hide Price (waitlist)';
$contact_eyebrow = 'Section 06 · Contact';
$contact_heading = 'Talk to us about<br>Request a Quote.';
$contact_lede    = 'On Shopify Plus, running B2B, or need a custom quote flow? Tell us what you sell and we\'ll help you set it up — usually within a business day.';

require __DIR__ . '/inc/contact-process.php';

// ---- Content data (drives both markup and JSON-LD) ----
$FAQS = [
    ['q' => 'What does DQ: Request a Quote & Hide Price do?',
     'a' => 'It lets you hide product prices and the add-to-cart button on any Shopify product, and replace them with a "Request a Quote" button. Customers submit a quote request with custom fields, and you approve, edit, or decline each request from your Shopify admin.'],
    ['q' => 'Can I hide prices for specific products or customers?',
     'a' => 'Yes. Target by product, collection, tag, or customer group — for example, hide prices only for wholesale customers, or only until a shopper logs in. Everyone else sees your normal storefront.'],
    ['q' => 'Does it work with Shopify Online Store 2.0 themes?',
     'a' => 'Yes. The quote button and form install as native theme app blocks, so they work with OS 2.0 themes with no code and no theme edits required.'],
    ['q' => 'How much does it cost?',
     'a' => 'There is a Free plan that includes 10 quotes total, a simple quote form, and email support. The Pro plan is $9/month for unlimited quotes, advanced multi-section forms, quote buttons on products and collections, and priority email & WhatsApp support, with a 14-day free trial.'],
    ['q' => 'Do customers need an account to request a quote?',
     'a' => 'No. Quote requests work for guests and logged-in customers. You can optionally require login before prices or the quote form appear, which is common for B2B and wholesale stores.'],
];

$STEPS = [
    ['t' => 'Hide the price & buy button',
     'd' => 'Pick products, collections, or customer groups. The price and add-to-cart button are replaced with a "Request a Quote" button — no theme code.'],
    ['t' => 'Customer submits a request',
     'd' => 'Shoppers add items to a quote and send it with your custom fields (quantity, company, notes). You get an instant email and a record in your admin.'],
    ['t' => 'Approve & send the offer',
     'd' => 'Review each request, set your price, and send an approved quote the customer can accept and check out — or convert it straight to a draft order.'],
];
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- ============== SEO: primary ============== -->
  <title><?= e($CONFIG['page_title']) ?></title>
  <meta name="description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="keywords" content="<?= e($CONFIG['page_keywords']) ?>" />
  <meta name="author" content="<?= e($CONFIG['site_author']) ?>" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="theme-color" content="#0e1210" />
  <meta name="color-scheme" content="light" />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />

  <!-- ============== Open Graph ============== -->
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="<?= e($CONFIG['site_name']) ?>" />
  <meta property="og:locale" content="<?= e($CONFIG['site_locale']) ?>" />
  <meta property="og:title" content="<?= e($CONFIG['page_title']) ?>" />
  <meta property="og:description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta property="og:url" content="<?= e($CONFIG['canonical_url']) ?>" />
  <meta property="og:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta property="og:image:alt" content="DQ: Request a Quote & Hide Price for Shopify" />

  <!-- ============== Twitter / X ============== -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:title" content="<?= e($CONFIG['page_title']) ?>" />
  <meta name="twitter:description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="twitter:image" content="<?= e($CONFIG['og_image']) ?>" />

  <!-- ============== Icons ============== -->
  <link rel="icon" type="image/png" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="apple-touch-icon" href="<?= e($CONFIG['logo']) ?>" />

  <!-- ============== Performance hints ============== -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="dns-prefetch" href="https://fonts.googleapis.com" />

  <!-- ============== Fonts & styles ============== -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <!-- ============== Structured data (JSON-LD) ============== -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "SoftwareApplication",
        "@id": "<?= e($CONFIG['canonical_url']) ?>#app",
        "name": "DQ: Request a Quote & Hide Price",
        "operatingSystem": "Shopify",
        "applicationCategory": "BusinessApplication",
        "url": "<?= e($CONFIG['canonical_url']) ?>",
        "description": "<?= e($CONFIG['page_description']) ?>",
        "publisher": { "@type": "Organization", "name": "<?= e($CONFIG['site_name']) ?>", "url": "<?= e($CONFIG['site_url']) ?>/" },
        "offers": [
          {"@type":"Offer","name":"Free","price":"0","priceCurrency":"USD","description":"10 quotes total, a simple quote form with one section, email support, and a quote button on product pages."},
          {"@type":"Offer","name":"Pro","price":"9","priceCurrency":"USD","description":"Unlimited quotes, advanced multi-section quote forms, Page/Popup/Drawer widget actions, quote buttons on products and collections, and priority email & WhatsApp support. Billed monthly."}
        ]
      },
      {
        "@type": "FAQPage",
        "@id": "<?= e($CONFIG['canonical_url']) ?>#faq",
        "mainEntity": [
          <?php foreach ($FAQS as $i => $f): ?>
          {"@type":"Question","name":<?= json_encode($f['q'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>,"acceptedAnswer":{"@type":"Answer","text":<?= json_encode($f['a'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>}}<?= $i < count($FAQS) - 1 ? ',' : '' ?>
          <?php endforeach; ?>
        ]
      },
      {
        "@type": "HowTo",
        "name": "How to hide prices and collect quote requests on Shopify",
        "step": [
          <?php foreach ($STEPS as $i => $s): ?>
          {"@type":"HowToStep","position":<?= $i + 1 ?>,"name":<?= json_encode($s['t'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>,"text":<?= json_encode($s['d'], JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>}<?= $i < count($STEPS) - 1 ? ',' : '' ?>
          <?php endforeach; ?>
        ]
      },
      {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {"@type":"ListItem","position":1,"name":"Home","item":"<?= e($CONFIG['site_url']) ?>/"},
          {"@type":"ListItem","position":2,"name":"Shopify Apps","item":"<?= e($CONFIG['site_url']) ?>/#apps"},
          {"@type":"ListItem","position":3,"name":"Request a Quote & Hide Price","item":"<?= e($CONFIG['canonical_url']) ?>"}
        ]
      }
    ]
  }
  </script>

  <style>
    html { scroll-padding-top: 84px; }

    /* Logo left · links centered · Install right */
    .nav-inner { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; }
    .nav-inner .nav-links { justify-self: center; }
    .nav-inner .nav-cta { justify-self: end; }

    /* ============ HERO ============ */
    .qhero { padding: 24px 0 0; }
    .qhero .stage {
      background: #fff; color: var(--ink);
      border: 1px solid var(--line); border-radius: 28px;
      padding: clamp(32px, 4vw, 56px) clamp(32px, 5vw, 64px);
      position: relative; overflow: hidden;
      display: grid; grid-template-columns: 1fr 1.05fr; gap: 48px; align-items: center;
    }
    @media (max-width: 960px) { .qhero .stage { grid-template-columns: 1fr; gap: 32px; } }
    .qhero .stage::before {
      content: ""; position: absolute; inset: 0; pointer-events: none;
      background: radial-gradient(760px 480px at 100% 0%, color-mix(in oklch, var(--accent) 10%, transparent), transparent 60%);
    }
    .qhero .lead { position: relative; }
    .qhero h1 {
      font-size: clamp(38px, 5vw, 62px); line-height: 1.02;
      letter-spacing: -0.035em; font-weight: 500; margin-top: 18px;
    }
    .qhero h1 .accent { color: var(--accent); }
    .qhero .lede { margin-top: 20px; font-size: clamp(15px, 1.2vw, 17px); max-width: 48ch; color: var(--muted); line-height: 1.55; }
    .qhero .cta { margin-top: 26px; display: flex; gap: 10px; flex-wrap: wrap; }
    .qhero .rating {
      margin-top: 22px; display: inline-flex; align-items: center; gap: 10px;
      font-family: var(--f-mono); font-size: 12px; color: var(--muted);
    }
    .qhero .rating .stars { color: var(--accent); letter-spacing: 2px; }

    .hero-media { position: relative; }
    .browser {
      background: #fff; border: 1px solid var(--line); border-radius: 16px;
      overflow: hidden;
    }
    .browser .bar { display: flex; align-items: center; gap: 6px; padding: 10px 14px; border-bottom: 1px solid var(--line); background: var(--paper-2); }
    .browser .bar .dot { width: 10px; height: 10px; border-radius: 50%; background: #d8d4c6; }
    .browser .bar .url { margin-left: 10px; font-family: var(--f-mono); font-size: 11px; color: var(--muted); }
    .browser video, .browser img { width: 100%; height: auto; display: block; background: var(--paper-2); }

    /* ---- Coded product-page mockup (replaces static comparison image) ---- */
    .pm { display: grid; grid-template-columns: 1fr 1fr; }
    @media (max-width: 420px) { .pm { grid-template-columns: 1fr; } }
    .pm-photo {
      position: relative; overflow: hidden;
      background: linear-gradient(165deg, #f6f4ee 0%, #eceadf 55%, #e3e0d4 100%);
      display: flex; align-items: flex-end; justify-content: center;
    }
    .pm-photo svg.chair { position: relative; width: 84%; height: auto; margin-bottom: 8%; }
    .pm-tag {
      position: absolute; top: 12px; left: 12px; z-index: 2;
      font-family: var(--f-mono); font-size: 10px; letter-spacing: .04em; text-transform: uppercase;
      color: var(--ink); background: rgba(255,255,255,.82); border: 1px solid var(--line);
      padding: 4px 8px; border-radius: 999px; backdrop-filter: blur(4px);
    }
    .pm-info { padding: clamp(16px, 3.4vw, 26px); display: flex; flex-direction: column; gap: 14px; }
    .pm-title { font-size: clamp(17px, 2vw, 22px); font-weight: 600; letter-spacing: -0.015em; line-height: 1.15; }
    .pm-price { position: relative; min-height: 34px; }
    .pm-state { position: absolute; inset: 0; display: flex; align-items: center; gap: 10px; }
    .pm-state.is-before { animation: pmBefore 7s ease-in-out infinite; }
    .pm-state.is-after  { animation: pmAfter  7s ease-in-out infinite; }
    .pm-amt { font-weight: 700; font-size: 22px; letter-spacing: -0.01em; color: var(--ink); }
    .pm-price .lock {
      width: 34px; height: 34px; flex: none; display: grid; place-items: center;
      border-radius: 9px; background: var(--paper-2); border: 1px solid var(--line); color: var(--ink);
    }
    .pm-price .lock svg { width: 17px; height: 17px; }
    .pm-hidden { font-weight: 600; font-size: 15px; color: var(--ink); white-space: nowrap; }

    /* Before -> After crossfade shared by the price row and the button */
    @keyframes pmBefore {
      0%, 34%  { opacity: 1; transform: translateY(0); }
      44%, 90% { opacity: 0; transform: translateY(-7px); }
      100%     { opacity: 1; transform: translateY(0); }
    }
    @keyframes pmAfter {
      0%, 34%  { opacity: 0; transform: translateY(7px); }
      44%, 90% { opacity: 1; transform: translateY(0); }
      100%     { opacity: 0; transform: translateY(7px); }
    }
    @media (prefers-reduced-motion: reduce) {
      .pm-state, .pm-btnstate { animation: none !important; }
      .pm-state.is-before, .pm-btnstate.is-cart { opacity: 0; }
      .pm-state.is-after,  .pm-btnstate.is-quote { opacity: 1; }
    }
    .pm-stock { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; color: var(--muted); }
    .pm-stock .d { width: 8px; height: 8px; border-radius: 50%; background: #33a35a; box-shadow: 0 0 0 3px rgba(51,163,90,.15); }
    .pm-qlabel { font-size: 13px; color: var(--muted); margin-bottom: -6px; }
    .pm-stepper {
      display: inline-flex; align-items: center; align-self: flex-start;
      border: 1px solid var(--line-2); border-radius: 10px; overflow: hidden; background: #fff;
    }
    .pm-stepper span, .pm-stepper b {
      display: grid; place-items: center; height: 38px; font-size: 15px;
    }
    .pm-stepper span { width: 40px; color: var(--muted); }
    .pm-stepper b { width: 46px; font-weight: 500; border-inline: 1px solid var(--line); color: var(--ink); }
    .pm-quote {
      margin-top: 4px;
      position: relative; overflow: hidden;
      width: 200px; max-width: 100%; height: 44px;
      border: 0; border-radius: 12px; cursor: default;
      background: black;
      color: var(--accent-ink);
      font-size: 14px;
      font-weight: 600;
    }
    .pm-btnstate {
      position: absolute; inset: 0;
      display: inline-flex; align-items: center; justify-content: center; gap: 10px;
    }
    .pm-btnstate.is-cart  { animation: pmBefore 7s ease-in-out infinite; }
    .pm-btnstate.is-quote { animation: pmAfter  7s ease-in-out infinite; }
    .pm-quote svg { width: 18px; height: 18px; }

    /* ============ Trust strip (at a glance) ============ */
    .strip {
      display: grid; grid-template-columns: repeat(4, 1fr);
      border: 1px solid var(--line); border-radius: 20px; overflow: hidden; background: #fff;
    }
    @media (max-width: 720px) { .strip { grid-template-columns: repeat(2, 1fr); } }
    .strip .cell {
      padding: clamp(22px, 3vw, 32px) clamp(18px, 2.4vw, 28px);
      border-right: 1px solid var(--line); border-bottom: 1px solid var(--line);
    }
    .strip .cell:last-child { border-right: 0; }
    @media (min-width: 721px) {
      .strip .cell { border-bottom: 0; }
      .strip .cell:nth-child(4) { border-right: 0; }
    }
    @media (max-width: 720px) {
      .strip .cell:nth-child(2n) { border-right: 0; }
      .strip .cell:nth-child(n+3) { border-bottom: 0; }
    }
    .strip .big {
      font-size: clamp(30px, 3.6vw, 44px); font-weight: 500; letter-spacing: -0.03em;
      line-height: 1; color: var(--ink);
    }
    .strip .big .u { font-size: 0.5em; color: var(--accent); margin-left: 2px; font-weight: 600; }
    .strip .l {
      margin-top: 12px; font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.06em;
      text-transform: uppercase; color: var(--muted);
    }

    /* ============ Section header (title + meta pill) ============ */
    .sec-head {
      display: grid; grid-template-columns: 1fr auto; gap: 24px;
      align-items: end; margin-bottom: clamp(26px, 3.6vw, 44px);
    }
    .sec-head h2 { font-size: clamp(28px, 3.6vw, 44px); font-weight: 500; letter-spacing: -0.03em; line-height: 1.04; }
    .sec-head .meta { justify-self: end; display: inline-flex; align-items: center; gap: 8px; }
    .sec-head .meta span {
      font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.08em; text-transform: uppercase;
      white-space: nowrap; padding: 6px 12px; border-radius: 999px;
      background: var(--paper-2); color: var(--muted); border: 1px solid var(--line);
    }
    @media (max-width: 720px) {
      .sec-head { grid-template-columns: 1fr; gap: 14px; }
      .sec-head .meta { justify-self: start; }
    }

    /* ============ Feature trio (art panel + text below) ============ */
    .feat3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    @media (max-width: 860px) { .feat3 { grid-template-columns: 1fr; } }
    .fcard { display: flex; flex-direction: column; }
    .fcard .art {
      position: relative; border-radius: 22px; min-height: 240px; overflow: hidden;
      display: grid; place-items: center; transition: transform 220ms ease;
    }
    .fcard:hover .art { transform: translateY(-4px); }
    .fcard .art .tag { position: absolute; top: 16px; left: 16px; z-index: 2; margin: 0; }
    .fcard .glyph {
      display: grid; 
      place-items: center; 
      width: 74px; 
      height: 74px;
      background: #fff; 
      border-radius: 99px;
    }
    .fcard .glyph img { width: 34px; height: 34px; }
    .fcard.t-a .art { background: color-mix(in oklch, var(--accent) 16%, white); }
    .fcard.t-a .glyph img { color: var(--accent-mid); }
    .fcard.t-b .art { background: var(--paper-3); }
    .fcard.t-b .glyph img { color: var(--ink); }
    .fcard.t-c .art { background: #b6d4e0; }
    .fcard.t-c .glyph img { color: var(--accent); }
    .fcard h3 { font-size: 21px; font-weight: 600; letter-spacing: -0.02em; margin-top: 20px; color: var(--ink); }
    .fcard p { color: var(--muted); font-size: 14px; line-height: 1.55; margin-top: 6px; max-width: 34ch; }

    /* ============ Split feature ============ */
    .split {
      display: grid; grid-template-columns: 1fr 1fr; gap: 0;
      border: 1px solid var(--line); border-radius: 24px; overflow: hidden; background: var(--paper-2);
    }
    @media (max-width: 860px) { .split { grid-template-columns: 1fr; } }
    .split .l { padding: clamp(28px, 4vw, 52px); display: flex; flex-direction: column; justify-content: center; }
    .split .l h2 { font-size: clamp(26px, 3.4vw, 40px); font-weight: 500; letter-spacing: -0.025em; line-height: 1.06; }
    .split .l p { color: var(--muted); margin-top: 16px; max-width: 42ch; line-height: 1.6; }
    .split .watch { margin-top: 26px; display: inline-flex; align-items: center; gap: 14px; background: transparent; border: 0; padding: 0; color: var(--ink); font-weight: 500; }
    .split .watch .play {
      width: 52px; height: 52px; border-radius: 50%; background: var(--accent); color: var(--accent-ink);
      display: grid; place-items: center; transition: transform 150ms ease;
    }
    .split .watch:hover .play { transform: scale(1.06); }
    .split .r { border-left: 1px solid var(--line); padding: clamp(16px, 2vw, 24px); display: flex; flex-direction: column; justify-content: center; gap: 4px; background: #fff; }
    @media (max-width: 860px) { .split .r { border-left: 0; border-top: 1px solid var(--line); } }
    .frow { display: grid; grid-template-columns: 1fr auto; gap: 16px; align-items: center; padding: 22px 20px; border-bottom: 1px solid var(--line); }
    .frow:last-child { border-bottom: 0; }
    .frow h4 { font-size: 16px; font-weight: 500; }
    .frow p { color: var(--muted); font-size: 13px; margin-top: 4px; }
    .frow .tile { width: 52px; height: 52px; border-radius: 14px; display: grid; place-items: center; }
    .frow .tile img { width: 24px; height: 24px; }
    .frow .tile.a { background: color-mix(in oklch, var(--accent) 16%, white); color: var(--accent-mid); }
    .frow .tile.b { background: var(--paper-2); color: var(--ink); border: 1px solid var(--line); }
    .frow .tile.c { background: var(--ink); color: var(--accent); }

    /* ============ How it works (steps) ============ */
    .steps { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 860px) { .steps { grid-template-columns: 1fr; } }
    .step { background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 28px; position: relative; }
    .step .n {
      width: 40px; height: 40px; border-radius: 12px; background: var(--accent); color: var(--accent-ink);
      display: grid; place-items: center; font-family: var(--f-mono); font-weight: 600; margin-bottom: 18px;
    }
    .step h3 { font-size: 18px; font-weight: 500; letter-spacing: -0.015em; }
    .step p { color: var(--muted); font-size: 14px; line-height: 1.55; margin-top: 8px; }

    /* ============ Stories / testimonials ============ */
    .stories { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 860px) { .stories { grid-template-columns: 1fr; } }
    .story { background: #fff; border: 1px solid var(--line); border-radius: 20px; padding: 26px; display: flex; flex-direction: column; }
    .story .stars { color: var(--accent); letter-spacing: 2px; font-size: 14px; }
    .story blockquote { margin: 14px 0 0; font-size: 15px; line-height: 1.6; color: var(--ink); }
    .story .who { display: flex; align-items: center; gap: 12px; margin-top: auto; padding-top: 22px; }
    .story .who .av {
      width: 40px; height: 40px; border-radius: 50%; flex-shrink: 0;
      background: color-mix(in oklch, var(--accent) 16%, white); color: var(--accent-mid);
      display: grid; place-items: center; font-family: var(--f-mono); font-size: 13px; font-weight: 600;
    }
    .story .who b { font-weight: 500; font-size: 14px; }
    .story .who span { display: block; color: var(--muted); font-size: 12px; }

    /* ============ Pricing ============ */
    .pricing { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; max-width: 760px; margin: 0 auto; }
    @media (max-width: 720px) { .pricing { grid-template-columns: 1fr; } }
    .plan { background: #fff; border: 1px solid var(--line); border-radius: 22px; padding: 32px; display: flex; flex-direction: column; }
    .plan.pro { border-color: var(--accent); box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent) 12%, transparent); position: relative; }
    .plan .badge-top { position: absolute; top: 20px; right: 20px; }
    .plan .name { font-family: var(--f-mono); font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); }
    .plan .price { font-size: clamp(38px, 5vw, 52px); font-weight: 500; letter-spacing: -0.03em; margin-top: 12px; line-height: 1; }
    .plan .price small { font-size: 15px; color: var(--muted); font-weight: 400; letter-spacing: 0; }
    .plan ul { list-style: none; padding: 0; margin: 22px 0 26px; display: grid; gap: 12px; }
    .plan li { display: grid; grid-template-columns: 20px 1fr; gap: 10px; font-size: 14px; color: var(--ink); align-items: start; }
    .plan li svg { width: 18px; height: 18px; color: var(--accent); margin-top: 1px; }
    .plan .btn { margin-top: auto; justify-content: center; }
    .pricing-note { text-align: center; margin-top: 22px; }
    .pricing-shot { max-width: 760px; margin: 26px auto 0; height: 220px; }

    /* ============ Blog ============ */
    .blog { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 860px) { .blog { grid-template-columns: 1fr; } }
    .post { background: #fff; border: 1px solid var(--line); border-radius: 20px; overflow: hidden; display: flex; flex-direction: column; transition: border-color 200ms ease; }
    .post:hover { border-color: var(--ink); }
    .post .cover { height: 170px; background: repeating-linear-gradient(135deg, var(--paper-2) 0 12px, var(--paper-3) 12px 24px); display: grid; place-items: center; color: var(--muted-2); font-family: var(--f-mono); font-size: 11px; }
    .post .body { padding: 22px; display: flex; flex-direction: column; flex: 1; }
    .post .meta { display: flex; align-items: center; gap: 10px; font-family: var(--f-mono); font-size: 11px; color: var(--muted); }
    .post .meta .tg { padding: 3px 9px; border-radius: 999px; background: var(--paper-2); border: 1px solid var(--line); letter-spacing: 0.04em; }
    .post h3 { font-size: 18px; font-weight: 500; letter-spacing: -0.015em; margin-top: 12px; }
    .post p { color: var(--muted); font-size: 14px; line-height: 1.55; margin-top: 10px; }
    .post .rd { margin-top: auto; padding-top: 18px; font-family: var(--f-mono); font-size: 12px; letter-spacing: 0.06em; text-transform: uppercase; color: var(--accent); }

    /* ============ Contact Sales band (two-tone) ============ */
    .cta-band { border: 1px solid var(--ink); border-radius: 24px; overflow: hidden; }
    .cta-band .top { background: var(--paper-2); padding: clamp(40px, 6vw, 72px) 32px; text-align: center; }
    .cta-band .top h2 { font-size: clamp(30px, 4.4vw, 52px); font-weight: 500; letter-spacing: -0.03em; }
    .cta-band .top p { color: var(--muted); max-width: 52ch; margin: 18px auto 0; line-height: 1.6; }
    .cta-band .bar {
      background: var(--accent); color: var(--accent-ink);
      display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;
      padding: 22px clamp(24px, 4vw, 44px);
    }
    .cta-band .bar strong { font-size: clamp(20px, 2.6vw, 28px); font-weight: 500; letter-spacing: -0.02em; }
    .cta-band .bar .btn { background: #fff; color: var(--ink); border-color: #fff; }
    .cta-band .bar .btn:hover { background: var(--ink); color: #fff; border-color: var(--ink); }

    /* ============ Need-help chat launcher ============ */
    .chat-fab {
      position: fixed; right: 20px; bottom: 20px; z-index: 60;
      width: 56px; height: 56px; border-radius: 50%; border: 0; cursor: pointer;
      background: var(--ink); color: #fff; display: grid; place-items: center;
      box-shadow: 0 10px 30px rgba(14,18,16,0.25); transition: transform 150ms ease, background 150ms ease;
    }
    .chat-fab:hover { transform: translateY(-2px); background: var(--accent); }
    .chat-fab svg { width: 24px; height: 24px; }

    @media (prefers-reduced-motion: reduce) {
      .fcard:hover, .chat-fab:hover, .split .watch:hover .play { transform: none; }
    }
  </style>
</head>
<body id="top">

<a href="#main" class="skip-link">Skip to content</a>

<!-- ============== NAV ============== -->
<header role="banner">
  <nav class="nav" aria-label="Primary">
    <div class="wrap nav-inner">
      <a href="/" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
        <span class="brand-mark"><img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" height="32" /></span>
      </a>
      <div class="nav-links">
        <a href="#features">Features</a>
        <a href="#pricing">Pricing</a>
        <a href="#blog">Blog</a>
        <button type="button" class="chat-open" style="background:none;border:0;padding:8px 12px;font-size:14px;border-radius:8px;color:inherit;font-family:inherit;">Need help?</button>
        <a href="#contact">Contact Sales</a>
      </div>
      <a href="#contact" class="btn btn-accent btn-sm nav-cta">Install →</a>
    </div>
  </nav>
</header>

<main id="main">

<!-- ============== HERO ============== -->
<section class="qhero" aria-labelledby="q-hero-title">
  <div class="wrap">
    <div class="stage">
      <div class="lead">
        <div class="eyebrow">
          <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25" height="25" viewBox="0 0 48 48">
            <path fill="#7cb342" d="M37.216,11.78c-0.023-0.211-0.211-0.305-0.351-0.305s-3.21-0.234-3.21-0.234s-2.132-2.132-2.39-2.343	c-0.234-0.234-0.68-0.164-0.867-0.117c-0.023,0-0.469,0.141-1.195,0.375c-0.726-2.086-1.968-3.984-4.194-3.984h-0.211	C24.187,4.375,23.391,4,22.735,4c-5.155,0-7.639,6.444-8.412,9.725c-2.015,0.633-3.445,1.054-3.609,1.125	c-1.125,0.351-1.148,0.375-1.289,1.429c-0.117,0.797-3.046,23.456-3.046,23.456L29.179,44l12.373-2.671	C41.575,41.282,37.24,11.991,37.216,11.78z M27.937,9.483c-0.562,0.164-1.242,0.375-1.921,0.609V9.671	c0-1.265-0.164-2.296-0.469-3.117C26.718,6.695,27.445,7.984,27.937,9.483L27.937,9.483z M24.117,6.812	c0.305,0.797,0.516,1.922,0.516,3.468v0.234c-1.265,0.398-2.601,0.797-3.984,1.242C21.422,8.804,22.899,7.351,24.117,6.812	L24.117,6.812z M22.617,5.359c0.234,0,0.469,0.094,0.656,0.234c-1.664,0.773-3.421,2.718-4.148,6.655	c-1.101,0.351-2.156,0.656-3.163,0.984C16.806,10.233,18.915,5.359,22.617,5.359z"></path><path fill="#558b2f" d="M36.865,11.428c-0.141,0-3.21-0.234-3.21-0.234s-2.132-2.132-2.39-2.343	C31.17,8.757,31.053,8.71,30.96,8.71L29.249,44l12.373-2.671c0,0-4.335-29.338-4.359-29.549	C37.169,11.569,37.005,11.475,36.865,11.428z"></path><path fill="#fff" d="M24.792,18.593l-1.475,4.449c0,0-1.337-0.715-2.927-0.715c-2.374,0-2.489,1.498-2.489,1.867	c0,2.028,5.301,2.812,5.301,7.583c0,3.757-2.374,6.177-5.578,6.177c-3.872,0-5.808-2.397-5.808-2.397l1.037-3.411	c0,0,2.028,1.752,3.734,1.752c1.129,0,1.59-0.876,1.59-1.521c0-2.651-4.333-2.766-4.333-7.145c0-3.665,2.628-7.214,7.952-7.214	C23.777,17.994,24.792,18.593,24.792,18.593z"></path>
          </svg>
          Shopify App · DQ Series
        </div>
        <h1 id="q-hero-title">
          Hide prices.<br>
          Win more <span class="accent">quotes</span>.
        </h1>
        <p class="lede">
          Turn browsing into conversations. DQLab <strong>Request a Quote &amp; Hide Price</strong> hides prices and
          the buy button, captures quote requests with your own fields, and lets you approve every offer from the Shopify admin.
        </p>
        <div class="cta">
          <a class="btn btn-accent btn-arrow" href="#pricing">See pricing</a>
          <a class="btn btn-outline" href="#how">How it works</a>
        </div>
        <div class="rating">
          <span class="stars" aria-hidden="true">★★★★★</span>
          <span>Built for Shopify · OS 2.0 · Free plan available</span>
        </div>
      </div>

      <div class="hero-media">
        <div class="browser">
          <div class="bar" aria-hidden="true">
            <span class="dot"></span><span class="dot"></span><span class="dot"></span>
            <span class="url">your-store.myshopify.com/products/lounge-chair</span>
          </div>
          <div class="pm" role="img"
               aria-label="Animated Shopify product page for a Modern Lounge Chair: the $500 price and Add to Cart button transform into a hidden price with an Add to Quote button.">
            <div class="pm-photo" aria-hidden="true">
              <span class="pm-tag">Quote only</span>
              <svg class="chair" viewBox="0 0 260 240" xmlns="http://www.w3.org/2000/svg" fill="none">
                <!-- back frame post -->
                <path d="M150 44 L196 150" stroke="#a9743f" stroke-width="12" stroke-linecap="round"/>
                <!-- back cushion -->
                <rect x="86" y="40" width="70" height="98" rx="12" fill="#727977" transform="rotate(-9 121 89)"/>
                <!-- seat cushion -->
                <rect x="78" y="120" width="108" height="36" rx="12" fill="#7f8583"/>
                <!-- front seat rail -->
                <path d="M80 150 L184 150" stroke="#b57e46" stroke-width="12" stroke-linecap="round"/>
                <!-- armrest -->
                <path d="M92 118 L172 108" stroke="#a9743f" stroke-width="10" stroke-linecap="round"/>
                <!-- arm support -->
                <path d="M96 118 L92 150" stroke="#a9743f" stroke-width="10" stroke-linecap="round"/>
                <!-- front leg -->
                <path d="M92 150 L78 214" stroke="#9a6a39" stroke-width="11" stroke-linecap="round"/>
                <!-- back leg -->
                <path d="M180 150 L206 214" stroke="#9a6a39" stroke-width="11" stroke-linecap="round"/>
                <!-- cushion seam -->
                <path d="M96 138 L182 138" stroke="#6a716f" stroke-width="2" stroke-linecap="round"/>
              </svg>
            </div>
            <div class="pm-info" aria-hidden="true">
              <div class="pm-title">Modern Lounge Chair</div>
              <div class="pm-price">
                <span class="pm-state is-before">
                  <span class="pm-amt">$500</span>
                </span>
                <span class="pm-state is-after">
                  <span class="lock">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <rect x="4" y="10" width="16" height="10" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                    </svg>
                  </span>
                  <span class="pm-hidden">Prices are hidden</span>
                </span>
              </div>
              <div class="pm-stock"><span class="d"></span> In stock</div>
              <div class="pm-qlabel">Quantity</div>
              <div class="pm-stepper"><span>&minus;</span><b>1</b><span>+</span></div>
              <div class="pm-quote">
                <span class="pm-btnstate is-cart">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="20" r="1.4"/><circle cx="19" cy="20" r="1.4"/><path d="M2 3h3l2.2 11.2a1.6 1.6 0 0 0 1.6 1.3h8.6a1.6 1.6 0 0 0 1.6-1.2L22 7H6"/>
                  </svg>
                  Add to Cart
                </span>
                <span class="pm-btnstate is-quote">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"/><path d="M17 21H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h7l5 5v11a2 2 0 0 1-2 2Z"/><path d="M9 13h6M9 17h4"/>
                  </svg>
                  Add to Quote
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== TRUST STRIP ============== -->
<section style="padding: 56px 0 0" aria-label="At a glance">
  <div class="wrap">
    <div class="strip">
      <div class="cell"><div class="big">0<span class="u">$</span></div><div class="l">FREE PLAN TO START</div></div>
      <div class="cell"><div class="big">OS<span class="u">2.0</span></div><div class="l">THEME APP BLOCKS</div></div>
      <div class="cell"><div class="big">B2B</div><div class="l">WHOLESALE READY</div></div>
      <div class="cell"><div class="big">14<span class="u">d</span></div><div class="l">FREE PRO TRIAL</div></div>
    </div>
  </div>
</section>

<!-- ============== FEATURES ============== -->
<section id="features" aria-labelledby="features-title">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 01 · Features</div>
        <h2 id="features-title" style="margin-top:14px">Everything you need<br>to sell on request.</h2>
      </div>
      <div class="meta"><span>Hide · Request · Approve</span></div>
    </div>

    <div class="feat3">
      <article class="fcard t-a">
        <div class="art">
          <span class="badge solid tag">Core</span>
          <span class="glyph" aria-hidden="true">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAACXBIWXMAAAsTAAALEwEAmpwYAAADy0lEQVR4nO2W2U9TQRSHeVMfXOIS9Vn/A4NLoimgqQ3ctteVGIxGfXZFg0o0iuuLohKrXe4thBbFtQKttHPplaItGlpUFLRaq4AGQUzaQkADOeYMuTcUaoNLiFEnOclklvNlfnPOmUlJ+afbgnR23rhDFyvU81MVzMdFiqy0cYcvUjBLEb4wXZ3xHy6atdOcBtU2UrzGJfDaTy4u8ysa4dkuUrLWWW1SbSX6FVN/G1zJqhnCqc8RTtN737Y30uSvgmDoGYQ/dFDDPo7dv703InCaHoHXnrWZNJN/CX5oZ9aRtJXMYLE+r/91axha2rrhihiEU1Y/7Df4qGH/ijsILe2fAdf4qk/0uoYUWf/DQMd51QTCa0vdlpxYpaMCVmhy4HK5APmmetA5Q3CnOQpi+wA1W3MUdNUhOkcCrfCuK0pVcFs3xwjPXveeWTdpbFBONUsws0+9joLetx2fqCPTDRGWqrKhqOIheDogoVW+6IHD5ocyHPd67Ud7Cc8+FvXMzKTQu/qVcwWeDTd4uH7cjIby5pt8oLP7IUOzEfiaJ0ngMXpylH1ofwT8dcY+gV/1RjBmzE4IJSXLZxBeE270lclQNLzTS84QdVxy7zmkazbC9rMVkH2MUDtS3gzu94My/KIzBFfFoLwfLeC1fCE8G642KqfHQUWzYiJK0uAxxEHRTlr9UNESkx3vLLTDEmU27C6qhRO2Tth6xkvh0jzeOQbcSD8Ntfp+gWcDGD8yWChebfFWHe5BaUZuOGDwgbt9QHaMp8zVPYBlmTmwT++F47ZOOibN41rcM9LPu64I1DsKYsSsvfbTYDxpnvERhe8qqo0D17QNwEFjMvCq0tFS1+oTSo3pIzlGWVFePOke3QMq+47CSnke154qSyC1xzBa6uHBFfBa4oPLHaR5Kkv5fpDCpeDadd5BA06KdgyucvFVHLTRV5Y4uEamk99j6pdkx9TAdMI8/V4aYbRjql24U0/T6YWcTlEI1HF9gpkNfTedRhUQ+1G5gGBRwOKQDH6xqoEWGf01YaiAfOyG+rvHe8ZUQKSG9yDwWkuNJSf28tVjGY6nQSnxHjHg0GzPo3QM57ibIi2vNrsN3NZNNJBG3elYmsuQuUHg2W4s/K9b31DZsTicLvPTaEfDPt4pyhtqewtW44E+fFjytjMFKb/SHKWqKTW8phCfxbpbuZGn/koIhpqGPYtN9Fmsu50bIZyaPosqhlH+ts+EKH0EStY6Ca/tkj4CQjHbiWMuo3LL8I/AH/eT+VfgWWmpCqYjVZE5Z/zhGerkBeSvb98A2o2axBjcZuwAAAAASUVORK5CYII=" alt="blind">
          </span>
        </div>
        <h3>Hide prices &amp; buy button</h3>
        <p>Replace price and add-to-cart with a “Request a Quote” button on any product, collection, or for specific customers.</p>
      </article>
      <article class="fcard t-b">
        <div class="art">
          <span class="badge tag">Capture</span>
          <span class="glyph" aria-hidden="true">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFCUlEQVR4nO2Ze0xbZRjGi8ZsMbroP8ZkI0YTXfQfTXRRF6NZpGMDyiWwsXVECTTMAcKkA6aozC1GzdhawsyCDFDbCXTt6WXlHC4tlKvDOHXBbVzmYEDHtYOS4VJoecw50u6wUmC0h0vCkzxJ07Tne37f+349J315PB8rp0TtL5GrlFK5apy2RE4QEplmK28tKKdE7S+VERapnMAsywhLrky1hbfaJWF2ngmtP/WjYjMdWipTkTMQCt5ql5RpGwLs3WaqIicgkausKxJqZ3SsP18YrwzYLxrnC0Vw2q1NWP7sdH6w8/vpkvzQ+T7LZ12TXiNAKFIHHojb6rPwAUKRhb3IYgBOFpfaaAg6/MlihW2xAHwniDD+Dr221wD0zs+1wEIAD2O+h+vzhSLvzwy7ba623wBbvgJgq7Wtk91O3p8Z9o48qHME5XX4QoJyuy57TU4BuswDXkHQ4bvNAysHwJX46wAsrQMsQfx1gCUCXOr/Dcae2lnvGY3GBb1iAFbbONrvdLjCf2RIxsHqJHRZu1c/wMTUv8huPo4kYyqUHQQTPqE6EeU33W9MDyv+cgBMYxolbQomtNP6m6TX4Ze1hdgQnsKv2hZiQ/w9chWetOoBuBB/HWCRAJOdnRhJSYJjbAwOiwUjqcmY6u1ZOy00nBAP89vbMBizH4N7IpjXlgwxZwAkSW4wHzkiNKelNZvT0q7cOnr0aa8A6F0fjI5kgtMeOrCPqcaSZLMCt8oZR8fHzQJQVTY9o65pylQbGsztJ76GWSx2OnPlASbMwLUfgKoogBIwdpACXC6KxKETn8BPZfo5i2oY0NQ0gXa9vIwN0Pd7QsJjK9JClw0yDFekAFSoKzgTngqFTn8YAWoZeMo6xq9pTHYngNbYiK6sz10QvWJxlHeHODUZDqsV9uEhjHyciKm+Xo8AtYZKXK+S4m5F7KzQoAQYIyMh1WXjOdVFV3Cn/ZR1OFPdyADQbskvZFehfMkAi5bdBnSeB4wxbsHbyj9EilaCx5UGt+Bs777Y4ALQV9Ta+9LT6fCTfWKxmHuAK6fd2qRan4QQTRH8lKZ5gzu9UWmC3DgDYKyx/XVK8l1vRsbCfxb7BKCR7nUBRqko5Oi+wvOEblGheQ/4WPkv6K7Kgp2MmEZl8OsLhvcVQGenASlaKZ5Qzd8mvDn8iLKWqRRdsVntR4bolwfA2gyxMYcJ86jShC+13+ISlYLvdV/gRULjMfimCxWI/+kYWs6+A0d5kNvZ+R9C8Ba3AFOjQMtLuFa/jQmVqMkF/vgG6NYBLZ9iigpDgS4LW1R6V/AXShU4XpSEG2dexe28lxlPaQPnBqAElZwCtNceBOo2MH5DU4BcXTZwvQDokAFVe11B7pERKNMcRmnhXpjzXnEFd9qmet8TAKAPfZcTAC1VhT3C3XCYNjIAsqogbFeXwF4tBCojPLUEJsrew7h8O+5d2IFJzU449EGYJoM9A5AhNT4H6B8cwo7wfXgzMAy/Fm9mAOz1T+JZQoNw9TmMU/cfG+ADc1KByclJdPf0orejEjCfBf7JhLK1BPp+Cxx3b7t+WrFaARaUwwa05nEPwB5w0MMHn6vPOOtJ1PcAQpGaXQUufCg5FmZFODcA9LSQHrhxDRERG4c/iyN9D3B/zCpS0DMrLiF2x4iglUbbpn0NsNxCRfAHIENG1ywALZC7NoEKyV+zALwZoUKwC5Sgb80C0II67Kn5qvEfMjJaT3yVJK8AAAAASUVORK5CYII=" alt="fill-in-form">
          </span>
        </div>
        <h3>Custom quote forms</h3>
        <p>Collect quantity, company, and any custom fields you need. Every request lands in your admin and your inbox instantly.</p>
      </article>
      <article class="fcard t-c">
        <div class="art">
          <span class="badge dark tag">Convert</span>
          <span class="glyph" aria-hidden="true">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAAFbElEQVR4nO2Ya0xbZRjHz4ddoC20hQK90AvbxOG+mBkX56JslHIpvbeHQktbepHFr16iURMMtFwLtHQtFMq4307JzEym0ZHMbCEmi5vGzZkYR/y0zbEPxkRA1/CY0yByk7bQ0y6Gf/Ik/dTz+z/v/33P8x4E2de+9hV/Xas/wJsyObgTpnu546Z7uRMGB3Kt8ADyLIoftJ4VYJZbPMyyxJ82z/GmLGrupGmUN2kC7gReRsgdNwJn1DDGGalRc0b1c6wR3RJ7SHeLM6g/m1z4S9YCPmZe5mMW4E+bgYfXVC1shs8dMwBntCZc7BE9sIf1wB7SAXOoepk5rC1ILPQn5wV5QSvKm7GeEQTNg7uFZw1VA2uwGnIGtEOs/sozzIAWZQZUfELhj8xY3xcErU8FM1YQBC2wV3jmxSpgDlRBTkALOYFKyOmvfJrVh75HCPzR4Buv5gVtKwTCQ3YfCtm96EpWH/pi3A3kBW0fEA7vRyHLr4GsXtW7cQPnD5pS+JjlND9ovZwYeDUwetSX6b2K0/iz99b1S9ZTgqDtUeI6r8bhgeFTQaZPBRlexcNMr/LlXcG/5K87mDdje5gs+EyvEjIvKIDukT9A/HUHYzaAxybZ8BkevOSQ0a04FbMBbtB2IiHwPWpgeFXAwKG3gad3y4HeKT0Rs4EjWB11dTwgDt6nAlaXDOo+VcGRbilkehRb4Gku2RK9BaXGBJ+HmTV8zLxANDyzUwpXftJDCKzw4xMjHPdIIMMt/7fzbhnQXFK8FqhdYlVU8DzMlsfDzH8R3vlOGXzxc00Y/p/65oEB6G3izfBA7ZIAtbPiT1q7OPKowcfMbycDPrRqgNYq3gZeAtSOCkhzlr8VeQWma9+JCn5YB+yLOmAP7j42oXWFRyjfLQZ6l3Rb+PQOcXQGBDMWPm+ydnFH+EEdHA+g8ObnWuD2aoA1ULWnzt99bIRjrood4dPbyxejilB4FSZMrp3gTwRQuLtQG3747C8G4HqVwOzXEtb5dKcYKG1lrqjgc6eMr+dOGJf+K/NMHwrfPjJtgPhy3gAcjxJy+tD4d94phrT2ckhrK1+itJa+FtEAd9x4c6cNy/Rq4PYmA2smuhWQ3ashAh4orWVAbi35OrKBCcPvO542A1XwQr96LULra3a+BjhuefjtGo/YpK2Dp7SWAqml9LfIERozzkU8KgNaKPCrtjVxdb4G2C5Z3OHJLaVAaiq5EdEAZ6SmiD2qX4p4zvdVQoFfCT8sbI0THpl4xYayCk9uFv2Rai+JbqhjjepPskb0yxFfUn4Unvcp4O7jrSbi2Xlyk2iZZC89GRX82ipE+4bt0UC+Vw53djCxJ/jmEtwAkB3FwqgNsId1nljHg/wLMrjzq4kQeBJejmJ31AZYQ7qGmC8jF5TwnEe6wUQc4SG1Ufhx1Abwr2XMi9WLMd+kPArId0vCQxleu9+wJZvgixcPNRTH9gUvJ1B5jhnQfh/zNdAtD4/E4akyHvB24T1So7AsJvhNRuyx3mG3n+d3FRv7rsHXDPRVticF3o4bKGrbs4Fsv+Z8cuCFcLihyLZnA8fcZYcZfs13CYdvPHcbqUcPIfEQyy8hMXyqjzJ9KizDq7xOXOaLr6c2CLGUhqIPkY5XUhEiRO2RC4jq/OH6QgGSCGV0y28QsGG/QhIlihvNorlln9Fd0hCtSxKiuSRXqZ0VT6KFJzlET1KbRLMkuzCU2igMpTQKr1DqCxlIokX1VdDx/YH/Tu+Q6KjOipX18JTW8nlyS9n8hs47RCspTcXV4T+oL6RQ6wtpyLOidKe4Os0pvklpK7tPaSsfoDglDLzILaUD5OaS+2SH6GaKQ1SVbM597ev/pL8BEp82F3eAadkAAAAASUVORK5CYII=" alt="approval">
          </span>
        </div>
        <h3>Approve &amp; negotiate</h3>
        <p>Set your price, counter or approve, and turn an accepted quote into a draft order or a checkout link the customer can pay in one click.</p>
      </article>
    </div>
  </div>
</section>

<!-- ============== MAIN FEATURE (split) ============== -->
<section id="how" aria-labelledby="split-title" style="padding-top:0">
  <div class="wrap">
    <div class="split">
      <div class="l">
        <div class="eyebrow">Section 02 · Main feature</div>
        <h2 id="split-title" style="margin-top:14px">Set up your<br>quote flow.</h2>
        <p>Choose what to hide, design the request form, and decide who sees prices — all from a clean settings panel. No theme code, no developer required.</p>
        <button type="button" class="watch chat-open" aria-label="Watch a tutorial in chat">
          <span class="play" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
          </span>
          Watch tutorial
        </button>
      </div>
      <div class="r">
        <div class="frow">
          <div>
            <h4>Targeting rules</h4>
            <p>Hide by product, collection, tag, or customer group.</p>
          </div>
          <div class="tile a" aria-hidden="true">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAEfUlEQVR4nO3ZyU8bVxwH8GmVqlIUVaqo1F6qSpHaXsIyHm/zPGMTqapy6aGHHnrpqfeSpQHsEEMSnMVmxoBtcBYi9Q/IhTRtozQ2kCgEWgKtMEoRpajMGIPBhtIEYodf9cZxsCc2tfGK5K/0lezbfPTe7z0vBFFJJZWUPIKb2o9L7OXMc7rPBA7NijyaF3ntVwDEa8ReisBpFSJPD4o8gsQKPHq40KHTEuWe+W5VlcihToGjo3LESwyHtgQefRe4pHqPKLeAm3pD4OhvBB6F0wFeXR16XeDp1j86j7xJlEPmOe0nAkdPZgpI0Wm/nf6idAAefSxw9Pc5AJLLoTt+O32oaIA5F/O2yKELIo8284bY3m4RkUduwWZ4p2AAMBOv4yNU4FEg34AUXcYzB2bDvrwi/B3osMihiSIAQNYpsUN7JGfAvI19Hx+VJQBA0pbjUP8CxxzMGhBwGA6IHG0RefppqREij+DvDhpGWxSRrCFhJxMq9cOLuByCyXNq+OlELfQ3VEPWELheD8+uGiDYpSsZYvqCBu421UmAeHcFiffpZT0EOosHmrPRcN+kgP6j2wDcH4/XRLOGbPVtQ3C3+gyw5mLBby8cQOARjJ9Rwa2jNUmAmw3VMNBIjjxoP/Ru1pB/XTrtxmX9X4kY3GifAUJONu+IKYsabn8bm4PEeprqYLBZkf2WkuefHhYiVw1JGNzNK3pYysP8zFzUgLcxeQ76G6rh9olauGdSSBDcnCFrPSzgrvfq4XmfHGSAJ249LNh1uzpOH56m4KZsDm4dq4EhIwne5hgg7xCpvaz04FH5/FyvB7xy/kzmgHsxB8eS5wAP9qCRhIFmMglQGMiL/nC8RtrT8u0WuWaAFQeTFvH4vAbupJiDu411MGTc3kae4kGq4Z6JhCmLSloJOWjjih4WE47rWatW2i6vzkFsG+0E8BQSgs/5sTYl/NJKwa+tFMxe0kiXpxyE52qkJc0cmBTgTbONPMWC4IZcDExf1EgQDHrUppTmR46R3wfepvRz4CkFJN6gQwc+i1rCxA4ERjqa5ZCfT+I5yB7gKRYkXnwrJ75f72Uhes2AP1bEALLjtGwhaym66mLB21SbE8BTDhDcfCA8FUhCKpCe/EG8zWRxICtOBmatmoJAhlsUC7+bNfU5Q4Lduoawi32yE+Tx+dg9EuzWxU6rPECGjOTmozOUichnwEzsW3Egd9jFPk+H8bWrYcaqhUAXgsl2tfRtcjeQgWZya9RM3SjoD9ui9fAHKw7mAb4f5BC8IqsuBv60aqWPLf7O2OpkPAdNdTBymvL5zMxHRLES7GQ/DTmZQNKKWNQvX0+cVcKsjc4YMnyKXP7tnOJzolRZ7kInQy5mI9XqZDIjQ8a6Z2NtSo4oh4Cb2r/kYG6supit+MPP2bQ7QvDX2FGzwjtsVr9FlFsW7foPQw5m/P+O3+EWcma8XVm8/0B2m8Uu5suwgwnLIfdPKdYnzKqvib2WpW7mbNjJRIaMZHSslXLj/1aIvZrVblWVz6KqKvVzVFJJJQTxH0yBK+WFQYCJAAAAAElFTkSuQmCC" alt="product">
          </div>
        </div>
        <div class="frow">
          <div>
            <h4>Login to view price</h4>
            <p>Optionally require an account before prices appear.</p>
          </div>
          <div class="tile b" aria-hidden="true">
            <img width="24" height="24" src="https://img.icons8.com/3d-fluency/94/lock-2.png" alt="lock-2"/>
          </div>
        </div>
        <div class="frow">
          <div>
            <h4>Admin approval</h4>
            <p>Review, price, and approve every request in one place.</p>
          </div>
          <div class="tile c" aria-hidden="true">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAACuklEQVR4nO2WXW/SUBjH+wkUTHQSE1+gUOiXkQtseLkZaHaviRsdL6VceOGMCa7ZhaIx+gVMvJABgwEF/By6b3GOOeY5XWGajZ6yshy0/+SfNOnN79c+z2kFwY8fP378/KtJjLWkPNVPoIlxPSmsU+SxlopPdCRPdCJP60Se6lie1HLCOsEnJjqBrpWEDPDjGkqMLfi1koiNNMWCrxGr50skpnWU+K4/FHiDl8waips2vIPERP8h8AVfRXFTIyDAIsGNQAzgR1UUHwG83cUSdMFNDkZIGmqp2KiCJIC36yhR42OJKfywgqRhlUgjKIuEhmWTC/jyHH54WkcJjuCjgzKSBpU5vKOEhiU38NffZJKB/cwJFK49g+9b8LFBhUAlNgksmVV2+BtGLhU0sihoZAntfhYHjEzOE/jjEoodW/CMEvhy8B5JiDP4MrHKJIG9gb+kBMCLvRKK9m14JgksDVzAw5wvhJ8XBY204gr+aBdF+yUCAowS2BU8BJaVAd6VxAy+VyK0bBLYNTwVMDI/XQg4SohdNRXpqki04dkk8FLwLkfIUWIGf7RLaNkk8NLwzEvMsNgUvnMGnk0CS4OSN1/YpSUaSg7gw50iErsqEbt/CVwsgcWeR/BLSzTSZON9/le4U8SRrkqgjBLew7uWoPAFEm7vkEhHtcomsTp4ZolGmtxqFsiDQ4AvnpZJYvXwjhIUPk/hw+0iLaMEFjtXBH+hhA3f2qajYwswSFw9vB0476lEI01uvtu04OHp0zfgLAGnU6StMv+CrCTBRloJvFbQva/PrNE52wUS4TYH8Hau7T1SqEBrm0mCnk68wNvZ+Ly1d//bc0cJuHfnw+OXAo8Jfdp6tVDisEhCzcKBwHNCH5+cL9HaIaHmJt/wdkLNwp8S6wRv5/bb/Iu7X55iKFzPbvjx48fPf5HfHS4yIZmrWTYAAAAASUVORK5CYII=" alt="checkmark--v1">
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== SUCCESS STORIES ============== -->
<section aria-labelledby="stories-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 03 · Success stories</div>
        <h2 id="stories-title" style="margin-top:14px">Merchants who<br>sell on request.</h2>
      </div>
      <div class="meta"><span>Testimonials</span></div>
    </div>
    <div class="stories">
      <article class="story">
        <div class="stars" aria-hidden="true">★★★★★</div>
        <blockquote>“We hid prices for our trade catalog and quote requests tripled in the first month. Setup took ten minutes.”</blockquote>
        <div class="who"><span class="av" aria-hidden="true">MR</span><div><b>Maria Rossi</b><span>Owner · Rossi Supply</span></div></div>
      </article>
      <article class="story">
        <div class="stars" aria-hidden="true">★★★★★</div>
        <blockquote>“The admin approval flow is exactly what our B2B store needed. Every quote is now tracked in one place.”</blockquote>
        <div class="who"><span class="av" aria-hidden="true">JK</span><div><b>James Kim</b><span>Ops Lead · NorthPeak</span></div></div>
      </article>
      <article class="story">
        <div class="stars" aria-hidden="true">★★★★★</div>
        <blockquote>“Being able to require login before showing prices keeps our wholesale pricing private. Works perfectly on OS 2.0.”</blockquote>
        <div class="who"><span class="av" aria-hidden="true">AD</span><div><b>Amara Diallo</b><span>Founder · Loom &amp; Co</span></div></div>
      </article>
    </div>
  </div>
</section>

<!-- ============== PRICING ============== -->
<section id="pricing" aria-labelledby="pricing-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head" style="grid-template-columns:1fr; text-align:center; justify-items:center;">
      <div>
        <div class="eyebrow">Section 04 · Pricing</div>
        <h2 id="pricing-title" style="margin-top:14px">Start free.<br>Upgrade when you grow.</h2>
      </div>
    </div>

    <div class="pricing">
      <article class="plan">
        <div class="name">Free</div>
        <div class="price">$0<small> /month</small></div>
        <ul>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> 10 quotes total</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Simple quote form</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> One form section</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Email support</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Widget action: Page &amp; Popup</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Quote button on product page</li>
        </ul>
        <a href="#contact" class="btn btn-outline">Get started free</a>
      </article>

      <article class="plan pro">
        <span class="badge solid badge-top">Popular</span>
        <div class="name">Pro</div>
        <div class="price">$9<small> /month</small></div>
        <ul>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Unlimited quotes</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Advanced quote form</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Multiple form sections</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Widget action: Page, Popup &amp; Drawer</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Quote button on products &amp; collections</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Priority email &amp; WhatsApp support</li>
        </ul>
        <a href="#contact" class="btn btn-accent btn-arrow">Start 14-day trial</a>
      </article>
    </div>
    <p class="pricing-note eyebrow" style="justify-content:center; display:flex;">No credit card required to start</p>
  </div>
</section>

<!-- ============== BLOG ============== -->
<section id="blog" aria-labelledby="blog-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 05 · Blog</div>
        <h2 id="blog-title" style="margin-top:14px">From the blog.</h2>
      </div>
      <div class="meta"><span>Guides for quote-based selling</span></div>
    </div>
    <div class="blog">
      <article class="post">
        <div class="cover" aria-hidden="true">COVER · PLACEHOLDER</div>
        <div class="body">
          <div class="meta"><span>Jul 2026</span><span class="tg">B2B</span></div>
          <h3>When to hide prices on Shopify</h3>
          <p>How wholesale, custom, and high-ticket stores use “request a quote” to qualify buyers and protect margins.</p>
          <span class="rd">Read →</span>
        </div>
      </article>
      <article class="post">
        <div class="cover" aria-hidden="true">COVER · PLACEHOLDER</div>
        <div class="body">
          <div class="meta"><span>Jul 2026</span><span class="tg">Guide</span></div>
          <h3>Designing a quote form that converts</h3>
          <p>The fields that move a browser to a serious lead — and the ones that just add friction.</p>
          <span class="rd">Read →</span>
        </div>
      </article>
      <article class="post">
        <div class="cover" aria-hidden="true">COVER · PLACEHOLDER</div>
        <div class="body">
          <div class="meta"><span>Jun 2026</span><span class="tg">Playbook</span></div>
          <h3>From quote to paid in 24 hours</h3>
          <p>A simple approval workflow that turns requests into draft orders your customers can pay right away.</p>
          <span class="rd">Read →</span>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ============== CONTACT SALES BAND ============== -->
<!-- <section aria-labelledby="cta-title" style="padding-top:0">
  <div class="wrap">
    <div class="cta-band">
      <div class="top">
        <h2 id="cta-title">Have questions?</h2>
        <p>Running Shopify Plus, more than 1,000 monthly orders, or multiple stores? Want a custom quote flow or a migration from another app? Talk to our team.</p>
      </div>
      <div class="bar">
        <strong>Reach out to our team.</strong>
        <a href="#contact" class="btn">Get in touch</a>
      </div>
    </div>
  </div>
</section> -->

<!-- ============== CONTACT FORM ============== -->
<?php
  $contact_show_socials  = false;          // socials live in the footer on this page
  $contact_section_style = 'padding-top:0'; // match the tighter gap used by other sections
  require __DIR__ . '/inc/contact-section.php';
?>

</main>

<!-- ============== FOOTER ============== -->
<footer class="site-footer" role="contentinfo">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-col">
        <a href="/" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
          <span class="brand-mark"><img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" height="40" /></span>
        </a>
        <p style="color:#8a8d85; font-size:14px; margin-top:18px; max-width:34ch;">
          Request a Quote &amp; Hide Price — a Shopify app by Deque Lab for quote-based and B2B selling.
        </p>
        <div class="footer-socials">
          <a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Deque Lab on LinkedIn">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAABwklEQVR4nO2ZP0vDQByGkwsOruIkuGpXP4GLWyuufgk/g4OUXHFQEId2cFFwcHJUsC1mFPGutNpCLdY6VIp/2kT7N/1J0latbbGJklzgHninHMf75HdZLoLA4XA4TCPJdAXJJI5kqiFMwZHIVBMxjUkyWf5TeRET7FhpPDwiJkH7b97l8qgbKUQClgXMY8NAeWRMQaZR6wKYqG4XR73IpGJDgIHi+Cv/JuCLpOG8oIHW0EEpaDAfTntLQClo8J34veYtAa2h9wmodd1bAorXJ+CLpE0JYxKxvApz4RtvCSCXInAB3Mkwxnk+u3sN2xclSJWqUG224bXWMo/i2ukDTG4m2BZY2MvA41sTRnFZfIeZnRS7AtmXOvxGLK+CxKrAuPiPcmwK6G0wz//S4S2sHucheqcOXbeffGZTYF0p9q2ZCCXgJFcZWJd5qrEpML2dHNhn8SA7sK5ca7En0P7xvJepreTYa12fgN29EBfAXMCEC2AuQLkA4gIWGbWRWxE8fbWIadmygHE/z0BxMCJiemZZwPi54HZx1I2Er/yWBTpTIEG3y4uYbNgq/zmJEAkY9/POfhNENY6N7TfP4XA4glN8AJJwntfiYf8PAAAAAElFTkSuQmCC" alt="" width="20" height="20">
          </a>
          <a href="<?= e($CONFIG['twitter']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Deque Lab on X">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAAC+klEQVR4nO2ZSWhUQRCGP424K24J7oiCOyoeBAUVPAQEEQlxuehBD4ogBD3pyZjcNOjJmwt6EEXwIgriRUGiYkxEQVDcUC+iMZt7kpaCP9CMSd6bl2ReT5wfipmp6aqpf17339XvQQEFFNAXlADVQD3QBrgcWxvwGKhSLYmwFWhJoXjXg1kt5UlIdAZQvMuwzmzIlAR2JVyGNQPFcYhUB1Csi7BjcYg0BFCoizATgEi0BlCoizCb+pFweWKR+K+IVETkOJdFQV+A+Yo7kGsiP4FlveQYC7yIkec3sEExG4E/aUytZ8BIYAhww/NvV541QHvE5rZLY5cATWmukeOKmQ58lu8rMFv+qhh7wTTgXVprxMk6vKmxxfPfAYYCw4D73cRd0ZUcBTxIQML1NxGzD8AkxZ71/Aflm5exNz0ERovotYQk3EAQMbuq2DHeIjdBWC7/PvneeO14TR9IuIEiYrZT8as99XkqQTBcABbp/d4+knADScRUZ45yHPX8JzPyl2Ypsy7XRMzuAkVa5LWezFrxhvEJZNalQcTssPLM9c41H4HJ8p/OFyJ/gFXKtcfzm0IhxXqeD0ScCrWCDZc9/275VgK/8oHIW09mp2hqOe0ntq8YjoROpBlYqlwT9Vrq3cyolRgUSRyCJNIObFKedcB7YKo+n/LGmTwbZgGNIRLZrxwLvQJvqq8aATzxBME2TtQBB0WkRvEmsy8zvrOdvKtd/yHfK2Cc/JdCIXJdc97akXvdfP8NWKD8hzz/GfkmJGjlI5EtiTo1izZ9LvYy7hEwXF3vbc+/zVtTHWkRMVmdGeMQ1WU2xjDDO4g1atEbTqRBxPaEFYrZEfNesf3j6xVT5vlveYLQkEsiJrObNX6tzh5x/4DXah57wmLge66I2G2brsbwU4J1dT6ihopcr5E0LRKDhkhLAEW6GP1cJOoDKNT1x2OFOPtB2lY5GB69NemMEwvlAT8MLYtLwifTHNiVKCMhinWzuS6lR3Kt+u3KbKZTAQUUwD/4C68XAvRNax0sAAAAAElFTkSuQmCC" alt="" width="20" height="20">
          </a>
          <a href="mailto:<?= e($CONFIG['contact_email']) ?>" aria-label="Email Deque Lab">
            <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAYAAACM/rhtAAAACXBIWXMAAAsTAAALEwEAmpwYAAABhklEQVR4nO2Vy0tCQRjF73/nSouI3i1qFUEQLYoWbaIyytRS7x2DaFMQ0UKCNqHSw9B0ih4KBRERQUILk8LwcTkx4y2Kinx3sTlwYJgZmN/H9xhJEhISqgMZpoPQo6WPgISqurJBAFIBqOoLsNMdg9mX/HMwsy/JWb4Ajm09oMl+jO7FGGZ2nmoOZtl7Ru/yJRptRxjxxL9PsRzO8cMGK+WXbYFU1cFsBy/oW7mCyUoxtHEHOZz9vQadhxkMrt/COBfBwNoNFkLpioM5QxkOxN7oX72GI5guvknsgc/RubToyrGsZcmkZcm6nyq/i2d3tfqw5+tDobni4SJqvs7nC6vzksbMFOswEkOz44Q/RgqEG99OoNV5hjblHBPeRPXnIHuwxXWKdhLFpPfxx3tmfxJd7uh7QEotBzVL8+hmnKe9Z+mCQ7NmYmZrtsfO2J1SSqJiP4kczmLYc48OJQqjJcLN1mzvbWT8j6+O6ACKCEAqAFXd2CAAqQBUBSApBlCPloSEhOpAr54GgjiuxPL7AAAAAElFTkSuQmCC" alt="" width="20" height="20">
          </a>
        </div>
      </div>
      <nav class="footer-col" aria-label="Product">
        <h5>Product</h5>
        <ul>
          <li><a href="#features">Features</a></li>
          <li><a href="#pricing">Pricing</a></li>
          <li><a href="#how">How it works</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Company">
        <h5>Company</h5>
        <ul>
          <li><a href="/">Deque Lab</a></li>
          <li><a href="/#apps">All Shopify apps</a></li>
          <li><a href="#blog">Blog</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Community">
        <h5>Community</h5>
        <ul>
          <li><button type="button" class="chat-open" style="background:none;border:0;padding:0;color:inherit;font:inherit;cursor:pointer;font-size:14px;">Help &amp; chat</button></li>
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
        <span>DQ Series · Shopify</span>
      </span>
    </div>
  </div>
</footer>

<!-- ============== Need-help chat launcher ============== -->
<button type="button" class="chat-fab chat-open" aria-label="Open help chat">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M21 11.5a8.38 8.38 0 0 1-8.5 8.5 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.7a8.5 8.5 0 0 1-.9-3.8A8.38 8.38 0 0 1 12.5 3 8.38 8.38 0 0 1 21 11.5z"/></svg>
</button>

<!--
  ============== Live chat (Tawk.to) ==============
  1. Create a free property at https://tawk.to → Administration → Channels → Chat Widget.
  2. Copy your Property ID and Widget ID from the widget's embed code.
  3. Replace PROPERTY_ID and WIDGET_ID below. The "Need help?" links maximize the chat.
  Until then, the buttons gracefully fall back to the contact section.
-->
<script>
  var Tawk_API = Tawk_API || {}, Tawk_LoadStart = new Date();
  (function () {
    var PROPERTY_ID = 'PROPERTY_ID', WIDGET_ID = 'WIDGET_ID';
    if (PROPERTY_ID !== 'PROPERTY' + '_ID') {
      var s1 = document.createElement('script'), s0 = document.getElementsByTagName('script')[0];
      s1.async = true;
      s1.src = 'https://embed.tawk.to/' + PROPERTY_ID + '/' + WIDGET_ID;
      s1.charset = 'UTF-8';
      s1.setAttribute('crossorigin', '*');
      s0.parentNode.insertBefore(s1, s0);
    }
  })();

  // "Need help?" / "Watch tutorial" / chat FAB → open chat, or fall back to contact.
  document.querySelectorAll('.chat-open').forEach(function (el) {
    el.addEventListener('click', function (ev) {
      if (window.Tawk_API && typeof Tawk_API.maximize === 'function') {
        ev.preventDefault();
        Tawk_API.maximize();
      } else {
        location.hash = '#contact';
      }
    });
  });
</script>

<?php if ($form_sent): ?>
<script>
  if (location.hash !== '#contact') location.hash = '#contact';
</script>
<?php endif; ?>

</body>
</html>
