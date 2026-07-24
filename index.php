<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — single-page PHP site
 * ============================================================ */

// ---------- Config ----------
$CONFIG = [
    'site_name'        => 'Deque Lab',
    'site_tagline'     => 'SaaS & Shopify products',
    'site_description' => 'Deque Lab is a small product lab building SaaS products (Alliances PRO, Muslim Daily Planner) and Shopify apps. Live products, coming soon, and one acquired app.',
    'site_keywords'    => 'Deque Lab, Alliances PRO, Muslim Daily Planner, Shopify apps, SaaS products, Mini CRM, Appointment Booking Shopify, PreOrder Shopify, Education CRM, Real Estate CRM',
    'site_locale'      => 'en_US',
    'site_author'      => 'Deque Lab',
    'site_founded'     => '2021',
    'contact_email'    => 'inafeeur@gmail.com',
    'linkedin'         => 'https://linkedin.com/company/deque-lab',
    'twitter'          => 'https://x.com/dequelab',
    'twitter_handle'   => '@dequelab',
];

// Resolve canonical URL from current request (works in any environment).
$scheme  = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)
) ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'dequelab.com';
$path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');
$CONFIG['site_url']      = $scheme . '://' . $host;
$CONFIG['canonical_url'] = $scheme . '://' . $host . $path;
$CONFIG['logo']          = '/assets/dqlab-small.png';      // square mark — hero, favicon, OG, schema
$CONFIG['logo_full']     = '/assets/dqlab.png';                   // wordmark — header + footer
$CONFIG['og_image']      = $CONFIG['site_url'] . $CONFIG['logo'];

// ---------- Session (CSRF + flash) ----------
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_samesite' => 'Lax',
        'cookie_secure'   => $scheme === 'https',
    ]);
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ---------- Helpers ----------
function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// Obvious spam-bot content: link markup, shorteners, classic spam phrases.
function looks_like_spam(string $text): bool {
    if (preg_match('~\[url=|\[link=|<a\s+href~i', $text)) {
        return true;
    }
    static $phrases = [
        'jackpot', 'casino', 'lottery', 'viagra', 'cialis', 'betting',
        'earn money fast', 'work from home and earn', 'million dollar',
        'crypto giveaway', 'investment opportunity', 'sex cam', 'xxx',
        '1borsa.com', 'bit.ly', 'tinyurl.', 'cutt.ly', 'is.gd',
    ];
    foreach ($phrases as $p) {
        if (stripos($text, $p) !== false) {
            return true;
        }
    }
    return false;
}

// ---------- Contact form options (rendered + validated server-side) ----------
$INTEREST_SAAS = [
    'Alliances PRO',
    'Muslim Daily Planner',
    'Education CRM (waitlist)',
    'Real Estate CRM (waitlist)',
    'Duty Timer (waitlist)',
    'Email Marketing Suite (waitlist)',
    'Competitor Marketing Analysis (waitlist)',
];
$INTEREST_SHOPIFY = [
    'DQ: Appointment Booking',
    'DQ: CRM for B2B (waitlist)',
    'DQ: Wholesale Pricing (waitlist)',
    'DQ: Request a Quote / Hide Price (waitlist)',
    'DQ: Live Product Options (waitlist)',
    'DQ: Subscription Plus (waitlist)',
    'DQ: Checkout Extensions Plus (waitlist)',
];
$INTEREST_ALL = array_merge($INTEREST_SAAS, $INTEREST_SHOPIFY, ['Just saying hi']);

// ---------- Contact form handling ----------
$form_errors = [];
$form_data   = ['name' => '', 'email' => '', 'interest' => '', 'message' => ''];
$form_sent   = false;

