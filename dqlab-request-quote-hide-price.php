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
$contact_eyebrow = 'Contact Sales';
$contact_heading = 'Talk to us about<br><em>Request a Quote</em>.';
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
     'a' => 'There is a Free plan for up to 20 quote requests per month. The Pro plan is $9/month for unlimited quotes, custom fields, and admin approval flows. Both include a 14-day trial of Pro features.'],
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
          {"@type":"Offer","name":"Free","price":"0","priceCurrency":"USD","description":"Up to 20 quote requests per month."},
          {"@type":"Offer","name":"Pro","price":"9","priceCurrency":"USD","description":"Unlimited quotes, custom fields, and admin approval. Billed monthly."}
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
      overflow: hidden; box-shadow: 0 30px 60px rgba(14,18,16,0.10), 0 4px 10px rgba(14,18,16,0.04);
    }
    .browser .bar { display: flex; align-items: center; gap: 6px; padding: 10px 14px; border-bottom: 1px solid var(--line); background: var(--paper-2); }
    .browser .bar .dot { width: 10px; height: 10px; border-radius: 50%; background: #d8d4c6; }
    .browser .bar .url { margin-left: 10px; font-family: var(--f-mono); font-size: 11px; color: var(--muted); }
    .browser video { width: 100%; height: auto; display: block; background: var(--paper-2); }

    /* ============ Feature trio (colored cards) ============ */
    .feat3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 860px) { .feat3 { grid-template-columns: 1fr; } }
    .fcard {
      border: 1px solid var(--line); border-radius: 20px; padding: 26px;
      display: flex; flex-direction: column; min-height: 230px;
      transition: transform 200ms ease, border-color 200ms ease;
    }
    .fcard:hover { transform: translateY(-3px); }
    .fcard .art {
      flex: 1; border-radius: 14px; display: grid; place-items: center; margin-bottom: 18px;
      min-height: 120px;
    }
    .fcard .art svg { width: 52px; height: 52px; }
    .fcard.t-a { background: color-mix(in oklch, var(--accent) 8%, white); border-color: color-mix(in oklch, var(--accent) 18%, white); }
    .fcard.t-a .art { background: color-mix(in oklch, var(--accent) 14%, white); color: var(--accent-mid); }
    .fcard.t-b { background: var(--paper-2); }
    .fcard.t-b .art { background: #fff; border: 1px solid var(--line); color: var(--ink); }
    .fcard.t-c { background: linear-gradient(180deg, #fff, var(--paper-2)); }
    .fcard.t-c .art { background: var(--ink); color: var(--accent); }
    .fcard h3 { font-size: 19px; font-weight: 500; letter-spacing: -0.015em; }
    .fcard p { color: var(--muted); font-size: 14px; line-height: 1.55; margin-top: 8px; }
    .fcard .tag { align-self: flex-start; margin-bottom: 14px; }

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
    .frow .tile svg { width: 24px; height: 24px; }
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
        <a href="#contact" class="btn btn-accent btn-sm nav-cta">Request early access →</a>
      </div>
    </div>
  </nav>
</header>

<main id="main">

<!-- ============== HERO ============== -->
<section class="qhero" aria-labelledby="q-hero-title">
  <div class="wrap">
    <div class="stage">
      <div class="lead">
        <div class="eyebrow">Shopify App · DQ Series</div>
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
            <span class="url">your-store.myshopify.com</span>
          </div>
          <video autoplay muted loop playsinline preload="metadata"
                 poster="/assets/dq_request_quote.png"
                 aria-label="Storefront overview of the Request a Quote app">
            <source src="/assets/vid/hero_illu.mp4" type="video/mp4" />
          </video>
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
        <span class="badge solid tag">Core</span>
        <div class="art" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/><path d="M4 4l16 16"/></svg>
        </div>
        <h3>Hide prices &amp; buy button</h3>
        <p>Replace price and add-to-cart with a “Request a Quote” button on any product, collection, or for specific customers.</p>
      </article>
      <article class="fcard t-b">
        <span class="badge tag">Capture</span>
        <div class="art" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h8M8 16h5"/></svg>
        </div>
        <h3>Custom quote forms</h3>
        <p>Collect quantity, company, and any custom fields you need. Every request lands in your admin and your inbox instantly.</p>
      </article>
      <article class="fcard t-c">
        <span class="badge dark tag">Convert</span>
        <div class="art" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M20 6L9 17l-5-5"/></svg>
        </div>
        <h3>Approve &amp; convert</h3>
        <p>Set your price, approve, and turn a quote into a draft order or a checkout link the customer can pay in one click.</p>
      </article>
    </div>
  </div>
</section>

<!-- ============== MAIN FEATURE (split) ============== -->
<section id="how" aria-labelledby="split-title" style="padding-top:0">
  <div class="wrap">
    <div class="split">
      <div class="l">
        <div class="eyebrow">Main feature</div>
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
          <div class="tile a" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 3v18M3 12h18"/></svg></div>
        </div>
        <div class="frow">
          <div>
            <h4>Login to view price</h4>
            <p>Optionally require an account before prices appear.</p>
          </div>
          <div class="tile b" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg></div>
        </div>
        <div class="frow">
          <div>
            <h4>Admin approval</h4>
            <p>Review, price, and approve every request in one place.</p>
          </div>
          <div class="tile c" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M13 2L3 14h7l-1 8 10-12h-7z"/></svg></div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== HOW IT WORKS ============== -->
<section aria-labelledby="steps-title" style="padding-top:0">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 02 · How it works</div>
        <h2 id="steps-title" style="margin-top:14px">Three steps<br>to your first quote.</h2>
      </div>
    </div>
    <div class="steps">
      <?php foreach ($STEPS as $i => $s): ?>
      <article class="step">
        <div class="n" aria-hidden="true"><?= $i + 1 ?></div>
        <h3><?= e($s['t']) ?></h3>
        <p><?= e($s['d']) ?></p>
      </article>
      <?php endforeach; ?>
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
      <div class="meta"><span>Placeholder testimonials</span></div>
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
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Up to 20 quote requests / month</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Hide price &amp; add-to-cart button</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Basic quote form</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Email notifications</li>
        </ul>
        <a href="#contact" class="btn btn-outline">Get started free</a>
      </article>

      <article class="plan pro">
        <span class="badge solid badge-top">Popular</span>
        <div class="name">Pro</div>
        <div class="price">$9<small> /month</small></div>
        <ul>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Unlimited quote requests</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Custom fields &amp; targeting rules</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Admin approval &amp; draft orders</li>
          <li><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20 6L9 17l-5-5"/></svg> Login-to-view-price &amp; priority support</li>
        </ul>
        <a href="#contact" class="btn btn-accent btn-arrow">Start 14-day trial</a>
      </article>
    </div>

    <!-- Dummy pricing screenshot (replace with a real one later) -->
    <div class="placeholder pricing-shot" role="img" aria-label="App pricing screenshot placeholder">
      PRICING SCREENSHOT · PLACEHOLDER
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
<section aria-labelledby="cta-title" style="padding-top:0">
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
</section>

<!-- ============== CONTACT FORM ============== -->
<?php require __DIR__ . '/inc/contact-section.php'; ?>

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
