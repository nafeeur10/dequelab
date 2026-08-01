<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — single-page PHP site
 * ============================================================ */

require __DIR__ . '/inc/lib.php';              // helpers, config, session, CSRF, interest lists
require __DIR__ . '/inc/contact-process.php';  // contact POST handler → $form_errors/$form_data/$form_sent

// Canonical: home lives at the site root, not /index.php.
$CONFIG['canonical_url'] = $CONFIG['site_url'] . '/';
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <!-- ============== SEO: primary ============== -->
  <title><?= e($CONFIG['site_name']) ?> — <?= e($CONFIG['site_tagline']) ?> | Alliances PRO, Muslim Daily Planner &amp; Shopify Apps</title>
  <meta name="description" content="<?= e($CONFIG['site_description']) ?>" />
  <meta name="keywords" content="<?= e($CONFIG['site_keywords']) ?>" />
  <meta name="author" content="<?= e($CONFIG['site_author']) ?>" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1" />
  <meta name="googlebot" content="index, follow" />
  <meta name="theme-color" content="#0e1210" />
  <meta name="color-scheme" content="light" />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />

  <!-- ============== Open Graph ============== -->
  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="<?= e($CONFIG['site_name']) ?>" />
  <meta property="og:locale" content="<?= e($CONFIG['site_locale']) ?>" />
  <meta property="og:title" content="<?= e($CONFIG['site_name']) ?> — <?= e($CONFIG['site_tagline']) ?>" />
  <meta property="og:description" content="<?= e($CONFIG['site_description']) ?>" />
  <meta property="og:url" content="<?= e($CONFIG['canonical_url']) ?>" />
  <meta property="og:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta property="og:image:alt" content="Deque Lab logo" />
  <meta property="og:image:width" content="512" />
  <meta property="og:image:height" content="512" />

  <!-- ============== Twitter / X ============== -->
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:creator" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:title" content="<?= e($CONFIG['site_name']) ?> — <?= e($CONFIG['site_tagline']) ?>" />
  <meta name="twitter:description" content="<?= e($CONFIG['site_description']) ?>" />
  <meta name="twitter:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta name="twitter:image:alt" content="Deque Lab logo" />

  <!-- ============== Icons & manifest hints ============== -->
  <link rel="icon" type="image/jpeg" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="apple-touch-icon" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="shortcut icon" href="<?= e($CONFIG['logo']) ?>" />

  <!-- ============== Performance hints ============== -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="preconnect" href="https://api.iconify.design" crossorigin />
  <link rel="dns-prefetch" href="https://fonts.googleapis.com" />
  <link rel="dns-prefetch" href="https://api.iconify.design" />
  <link rel="preload" as="image" href="<?= e($CONFIG['logo']) ?>" />

  <!-- ============== Fonts & styles ============== -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <!-- ============== Structured data (JSON-LD) ============== -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@graph": [
      {
        "@type": "Organization",
        "@id": "<?= e($CONFIG['site_url']) ?>/#organization",
        "name": "<?= e($CONFIG['site_name']) ?>",
        "url": "<?= e($CONFIG['site_url']) ?>/",
        "logo": {
          "@type": "ImageObject",
          "url": "<?= e($CONFIG['og_image']) ?>",
          "width": 512,
          "height": 512
        },
        "foundingDate": "<?= e($CONFIG['site_founded']) ?>",
        "description": "<?= e($CONFIG['site_description']) ?>",
        "sameAs": [
          "<?= e($CONFIG['linkedin']) ?>",
          "<?= e($CONFIG['twitter']) ?>"
        ],
        "contactPoint": [{
          "@type": "ContactPoint",
          "contactType": "customer support",
          "email": "<?= e($CONFIG['contact_email']) ?>",
          "availableLanguage": ["English"]
        }]
      },
      {
        "@type": "WebSite",
        "@id": "<?= e($CONFIG['site_url']) ?>/#website",
        "url": "<?= e($CONFIG['site_url']) ?>/",
        "name": "<?= e($CONFIG['site_name']) ?>",
        "description": "<?= e($CONFIG['site_description']) ?>",
        "publisher": { "@id": "<?= e($CONFIG['site_url']) ?>/#organization" },
        "inLanguage": "en"
      },
      {
        "@type": "WebPage",
        "@id": "<?= e($CONFIG['canonical_url']) ?>#webpage",
        "url": "<?= e($CONFIG['canonical_url']) ?>",
        "name": "<?= e($CONFIG['site_name']) ?> — <?= e($CONFIG['site_tagline']) ?>",
        "isPartOf": { "@id": "<?= e($CONFIG['site_url']) ?>/#website" },
        "about": { "@id": "<?= e($CONFIG['site_url']) ?>/#organization" },
        "inLanguage": "en",
        "description": "<?= e($CONFIG['site_description']) ?>"
      },
      {
        "@type": "ItemList",
        "name": "SaaS products",
        "itemListOrder": "https://schema.org/ItemListOrderAscending",
        "numberOfItems": 7,
        "itemListElement": [
          {"@type":"ListItem","position":1,"item":{"@type":"SoftwareApplication","name":"Alliances PRO","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Mini CRM built for follow-ups. Deals, tasks, organizations.","offers":{"@type":"Offer","availability":"https://schema.org/InStock"}}},
          {"@type":"ListItem","position":2,"item":{"@type":"SoftwareApplication","name":"Muslim Daily Planner","applicationCategory":"LifestyleApplication","operatingSystem":"iOS, Android, Web","description":"Prayer times, Adhkar, habit tracking, day planner.","offers":{"@type":"Offer","availability":"https://schema.org/InStock"}}},
          {"@type":"ListItem","position":3,"item":{"@type":"SoftwareApplication","name":"Education CRM","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Lead-to-enrollment CRM for coaching centres, language schools, and tutoring businesses.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":4,"item":{"@type":"SoftwareApplication","name":"Real Estate CRM","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Property-first CRM with listings, buyer matches, and viewing schedules.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":5,"item":{"@type":"SoftwareApplication","name":"Duty Timer","applicationCategory":"BusinessApplication","operatingSystem":"iOS, Android","description":"Mobile check-in / check-out for field teams, security, hospitality.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":6,"item":{"@type":"SoftwareApplication","name":"Email Marketing Suite","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Campaigns, automations, and lifecycle flows for small teams.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":7,"item":{"@type":"SoftwareApplication","name":"Competitor Marketing Analysis","applicationCategory":"BusinessApplication","operatingSystem":"Web","description":"Track competitors' ads, landing pages, and SEO movement in a weekly digest.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}}
        ]
      },
      {
        "@type": "ItemList",
        "name": "Shopify apps",
        "itemListOrder": "https://schema.org/ItemListOrderAscending",
        "numberOfItems": 8,
        "itemListElement": [
          {"@type":"ListItem","position":1,"item":{"@type":"SoftwareApplication","name":"DQ: Appointment Booking App","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Booking widget for services, classes, and consultations on Shopify.","offers":{"@type":"Offer","availability":"https://schema.org/InStock"}}},
          {"@type":"ListItem","position":2,"item":{"@type":"SoftwareApplication","name":"DQ: PreOrder & Request Stock","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Flagship preorder app — acquired by a larger Shopify partner in 2024.","offers":{"@type":"Offer","availability":"https://schema.org/Discontinued"}}},
          {"@type":"ListItem","position":3,"item":{"@type":"SoftwareApplication","name":"DQ: CRM for B2B","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Native CRM for B2B merchants on Shopify with account hierarchies.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":4,"item":{"@type":"SoftwareApplication","name":"DQ: Wholesale Pricing & Discount","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Customer-group pricing and tiered volume discounts on Shopify Functions.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":5,"item":{"@type":"SoftwareApplication","name":"DQ: Request a Quote & Hide Price","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Gate prices behind login and capture quote requests with admin approval.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":6,"item":{"@type":"SoftwareApplication","name":"DQ: Live Product Options","applicationCategory":"BusinessApplication","operatingSystem":"Shopify","description":"Real-time configurators — swatches, conditional logic, live price preview.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":7,"item":{"@type":"SoftwareApplication","name":"DQ: Subscription Plus","applicationCategory":"BusinessApplication","operatingSystem":"Shopify Plus","description":"Subscriptions for Shopify Plus with mixed cart and merchant-branded portal.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}},
          {"@type":"ListItem","position":8,"item":{"@type":"SoftwareApplication","name":"DQ: Checkout Extensions Plus","applicationCategory":"BusinessApplication","operatingSystem":"Shopify Plus","description":"Bundle of checkout extensions for Plus merchants.","offers":{"@type":"Offer","availability":"https://schema.org/PreOrder"}}}
        ]
      },
      {
        "@type": "BreadcrumbList",
        "itemListElement": [
          {"@type":"ListItem","position":1,"name":"Home","item":"<?= e($CONFIG['site_url']) ?>/"},
          {"@type":"ListItem","position":2,"name":"SaaS","item":"<?= e($CONFIG['site_url']) ?>/#saas"},
          {"@type":"ListItem","position":3,"name":"Shopify Apps","item":"<?= e($CONFIG['site_url']) ?>/#apps"},
          {"@type":"ListItem","position":4,"name":"Contact","item":"<?= e($CONFIG['site_url']) ?>/#contact"}
        ]
      }
    ]
  }
  </script>

  <style>
    /* Page chrome (.skip-link, .sr-only, html scroll) and contact-form
       styles are shared and live in /assets/styles.css. */

    /* ============ HERO (light) ============ */
    .hero { padding: 24px 0 0; }
    .hero .stage {
      background: #fff; color: var(--ink);
      border: 1px solid var(--line);
      border-radius: 28px;
      padding: clamp(32px, 4vw, 56px) clamp(32px, 5vw, 72px);
      position: relative; overflow: hidden;
      display: grid; grid-template-columns: 1.1fr 0.9fr;
      gap: 48px; align-items: center;
    }
    @media (max-width: 960px) { .hero .stage { grid-template-columns: 1fr; } }
    .hero .stage::before {
      content: ""; position: absolute; inset: 0;
      background:
        radial-gradient(800px 500px at 95% 0%, color-mix(in oklch, var(--accent) 10%, transparent), transparent 60%),
        radial-gradient(500px 400px at 0% 100%, color-mix(in oklch, var(--accent) 5%, transparent), transparent 60%);
      pointer-events: none;
    }
    .hero h1 {
      font-size: clamp(40px, 5.6vw, 70px);
      line-height: 1; letter-spacing: -0.035em; font-weight: 500;
      position: relative;
    }
    .hero h1 .accent { color: var(--accent); }
    .hero .lede {
      margin-top: 22px;
      font-size: clamp(15px, 1.2vw, 17px);
      max-width: 46ch; color: var(--muted); line-height: 1.55; position: relative;
    }
    .hero-cta { margin-top: 28px; display: flex; gap: 10px; flex-wrap: wrap; position: relative; }

    .hero-brand { position: relative; display: grid; place-items: center; aspect-ratio: 1; width: 100%; }
    .hero-brand .ring {
      position: absolute; inset: 0; border-radius: 50%;
      border: 1px dashed rgba(14,18,16,0.08);
      animation: spin 60s linear infinite;
    }
    .hero-brand .ring.r2 { inset: 8%; border-style: solid; border-color: rgba(14,18,16,0.05); animation-duration: 80s; animation-direction: reverse; }
    .hero-brand .ring.r3 { inset: 18%; border-style: dashed; border-color: color-mix(in oklch, var(--accent) 20%, transparent); }
    @keyframes spin { to { transform: rotate(360deg); } }
    .hero-brand .logo-bg {
      position: relative;
      width: 50%; aspect-ratio: 1;
      background: #fff; border-radius: 50%;
      border: 1px solid var(--line);
      display: grid; place-items: center; padding: 12%;
      overflow: hidden;
      box-shadow: 0 24px 60px rgba(14,18,16,0.08), 0 2px 6px rgba(14,18,16,0.04);
    }
    .hero-brand .logo-bg img { width: 100%; height: 100%; object-fit: contain; }
    .hero-brand .chip {
      position: absolute; padding: 10px 16px 10px 12px;
      border-radius: 999px; background: #fff; border: 1px solid var(--line);
      font-family: var(--f-mono); font-size: 11px; color: var(--ink); letter-spacing: 0.06em;
      display: inline-flex; align-items: center; gap: 10px; white-space: nowrap; line-height: 1;
      box-shadow: 0 4px 12px rgba(14,18,16,0.04);
    }
    .hero-brand .chip .n {
      display: inline-grid; place-items: center;
      min-width: 22px; height: 22px; padding: 0 6px;
      border-radius: 999px; background: var(--accent); color: var(--accent-ink);
      font-family: var(--f-mono); font-size: 11px; font-weight: 600; letter-spacing: 0;
    }
    .hero-brand .c1 { top: 6%; left: 0%; animation: float 6s ease-in-out infinite; }
    .hero-brand .c2 { top: 28%; right: -4%; animation: float 6s ease-in-out infinite 1s; }
    .hero-brand .c3 { bottom: 18%; left: -2%; animation: float 6s ease-in-out infinite 2s; }
    .hero-brand .c4 { bottom: 4%; right: 8%; animation: float 6s ease-in-out infinite 3s; }
    @keyframes float { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
    @media (prefers-reduced-motion: reduce) {
      .hero-brand .ring, .hero-brand .chip { animation: none !important; }
      .marquee-track { animation: none !important; }
      html { scroll-behavior: auto; }
    }

    /* ============ Tech-stack marquee (hero-width) ============ */
    .tech-marquee-wrap { padding: 40px 0 0; }
    .tech-marquee {
      position: relative;
      overflow: hidden;
      padding: 32px 0;
      background: transparent;
      border: 0;
      -webkit-mask-image: linear-gradient(to right, transparent 0, #000 7%, #000 93%, transparent 100%);
              mask-image: linear-gradient(to right, transparent 0, #000 7%, #000 93%, transparent 100%);
    }
    .tech-track {
      display: flex; align-items: center; gap: 72px;
      width: max-content;
      animation: ticker 42s linear infinite;
      will-change: transform;
    }
    .tech-track:hover { animation-play-state: paused; }
    .tech-item {
      display: inline-flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      height: 68px;
      width: 68px;
    }
    .tech-item img {
      max-width: 96px; max-height: 96px;
      object-fit: contain;
      display: block;
      transition: transform 200ms ease;
    }
    .tech-item:hover img { transform: scale(1.08); }
    @keyframes ticker { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }
    @media (max-width: 640px) {
      .tech-marquee { padding: 22px 0; }
      .tech-item { height: 52px; width: 52px; }
      .tech-item img { max-width: 72px; max-height: 72px; }
      .tech-track { gap: 52px; }
    }

    /* ============ Section header ============ */
    .sec-head {
      display: grid; grid-template-columns: 1fr auto; gap: 32px;
      margin-bottom: 40px; align-items: end;
    }
    @media (max-width: 720px) { .sec-head { grid-template-columns: 1fr; gap: 16px; } }
    .sec-head h2 {
      font-size: clamp(32px, 4.4vw, 56px);
      letter-spacing: -0.03em; line-height: 1.02; font-weight: 500;
    }
    .sec-head .meta {
      font-family: var(--f-mono); font-size: 11px;
      letter-spacing: 0.1em; color: var(--muted);
      display: flex; gap: 18px; flex-wrap: wrap; align-items: center;
    }
    .sec-head .meta b { color: var(--ink); font-family: var(--f-sans); font-weight: 500; font-size: 13px; letter-spacing: 0; }
    .sec-head .meta .dot { color: var(--accent); }

    /* ============ Product grid ============ */
    .pgrid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
    @media (max-width: 960px) { .pgrid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 640px) { .pgrid { grid-template-columns: 1fr; } }

    .p {
      background: #fff; border: 1px solid var(--line);
      border-radius: 20px; padding: 26px;
      display: flex; flex-direction: column;
      min-height: 280px;
      transition: border-color 200ms ease, transform 200ms ease;
      position: relative; overflow: hidden;
    }
    .p:hover { border-color: var(--ink); }

    .p .top { display: flex; align-items: start; justify-content: space-between; gap: 12px; margin-bottom: 20px; }
    .p .ic {
      width: 48px; height: 48px; border-radius: 12px;
      background: var(--accent); color: var(--accent-ink);
      display: grid; place-items: center;
      font-family: var(--f-mono); font-weight: 600; font-size: 15px; letter-spacing: -0.01em;
    }
    .p .ic.alt-dark { background: #fff; color: var(--accent); border: 1.5px solid var(--accent); }
    .p .ic.alt-pale { background: var(--paper-2); color: var(--ink); border: 1px solid var(--line); }
    .p .ic.alt-tint { background: color-mix(in oklch, var(--accent) 18%, white); color: var(--accent-mid); }
    /* Image-based product icon (real product logo inside the 48×48 slot) */
    .p .ic.ic-img { padding: 0; overflow: hidden; background: var(--paper-2); border: 1px solid var(--line); }
    .p .ic.ic-img img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .p .ic.ic-img.bg-white { background: #fff; }
    .p .ic.ic-img.bg-green { background: color-mix(in oklch, var(--accent) 14%, white); border-color: color-mix(in oklch, var(--accent) 22%, white); }

    .p .status {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 5px 10px; border-radius: 999px;
      font-family: var(--f-mono); font-size: 10px;
      letter-spacing: 0.1em; text-transform: uppercase; font-weight: 500;
    }
    .p .status::before { content: ""; width: 6px; height: 6px; border-radius: 50%; }
    .p .status.live { background: color-mix(in oklch, var(--accent) 12%, white); color: var(--accent-mid); }
    .p .status.live::before { background: var(--accent); animation: pulse 2s ease-in-out infinite; }
    .p .status.soon { background: var(--paper-2); color: var(--muted); border: 1px dashed var(--line-2); }
    .p .status.soon::before { background: var(--muted-2); }
    .p .status.sold { background: #fff4e5; color: #a45300; }
    .p .status.sold::before { background: #d97706; }
    .p .status.submitted { background: #eef2ff; color: #3730a3; }
    .p .status.submitted::before { background: #4f46e5; }
    @keyframes pulse {
      0%,100% { box-shadow: 0 0 0 0 color-mix(in oklch, var(--accent) 50%, transparent); }
      50% { box-shadow: 0 0 0 5px color-mix(in oklch, var(--accent) 0%, transparent); }
    }

    .p h3 { font-size: 22px; letter-spacing: -0.02em; line-height: 1.1; font-weight: 500; }
    .p .desc { color: var(--muted); font-size: 14px; line-height: 1.55; margin-top: 10px; }

    .p .foot {
      margin-top: auto; padding-top: 22px;
      display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .p .foot .price { font-family: var(--f-mono); font-size: 12px; color: var(--muted); letter-spacing: 0.04em; }
    .p .cta {
      display: inline-flex; align-items: center; gap: 8px;
      font-family: var(--f-mono); font-size: 12px;
      letter-spacing: 0.06em; text-transform: uppercase; font-weight: 500;
      color: var(--ink);
      transition: gap 150ms ease, color 150ms ease;
    }
    .p .cta::after { content: "→"; }
    .p:hover .cta { gap: 12px; color: var(--accent); }
    .p .cta.muted { color: var(--muted-2); pointer-events: none; }
    .p .cta.muted::after { content: ""; }

    .p.soon { background: linear-gradient(180deg, #fff, var(--paper-2)); }
    .p.soon h3 { color: var(--ink); }
    .p.soon .ic { background: transparent; color: var(--ink); border: 1.5px dashed var(--line-2); }
    .p.soon .ic.alt-dark, .p.soon .ic.alt-pale, .p.soon .ic.alt-tint {
      background: transparent; color: var(--ink); border: 1.5px dashed var(--line-2);
    }

    .p.sold { background: linear-gradient(180deg, #fff, #fffaf0); }
    .p.sold .ic { background: #fff4e5; color: #a45300; border: 1px solid #f4d9b0; }

    .p-feat { grid-column: span 2; min-height: 380px; }
    @media (max-width: 960px) { .p-feat { grid-column: span 2; } }
    @media (max-width: 640px) { .p-feat { grid-column: span 1; min-height: 280px; } }

    .p-feat .body { display: grid; grid-template-columns: 1.05fr 0.95fr; gap: 32px; flex: 1; align-items: stretch; }
    @media (max-width: 720px) { .p-feat .body { grid-template-columns: 1fr; } }
    .p-feat .body .text { display: flex; flex-direction: column; }
    .p-feat .body .text h3 { font-size: 28px; }
    .p-feat .body .mock-crm, .p-feat .body .mock-book { height: 100%; align-self: stretch; }

    .feat-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 18px; }
    .feat-chips .chip-f {
      font-family: var(--f-mono); font-size: 10px;
      letter-spacing: 0.08em; text-transform: uppercase;
      padding: 5px 10px; border-radius: 999px;
      background: var(--paper-2); color: var(--ink);
      border: 1px solid var(--line);
    }
    .feat-chips .chip-f.on { background: var(--accent); color: var(--accent-ink); border-color: transparent; }

    /* ===== Mocks (light) ===== */
    .mock-crm {
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 14px; padding: 14px;
      display: flex; flex-direction: column; gap: 10px;
    }
    .mock-crm .row { display: flex; justify-content: space-between; font-family: var(--f-mono); font-size: 10px; color: var(--muted); letter-spacing: 0.08em; }
    .mock-crm .lanes { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 6px; flex: 1; }
    .mock-crm .lane { background: #fff; border: 1px solid var(--line); border-radius: 8px; padding: 8px; display: flex; flex-direction: column; }
    .mock-crm .lane h6 { font-family: var(--f-mono); font-size: 9px; color: var(--muted); letter-spacing: 0.1em; margin: 0 0 6px; font-weight: 500; }
    .mock-crm .deal { background: var(--paper-2); border: 1px solid var(--line); border-radius: 6px; padding: 6px 8px; font-size: 11px; color: var(--ink); margin-bottom: 4px; }
    .mock-crm .deal:last-child { margin-bottom: 0; }
    .mock-crm .deal.focus { background: #fff; border-color: var(--accent); box-shadow: 0 0 0 2px color-mix(in oklch, var(--accent) 14%, transparent); }
    .mock-crm .deal .v { color: var(--accent-mid); font-family: var(--f-mono); font-size: 9px; margin-top: 3px; font-weight: 600; }

    .mock-planner {
      background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 14px;
      display: grid; gap: 8px;
    }
    .mock-planner .head { display: flex; justify-content: space-between; font-family: var(--f-mono); font-size: 10px; color: var(--muted); letter-spacing: 0.08em; }
    .mock-planner .head b { color: var(--ink); font-family: var(--f-sans); font-weight: 500; font-size: 12px; letter-spacing: 0; }
    .mock-planner .prayers { display: grid; grid-template-columns: repeat(5, 1fr); gap: 4px; }
    .mock-planner .pr { padding: 8px 4px; text-align: center; border-radius: 6px; background: var(--paper-2); font-family: var(--f-mono); font-size: 10px; }
    .mock-planner .pr.on { background: var(--accent); color: var(--accent-ink); }
    .mock-planner .pr .t { font-size: 11px; font-weight: 600; }
    .mock-planner .pr .l { font-size: 8px; opacity: 0.8; margin-top: 2px; letter-spacing: 0.04em; }
    .mock-planner .tasks { display: grid; gap: 5px; margin-top: 4px; }
    .mock-planner .task {
      display: grid; grid-template-columns: 14px 1fr auto; gap: 8px; align-items: center;
      font-size: 12px; padding: 6px 8px; background: var(--paper-2); border-radius: 6px;
    }
    .mock-planner .task::before { content: ""; width: 12px; height: 12px; border-radius: 4px; border: 1.5px solid var(--line-2); }
    .mock-planner .task.done::before { background: var(--accent); border-color: var(--accent); }
    .mock-planner .task.done { color: var(--muted); text-decoration: line-through; }
    .mock-planner .task .time { font-family: var(--f-mono); font-size: 10px; color: var(--muted); }

    .mock-book {
      background: #fff; border: 1px solid var(--line); border-radius: 14px; padding: 16px;
      display: flex; flex-direction: column;
    }
    .mock-book .h { display: flex; justify-content: space-between; align-items: end; margin-bottom: 12px; }
    .mock-book .h h6 { font-size: 14px; font-weight: 500; margin: 0; }
    .mock-book .h .sub { font-family: var(--f-mono); font-size: 10px; color: var(--muted); letter-spacing: 0.06em; }
    .mock-book .cal { display: grid; grid-template-columns: repeat(7, 1fr); gap: 3px; }
    .mock-book .cal .d { aspect-ratio: 1; display: grid; place-items: center; font-size: 11px; border-radius: 5px; color: var(--ink); }
    .mock-book .cal .d.off { color: var(--line-2); }
    .mock-book .cal .d.avail { background: var(--paper-2); }
    .mock-book .cal .d.sel { background: var(--accent); color: var(--accent-ink); font-weight: 500; }
    .mock-book .slots { display: grid; grid-template-columns: repeat(3, 1fr); gap: 5px; margin-top: 10px; }
    .mock-book .slots.flex { margin-top: auto; padding-top: 10px; }
    .mock-book .slot { padding: 6px; text-align: center; font-family: var(--f-mono); font-size: 10px; border: 1px solid var(--line); border-radius: 5px; }
    .mock-book .slot.on { background: var(--accent); color: var(--accent-ink); border-color: transparent; }

    .acq-story {
      background: linear-gradient(180deg, #fff, #fffaf0);
      border: 1px solid #f4d9b0; border-radius: 14px;
      padding: 14px; display: grid; gap: 10px;
    }
    .acq-story .key { font-family: var(--f-mono); font-size: 10px; color: #a45300; letter-spacing: 0.1em; }
    .acq-story .row { display: flex; justify-content: space-between; padding: 8px 0; border-top: 1px solid #f4d9b0; font-size: 12px; }
    .acq-story .row:first-of-type { border-top: 0; }
    .acq-story .row .v { font-family: var(--f-mono); color: #a45300; font-size: 12px; }

    /* Contact + form styles are shared — see /assets/styles.css */

    /* ============ Highlight strip ============ */
    .strip {
      display: grid; grid-template-columns: repeat(4, 1fr);
      border: 1px solid var(--line); border-radius: 18px;
      overflow: hidden; background: #fff;
    }
    @media (max-width: 720px) { .strip { grid-template-columns: 1fr 1fr; } }
    .strip .cell { padding: 24px 22px; border-right: 1px solid var(--line); }
    .strip .cell:last-child { border-right: none; }
    .strip .big { font-size: clamp(28px, 3.4vw, 42px); letter-spacing: -0.025em; line-height: 1; font-weight: 500; }
    .strip .big .u { color: var(--accent); }
    .strip .l { font-family: var(--f-mono); font-size: 11px; color: var(--muted); letter-spacing: 0.06em; margin-top: 10px; }

    .legend { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 28px; }
    .legend .l {
      display: inline-flex; align-items: center; gap: 8px;
      font-family: var(--f-mono); font-size: 11px;
      letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted);
    }
    .legend .l::before { content: ""; width: 8px; height: 8px; border-radius: 50%; }
    .legend .l.live::before { background: var(--accent); }
    .legend .l.soon::before { background: var(--muted-2); }
    .legend .l.sold::before { background: #d97706; }
  </style>
  <?php require __DIR__ . '/inc/crisp.php'; ?>

  <script src="https://analytics.ahrefs.com/analytics.js" data-key="AgbLLxcpekIQG5E1V/q5ow" async></script>
</head>
<body id="top">

<a href="#main" class="skip-link">Skip to content</a>

<!-- ============== NAV ============== -->
<header role="banner">
  <nav class="nav" aria-label="Primary">
    <div class="wrap nav-inner">
      <a href="#top" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — home">
        <span class="brand-mark">
          <img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" height="32" />
        </span>
      </a>
      <div class="nav-links">
        <a href="#saas">SaaS</a>
        <a href="#apps">Shopify Apps</a>
        <a href="#contact">Contact</a>
        <a href="#contact" class="btn btn-accent btn-sm nav-cta">Get notified →</a>
      </div>
    </div>
  </nav>
</header>

<main id="main">

<!-- ============== HERO ============== -->
<section class="hero" aria-labelledby="hero-title">
  <div class="wrap">
    <div class="stage">
      <div>
        <div class="eyebrow">Deque Lab · Est. <?= e($CONFIG['site_founded']) ?></div>
        <h1 id="hero-title" style="margin-top: 18px">
          Two product lines.<br>
          One <span class="accent">small</span> lab.
        </h1>
        <p class="lede">
          We build SaaS products and Shopify apps. Some are shipping, some are coming next.
        </p>
        <div class="hero-cta">
          <a class="btn btn-accent btn-arrow" href="#saas">Explore SaaS</a>
          <a class="btn btn-outline" href="#apps">Explore Apps</a>
        </div>
      </div>

      <div class="hero-brand" aria-hidden="true">
        <div class="ring"></div>
        <div class="ring r2"></div>
        <div class="ring r3"></div>
        <div class="logo-bg">
          <img src="<?= e($CONFIG['logo']) ?>" alt="" width="240" height="240" />
        </div>
        <span class="chip c1"><span class="n">7</span> SaaS products</span>
        <span class="chip c2"><span class="n">8</span> Shopify apps</span>
        <span class="chip c3"><span class="n">1</span> acquired</span>
        <span class="chip c4"><span class="n">5+</span> shipping</span>
      </div>
    </div>
  </div>
</section>

<!-- ============== STRIP STATS ============== -->
<section style="padding: 56px 0 0" aria-label="At-a-glance numbers">
  <div class="wrap">
    <div class="strip">
      <div class="cell"><div class="big">7</div><div class="l">SAAS PRODUCTS</div></div>
      <div class="cell"><div class="big">8</div><div class="l">SHOPIFY APPS</div></div>
      <div class="cell"><div class="big">1<span class="u">×</span></div><div class="l">APP ACQUIRED · 2024</div></div>
      <div class="cell"><div class="big">5<span class="u">yr</span></div><div class="l">BUILDING SINCE <?= e($CONFIG['site_founded']) ?></div></div>
    </div>
  </div>
</section>

<!-- ============== SAAS ============== -->
<section id="saas" aria-labelledby="saas-title">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 01 · SaaS</div>
        <h2 id="saas-title" style="margin-top: 14px">SaaS products,<br>built with care.</h2>
      </div>
      <div class="meta">
        <span><b>7</b> products</span>
        <span class="dot">·</span>
        <span>2 live</span>
        <span class="dot">·</span>
        <span>5 coming</span>
      </div>
    </div>

    <div class="legend" aria-label="Status legend">
      <span class="l live">Live</span>
      <span class="l soon">Coming Soon</span>
      <span class="l sold">Acquired</span>
    </div>

    <div class="pgrid">
      <!-- 01 · Alliances PRO -->
      <article class="p p-feat" id="saas-alliances">
        <div class="top">
          <div class="ic ic-img bg-white" aria-hidden="true">
            <img src="/assets/alliances-logo-blackbg.jpg" alt="" loading="lazy" />
          </div>
          <span class="status live">Live</span>
        </div>
        <div class="body">
          <div class="text">
            <h3>Alliances PRO</h3>
            <p class="desc">A Mini CRM built for follow-ups. Deals, Tasks, Organizations — wired together so nothing slips between a warm intro and a closed deal.</p>
            <div class="feat-chips" aria-label="Features">
              <span class="chip-f on">Deals</span>
              <span class="chip-f">Tasks</span>
              <span class="chip-f">Organizations</span>
              <span class="chip-f">Contacts</span>
              <span class="chip-f">Follow-up engine</span>
              <span class="chip-f">Email sync</span>
              <span class="chip-f">Calendar sync</span>
              <span class="chip-f">Pipeline analytics</span>
            </div>
          </div>
          <div class="mock-crm" aria-hidden="true">
            <div class="row"><span>PIPELINE · Q2</span><span style="color: var(--accent)">$184k</span></div>
            <div class="lanes">
              <div class="lane">
                <h6>NEW · 12</h6>
                <div class="deal">Alderwood<div class="v">$8.2k</div></div>
                <div class="deal">Fieldcraft<div class="v">$4.0k</div></div>
                <div class="deal">Meridian<div class="v">$12k</div></div>
              </div>
              <div class="lane">
                <h6>DISCOVERY · 8</h6>
                <div class="deal focus">Northwind<div class="v">$18k</div></div>
                <div class="deal">Pebble<div class="v">$24k</div></div>
                <div class="deal">Cedar Co<div class="v">$16k</div></div>
              </div>
              <div class="lane">
                <h6>WON · 4</h6>
                <div class="deal" style="opacity:.7">Patagonia<div class="v">$32k</div></div>
                <div class="deal" style="opacity:.7">Pine Co<div class="v">$16k</div></div>
                <div class="deal" style="opacity:.7">Lantern<div class="v">$22k</div></div>
              </div>
            </div>
          </div>
        </div>
        <div class="foot">
          <span class="price">CRM · per-seat pricing</span>
          <a href="https://alliances.pro" class="cta">Try Alliances PRO</a>
        </div>
      </article>

      <!-- 02 · Muslim Daily Planner -->
      <article class="p" id="saas-planner">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/mdb-logo.png" alt="" loading="lazy" />
          </div>
          <span class="status live">Live</span>
        </div>
        <h3>Muslim Daily Planner</h3>
        <p class="desc">Prayer times, daily Adhkar, habit tracking, and a clean day-planner — built for Muslims who want focus and Barakah in their routine.</p>
        <div class="mock-planner" style="margin-top: 18px" aria-hidden="true">
          <div class="head"><b>Wed · 9 Sha'ban</b><span>NYC · 40.7°N</span></div>
          <div class="prayers">
            <div class="pr"><div class="t">Fajr</div><div class="l">5:14</div></div>
            <div class="pr"><div class="t">Dhuhr</div><div class="l">12:48</div></div>
            <div class="pr on"><div class="t">Asr</div><div class="l">4:22</div></div>
            <div class="pr"><div class="t">Mag</div><div class="l">7:36</div></div>
            <div class="pr"><div class="t">Isha</div><div class="l">9:02</div></div>
          </div>
          <div class="tasks">
            <div class="task done">Morning Adhkar<span class="time">5:30</span></div>
            <div class="task">Read 1 page Qur'an<span class="time">PM</span></div>
          </div>
        </div>
        <div class="foot">
          <span class="price">Web</span>
          <a href="https://muslimdailyplanner.com" class="cta">Try</a>
        </div>
      </article>

      <!-- 03 · Education CRM -->
      <article class="p soon" id="saas-education">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/alliances-edu.png" alt="" loading="lazy" />
          </div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>Education CRM</h3>
        <p class="desc">Lead-to-enrollment CRM for coaching centres, language schools, and tutoring businesses. Built on the Alliances engine.</p>
        <div class="foot">
          <span class="price">EARLY 2026 · Q3</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 04 · Real Estate CRM -->
      <article class="p soon" id="saas-realestate">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/real-state-crm.png" alt="" loading="lazy" />
          </div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>Real Estate CRM</h3>
        <p class="desc">A property-first CRM. Listings, buyer matches, viewing schedules, and a follow-up engine that respects the long sales cycle.</p>
        <div class="foot">
          <span class="price">2026 · Q4</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 05 · Duty Timer -->
      <article class="p soon" id="saas-dutytimer">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/dutytimer.png" alt="" loading="lazy" />
          </div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>Duty Timer</h3>
        <p class="desc">Mobile check-in / check-out for field teams, security, hospitality, and on-site work. Geofence, photo proof, and clean reporting.</p>
        <div class="foot">
          <span class="price">MOBILE · BETA Q1 2027</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 06 · Email Marketing -->
      <article class="p soon" id="saas-email">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/ems.png" alt="" loading="lazy" />
          </div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>Email Marketing Suite</h3>
        <p class="desc">Campaigns, automations, and lifecycle flows for small teams. Honest pricing, no per-contact gotchas.</p>
        <div class="foot">
          <span class="price">2027</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 07 · Competitor Analysis -->
      <article class="p soon" id="saas-competitor">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/cma.png" alt="" loading="lazy" />
          </div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>Competitor Marketing Analysis</h3>
        <p class="desc">Track your competitors' ads, landing pages, SEO movement, and product launches — all in one weekly digest.</p>
        <div class="foot">
          <span class="price">2027 · Q2</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ============== TECH STACK MARQUEE (hero-width) ============== -->
<section class="tech-marquee-wrap" aria-label="Our technology stack">
  <div class="wrap">
    <div class="tech-marquee">
      <?php
        // Real multi-color brand logos via the Iconify "logos" collection.
        $tech_stack = [
          ['name' => 'Laravel',       'slug' => 'laravel'],
          ['name' => 'PHP',           'slug' => 'php'],
          ['name' => 'React',         'slug' => 'react'],
          ['name' => 'Shopify',       'slug' => 'shopify'],
          ['name' => 'Tailwind CSS',  'slug' => 'tailwindcss'],
          ['name' => 'DigitalOcean',  'slug' => 'digital-ocean'],
          ['name' => 'AWS',           'slug' => 'aws'],
          ['name' => 'MySQL',         'slug' => 'mysql'],
          ['name' => 'Gemini Flash',  'slug' => 'google-gemini'],
          ['name' => 'Claude Code',   'slug' => 'claude-icon'],
          ['name' => 'Redis',         'slug' => 'redis'],
        ];
        $render_item = function(array $t) {
          $src = 'https://api.iconify.design/logos/' . rawurlencode($t['slug']) . '.svg';
          echo '<div class="tech-item" title="' . e($t['name']) . '">'
             .   '<img src="' . e($src) . '" alt="' . e($t['name']) . '" width="68" height="68" loading="lazy" decoding="async" />'
             . '</div>';
        };
      ?>
      <div class="tech-track" aria-hidden="true">
        <?php foreach ($tech_stack as $t) $render_item($t); ?>
        <?php foreach ($tech_stack as $t) $render_item($t); ?>
      </div>
      <span class="sr-only">
        Technology stack: <?= e(implode(', ', array_column($tech_stack, 'name'))) ?>.
      </span>
    </div>
  </div>
</section>

<!-- ============== SHOPIFY APPS ============== -->
<section id="apps" aria-labelledby="apps-title">
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow">Section 02 · Shopify Apps</div>
        <h2 id="apps-title" style="margin-top: 14px">Shopify apps,<br>focused tools.</h2>
      </div>
      <div class="meta">
        <span><b>8</b> apps</span>
        <span class="dot">·</span>
        <span>1 live</span>
        <span class="dot">·</span>
        <span>1 acquired</span>
        <span class="dot">·</span>
        <span>6 coming</span>
      </div>
    </div>

    <div class="legend" aria-label="Status legend">
      <span class="l live">Live on Shopify</span>
      <span class="l soon">Coming Soon</span>
      <span class="l sold">Acquired</span>
    </div>

    <div class="pgrid">
      <!-- 01 · Appointment Booking -->
      <article class="p p-feat" id="app-appointment">
        <div class="top">
          <div class="ic ic-img bg-white" aria-hidden="true">
            <img src="/assets/dq-appointment-booking-app.png" alt="" loading="lazy" />
          </div>
          <span class="status submitted">Ongoing</span>
        </div>
        <div class="body">
          <div class="text">
            <h3>DQ: Appointment Booking App</h3>
            <p class="desc">Let customers book services, classes, and consultations directly from your product pages. Calendar sync, staff schedules, deposits, SMS reminders.</p>
            <div class="feat-chips" aria-label="Features">
              <span class="chip-f on">Native widget</span>
              <span class="chip-f">Google sync</span>
              <span class="chip-f">Outlook sync</span>
              <span class="chip-f">Staff schedules</span>
              <span class="chip-f">Buffer times</span>
              <span class="chip-f">Deposits</span>
              <span class="chip-f">SMS reminders</span>
              <span class="chip-f">OS 2.0</span>
            </div>
          </div>
          <div class="mock-book" aria-hidden="true">
            <div class="h">
              <h6>Book a session</h6>
              <span class="sub">30 MIN · FREE</span>
            </div>
            <div class="cal">
              <div class="d off">28</div><div class="d off">29</div><div class="d off">30</div>
              <div class="d avail">1</div><div class="d avail">2</div><div class="d">3</div><div class="d">4</div>
              <div class="d avail">5</div><div class="d avail">6</div><div class="d avail">7</div><div class="d sel">8</div><div class="d avail">9</div><div class="d">10</div><div class="d">11</div>
              <div class="d avail">12</div><div class="d avail">13</div><div class="d avail">14</div><div class="d avail">15</div><div class="d avail">16</div><div class="d">17</div><div class="d">18</div>
              <div class="d avail">19</div><div class="d avail">20</div><div class="d avail">21</div><div class="d avail">22</div><div class="d avail">23</div><div class="d">24</div><div class="d">25</div>
            </div>
            <div class="slots flex">
              <div class="slot">10:00</div>
              <div class="slot on">11:00</div>
              <div class="slot">1:00</div>
            </div>
          </div>
        </div>
        <div class="foot">
          <span class="price">SHOPIFY APP STORE · 14-day trial</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 02 · PreOrder (Acquired) -->
      <article class="p sold" id="app-preorder">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/pre-order.jpeg" alt="" loading="lazy" />
          </div>
          <span class="status sold">Acquired 2024</span>
        </div>
        <h3>DQ: PreOrder &amp; Request Stock</h3>
        <p class="desc">Our flagship preorder app. Grew to 100+ merchants over two years, then acquired by a larger Shopify partner in 2024.</p>
        <div class="acq-story" style="margin-top: 16px" aria-hidden="true">
          <div class="key">EXIT SUMMARY</div>
          <div class="row"><span>Merchants</span><span class="v">100+</span></div>
          <div class="row"><span>App-store rating</span><span class="v">5 ★</span></div>
          <div class="row"><span>Run</span><span class="v">2 years</span></div>
        </div>
        <div class="foot">
          <span class="price">EXITED 2024 · NEW HOME</span>
          <span class="cta muted">Acquired</span>
        </div>
      </article>

      <!-- 03 · CRM for B2B -->
      <article class="p soon" id="app-crm-b2b">
        <div class="top">
          <div class="ic alt-dark" aria-hidden="true">CB</div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: CRM for B2B</h3>
        <p class="desc">Native CRM for B2B merchants on Shopify. Account hierarchies, rep assignments, account-based pipelines.</p>
        <div class="foot">
          <span class="price">B2B · 2026</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 04 · Wholesale Pricing Discount -->
      <article class="p soon" id="app-wholesale">
        <div class="top">
          <div class="ic alt-tint" aria-hidden="true">WP</div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: Wholesale Pricing &amp; Discount</h3>
        <p class="desc">Customer-group pricing, tiered volume discounts, net-30 terms, and order minimums on Shopify Functions.</p>
        <div class="foot">
          <span class="price">PRICING · 2026 Q3</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 05 · Request a Quote, Hide Price -->
      <article class="p soon" id="app-quote">
        <div class="top">
          <div class="ic ic-img bg-green" aria-hidden="true">
            <img src="/assets/dq_request_quote.png" alt="" loading="lazy" />
          </div>
          <span class="status live">Live on Shopify</span>
        </div>
        <h3>DQLab Request Quote Hide Price</h3>
        <p class="desc">Gate prices behind login or form. Capture quote requests with custom fields and admin-approval flow.</p>
        <div class="foot">
          <span class="price">SALES · 2026 Q3</span>
          <a href="/dqlab-request-quote-hide-price" class="cta">View app</a>
        </div>
      </article>

      <!-- 06 · Live Product Options -->
      <article class="p soon" id="app-options">
        <div class="top">
          <div class="ic" aria-hidden="true">LO</div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: Live Product Options</h3>
        <p class="desc">Real-time product configurators — color swatches, custom fields, conditional logic, live price preview, image overlays.</p>
        <div class="foot">
          <span class="price">CUSTOMIZE · 2027</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 07 · Subscription Plus -->
      <article class="p soon" id="app-subscription">
        <div class="top">
          <div class="ic alt-dark" aria-hidden="true">SP</div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: Subscription Plus</h3>
        <p class="desc">Subscriptions for Shopify Plus. Mixed cart, swap-anytime, dunning recovery, and merchant-branded customer portal.</p>
        <div class="foot">
          <span class="price">PLUS · 2027 Q1</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>

      <!-- 08 · Checkout Extensions Plus -->
      <article class="p soon" id="app-checkout">
        <div class="top">
          <div class="ic alt-tint" aria-hidden="true">CE</div>
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: Checkout Extensions Plus</h3>
        <p class="desc">A bundle of checkout extensions for Plus merchants — upsells, gift notes, custom blocks, and one-click order bumps.</p>
        <div class="foot">
          <span class="price">CHECKOUT · 2027</span>
          <a href="#contact" class="cta">Get notified</a>
        </div>
      </article>
    </div>
  </div>
</section>

<!-- ============== CONTACT ============== -->
<?php require __DIR__ . '/inc/contact-section.php'; ?>

</main>

<!-- ============== FOOTER ============== -->
<footer class="site-footer" role="contentinfo">
  <div class="wrap">
    <div class="footer-grid">
      <div class="footer-col">
        <a href="#top" class="brand" aria-label="<?= e($CONFIG['site_name']) ?> — back to top">
          <span class="brand-mark"><img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" height="40" /></span>
        </a>
        <p style="color:#8a8d85; font-size:14px; margin-top:18px; max-width: 34ch;">
          A small product lab. We build SaaS and Shopify apps for teams that care about craft.
        </p>
      </div>
      <nav class="footer-col" aria-label="SaaS products">
        <h5>SaaS</h5>
        <ul>
          <li><a href="#saas-alliances">Alliances PRO</a></li>
          <li><a href="#saas-planner">Muslim Daily Planner</a></li>
          <li><a href="#saas">All SaaS products</a></li>
        </ul>
      </nav>
      <nav class="footer-col" aria-label="Shopify apps">
        <h5>Shopify Apps</h5>
        <ul>
          <li><a href="#app-appointment">Appointment Booking</a></li>
          <li><a href="#app-preorder">PreOrder (acquired)</a></li>
          <li><a href="#apps">All Shopify apps</a></li>
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

<?php if ($form_sent): ?>
<script>
  // Scroll to success state on PRG return.
  if (location.hash !== '#contact') location.hash = '#contact';
</script>
<?php endif; ?>

</body>
</html>