// Flash success from a successful PRG redirect.
if (!empty($_SESSION['contact_sent'])) {
    $form_sent = true;
    unset($_SESSION['contact_sent']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['form_type'] ?? '') === 'contact') {

    // Honeypot: bots fill hidden field; act successful and bail.
    if (!empty($_POST['website'])) {
        $_SESSION['contact_sent'] = true;
        header('Location: ' . $path . '#contact', true, 303);
        exit;
    }
    
    // Time trap: humans need a few seconds between page load and submit.
    // Bots that fetch-then-post instantly get a fake success.
    $rendered_at = (int)($_SESSION['form_rendered_at'] ?? 0);
    if ($rendered_at === 0 || (time() - $rendered_at) < 4) {
        $_SESSION['contact_sent'] = true;
        header('Location: ' . $path . '#contact', true, 303);
        exit;
    }

    // CSRF
    if (!hash_equals($csrf_token, (string)($_POST['csrf_token'] ?? ''))) {
        $form_errors[] = 'Your session expired. Please refresh and try again.';
    }
    
    // Rate limit: max 3 sends per session per hour.
    $now = time();
    $_SESSION['contact_log'] = array_values(array_filter(
        (array)($_SESSION['contact_log'] ?? []),
        static fn($t) => (int)$t > $now - 3600
    ));
    if (count($_SESSION['contact_log']) >= 3) {
        $form_errors[] = 'Too many messages sent. Please try again in an hour, or email us directly.';
    }

    $form_data['name']     = trim((string)($_POST['name'] ?? ''));
    $form_data['email']    = trim((string)($_POST['email'] ?? ''));
    $form_data['interest'] = trim((string)($_POST['interest'] ?? ''));
    $form_data['message']  = trim((string)($_POST['msg'] ?? ''));

    // Interest must be one of the options we actually render.
    if ($form_data['interest'] !== '' && !in_array($form_data['interest'], $INTEREST_ALL, true)) {
        $form_data['interest'] = '';
    }

    // Known spam content: pretend success, send nothing.
    if (looks_like_spam($form_data['name'] . ' ' . $form_data['message'])) {
        $_SESSION['contact_sent'] = true;
        header('Location: ' . $path . '#contact', true, 303);
        exit;
    }

    // Links in the name field are never legitimate; links in the message
    // are almost always spam — ask the sender to describe instead.
    if (preg_match('~https?://|www\.~i', $form_data['name'])) {
        $_SESSION['contact_sent'] = true;
        header('Location: ' . $path . '#contact', true, 303);
        exit;
    }
    if (preg_match('~https?://|www\.~i', $form_data['message'])) {
        $form_errors[] = 'Please remove links from your message — describe it in words and we\'ll follow up by email.';
    }

    if ($form_data['name'] === '' || mb_strlen($form_data['name']) > 120) {
        $form_errors[] = 'Please enter your name.';
    }
    if ($form_data['email'] === '' || !filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $form_errors[] = 'Please enter a valid email address.';
    }
    if (mb_strlen($form_data['message']) > 5000) {
        $form_errors[] = 'Message is too long (max 5000 characters).';
    }
    foreach (['name', 'email', 'interest'] as $f) {
        if (preg_match('/[\r\n]/', $form_data[$f])) {
            $form_errors[] = 'Invalid characters in form input.';
            break;
        }
    }

    if (!$form_errors) {
        $domain = preg_replace('/^www\./', '', $host);
        $from   = 'noreply@' . $domain;

        $subject = '[Deque Lab] Contact: ' . ($form_data['interest'] !== '' ? $form_data['interest'] : 'New message');

        $body  = "New contact submission from " . $CONFIG['site_url'] . "\n";
        $body .= str_repeat('-', 56) . "\n";
        $body .= "Name:        " . $form_data['name'] . "\n";
        $body .= "Email:       " . $form_data['email'] . "\n";
        $body .= "Interest:    " . ($form_data['interest'] !== '' ? $form_data['interest'] : '(not specified)') . "\n";
        $body .= "Submitted:   " . gmdate('Y-m-d H:i:s') . " UTC\n";
        $body .= "IP:          " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
        $body .= "User-Agent:  " . substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200) . "\n";
        $body .= str_repeat('-', 56) . "\n\n";
        $body .= "Message:\n" . ($form_data['message'] !== '' ? $form_data['message'] : '(empty)') . "\n";

        $reply_name = preg_replace('/[^\p{L}\p{N} .\-_\']/u', '', $form_data['name']);
        $headers  = "From: Deque Lab Website <{$from}>\r\n";
        $headers .= "Reply-To: " . ($reply_name !== '' ? "{$reply_name} <{$form_data['email']}>" : $form_data['email']) . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $headers .= "Content-Transfer-Encoding: 8bit\r\n";

        $envelope = '-f' . $from;
        $sent = @mail($CONFIG['contact_email'], $subject, $body, $headers, $envelope);

        if ($sent) {
            // Post/Redirect/Get — prevent duplicate submission on refresh.
            $_SESSION['contact_sent']  = true;
            $_SESSION['csrf_token']    = bin2hex(random_bytes(32));
            $_SESSION['contact_log'][] = time();
            header('Location: ' . $path . '#contact', true, 303);
            exit;
        }
        $form_errors[] = 'Sorry, your message could not be sent. Please email us directly at ' . $CONFIG['contact_email'] . '.';
    }
}

// Timestamp this render for the submit-speed time trap.
$_SESSION['form_rendered_at'] = time();
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
    /* Accessibility: skip link */
    .skip-link {
      position: absolute; top: -40px; left: 8px;
      background: var(--ink); color: var(--paper);
      padding: 10px 14px; border-radius: 8px; z-index: 999;
      font-family: var(--f-mono); font-size: 12px;
      transition: top 150ms ease;
    }
    .skip-link:focus { top: 8px; outline: 2px solid var(--accent); outline-offset: 2px; }

    html { scroll-behavior: smooth; scroll-padding-top: 80px; }

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

    /* ============ Contact (light) ============ */
    .contact-wrap {
      background: #fff; color: var(--ink);
      border: 1px solid var(--line);
      border-radius: 28px;
      padding: clamp(40px, 5vw, 72px) clamp(28px, 5vw, 64px);
      display: grid; grid-template-columns: 1fr 1.1fr; gap: 56px;
      position: relative; overflow: hidden;
    }
    @media (max-width: 820px) { .contact-wrap { grid-template-columns: 1fr; gap: 40px; } }
    .contact-wrap::before {
      content: ""; position: absolute; inset: 0;
      background: radial-gradient(800px 500px at 110% 0%, color-mix(in oklch, var(--accent) 8%, transparent), transparent 60%);
      pointer-events: none;
    }
    .contact-wrap > * { position: relative; }

    .contact-wrap h2 {
      font-size: clamp(32px, 4.4vw, 56px); letter-spacing: -0.03em; line-height: 1.02; font-weight: 500;
    }
    .contact-wrap h2 em { font-style: normal; color: var(--accent); }
    .contact-wrap .lede { color: var(--muted); max-width: 42ch; margin-top: 20px; }

    .ctry { display: flex; gap: 12px; margin-top: 28px; flex-wrap: wrap; }
    .ctry .chip-link {
      display: inline-flex; align-items: center; gap: 10px;
      padding: 10px 16px;
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 999px; color: var(--ink);
      font-size: 13px; transition: border-color 150ms ease, background 150ms ease;
    }
    .ctry .chip-link:hover, .ctry .chip-link:focus { border-color: var(--accent); background: #fff; }
    .ctry .chip-link svg { width: 16px; height: 16px; }

    .form {
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 20px; padding: 28px;
    }
    .form-row { display: grid; gap: 18px; }
    .form-row .f label {
      display: block; font-family: var(--f-mono); font-size: 10px;
      letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted);
      margin-bottom: 8px;
    }
    .form-row .f label .req { color: var(--accent); }
    .form-row input, .form-row textarea, .form-row select {
      width: 100%; padding: 13px 14px;
      border: 1px solid var(--line); background: #fff; color: var(--ink);
      border-radius: 10px; font-family: var(--f-sans); font-size: 14px;
      transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .form-row input:focus, .form-row textarea:focus, .form-row select:focus {
      outline: none; border-color: var(--accent);
      box-shadow: 0 0 0 3px color-mix(in oklch, var(--accent) 14%, transparent);
    }
    .form-row input::placeholder, .form-row textarea::placeholder { color: var(--muted-2); }
    .form-row textarea { min-height: 110px; resize: vertical; }
    .form .grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    @media (max-width: 480px) { .form .grid2 { grid-template-columns: 1fr; } }
    .form .submit {
      margin-top: 20px;
      display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .form .submit .note { font-family: var(--f-mono); font-size: 11px; color: var(--muted); max-width: 30ch; line-height: 1.5; }
    .form button.btn { border: 0; cursor: pointer; }
    .form .hp { position: absolute; left: -9999px; width: 1px; height: 1px; overflow: hidden; }

    .form-success {
      background: color-mix(in oklch, var(--accent) 10%, white);
      border: 1px solid color-mix(in oklch, var(--accent) 35%, white);
      border-radius: 14px; padding: 28px;
      text-align: center;
    }
    .form-success .tick {
      width: 52px; height: 52px; margin: 0 auto 14px;
      background: var(--accent); color: var(--accent-ink); border-radius: 50%;
      display: grid; place-items: center; font-size: 24px;
    }
    .form-success h4 { font-size: 20px; font-weight: 500; color: var(--ink); margin: 0 0 6px; }
    .form-success p { color: var(--muted); font-size: 14px; }

    .form-errors {
      background: #fff5f5; border: 1px solid #f3c5c5;
      border-radius: 10px; padding: 14px 16px; margin-bottom: 18px;
      color: #8a2c2c; font-size: 13px;
    }
    .form-errors ul { margin: 0; padding-left: 18px; }

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

    /* visually-hidden for accessibility (used by screen-reader-only labels) */
    .sr-only {
      position: absolute; width: 1px; height: 1px;
      padding: 0; margin: -1px; overflow: hidden;
      clip: rect(0, 0, 0, 0); white-space: nowrap; border: 0;
    }
  </style>
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
          <span class="status live">Live on Shopify</span>
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
          <a href="#contact" class="cta">Install on Shopify</a>
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
          <span class="status soon">Coming Soon</span>
        </div>
        <h3>DQ: Request a Quote &amp; Hide Price</h3>
        <p class="desc">Gate prices behind login or form. Capture quote requests with custom fields and admin-approval flow.</p>
        <div class="foot">
          <span class="price">SALES · 2026 Q3</span>
          <a href="#contact" class="cta">Get notified</a>
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
<section id="contact" aria-labelledby="contact-title">
  <div class="wrap">
    <div class="contact-wrap">
      <div>
        <div class="eyebrow">Stay in the loop</div>
        <h2 id="contact-title" style="margin-top: 16px">
          Want early access<br>
          or a <em>question</em>?
        </h2>
        <p class="lede">
          Drop us a line. We reply to every message — usually within a business day.
          Tell us which product, and we'll add you to that waitlist or get on a call.
        </p>

        <div class="ctry">
          <a href="mailto:<?= e($CONFIG['contact_email']) ?>" class="chip-link" aria-label="Email <?= e($CONFIG['contact_email']) ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M3 7l9 6 9-6"/><rect x="3" y="5" width="18" height="14" rx="2"/></svg>
            <?= e($CONFIG['contact_email']) ?>
          </a>
          <a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer" class="chip-link" aria-label="Deque Lab on LinkedIn">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M4 4h4v4H4zM4 10h4v10H4zM10 10h4v2h.1c.6-1 2-2 4-2 4.3 0 5 2.8 5 6.5V20h-4v-6.4c0-1.5-.5-2.6-2-2.6s-2.4 1.1-2.4 2.6V20h-4z"/></svg>
            LinkedIn
          </a>
          <a href="<?= e($CONFIG['twitter']) ?>" target="_blank" rel="noopener noreferrer" class="chip-link" aria-label="Deque Lab on X (Twitter)">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.2 3H21l-6.6 7.5L22 21h-6.2l-5-6.4L5 21H2.2l7-8L2 3h6.3l4.5 5.9z"/></svg>
            <?= e($CONFIG['twitter_handle']) ?>
          </a>
        </div>
      </div>

      <div class="form" id="briefForm">
        <?php if ($form_sent): ?>
          <div class="form-success" role="status" aria-live="polite">
            <div class="tick" aria-hidden="true">✓</div>
            <h4>Thanks — got it.</h4>
            <p>We'll reply within a business day at the email you provided.</p>
          </div>
        <?php else: ?>
          <?php if ($form_errors): ?>
            <div class="form-errors" role="alert">
              <ul>
                <?php foreach ($form_errors as $err): ?>
                  <li><?= e($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="<?= e($path) ?>#contact" novalidate>
            <input type="hidden" name="form_type" value="contact" />
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>" />
            <!-- Honeypot: must remain empty -->
            <div class="hp" aria-hidden="true">
              <label for="website">Website (leave blank)</label>
              <input type="text" name="website" id="website" tabindex="-1" autocomplete="off" />
            </div>

            <div class="form-row">
              <div class="grid2">
                <div class="f">
                  <label for="name">Name <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="text" name="name" id="name" placeholder="Jane Rivera" required maxlength="120"
                         autocomplete="name" value="<?= e($form_data['name']) ?>" />
                </div>
                <div class="f">
                  <label for="email">Email <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="email" name="email" id="email" placeholder="jane@company.com" required maxlength="180"
                         autocomplete="email" value="<?= e($form_data['email']) ?>" />
                </div>
              </div>
              <div class="f">
                <label for="interest">I'm interested in</label>
                <select name="interest" id="interest">
                  <option value="">— Select a product —</option>
                  <optgroup label="SaaS">
                    <?php foreach ($INTEREST_SAAS as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $form_data['interest'] === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <optgroup label="Shopify Apps">
                    <?php foreach ($INTEREST_SHOPIFY as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $form_data['interest'] === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <option value="Just saying hi" <?= $form_data['interest'] === 'Just saying hi' ? 'selected' : '' ?>>Just saying hi</option>
                </select>
              </div>
              <div class="f">
                <label for="msg">Message</label>
                <textarea name="msg" id="msg" maxlength="5000" placeholder="Anything you'd like to share — questions, feedback, or what you're hoping the product does."><?= e($form_data['message']) ?></textarea>
              </div>
            </div>

            <div class="submit">
              <div class="note">No newsletter list. We'll only email you about what you asked about.</div>
              <button type="submit" class="btn btn-accent btn-arrow">Send message</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>

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
        <a href="/privacy.php">Privacy Policy</a>
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
