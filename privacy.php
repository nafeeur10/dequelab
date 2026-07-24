<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — Privacy Policy
 * ============================================================ */

$CONFIG = [
    'site_name'     => 'Deque Lab',
    'contact_email' => 'inafeeur@gmail.com',
    'linkedin'      => 'https://linkedin.com/company/deque-lab',
    'twitter'       => 'https://x.com/dequelab',
    'logo_full'     => '/assets/dqlab.png',
];

// Fixed at the date of the last substantive change — not date('Y'),
// which would misrepresent an untouched policy as freshly reviewed.
$LAST_UPDATED = 'July 17, 2026';

$scheme = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)
) ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'dequelab.com';
$CONFIG['site_url']      = $scheme . '://' . $host;
$CONFIG['canonical_url'] = $CONFIG['site_url'] . '/privacy.php';

function e(?string $v): string {
    return htmlspecialchars($v ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Privacy Policy · <?= e($CONFIG['site_name']) ?></title>
  <meta name="description" content="How Deque Lab collects, uses, shares, and protects information across our SaaS products and Shopify apps." />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="icon" type="image/png" href="/assets/logo-mark.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "WebPage",
    "name": "Privacy Policy",
    "url": "<?= e($CONFIG['canonical_url']) ?>",
    "description": "How Deque Lab collects, uses, shares, and protects information.",
    "inLanguage": "en",
    "publisher": {
      "@type": "Organization",
      "name": "<?= e($CONFIG['site_name']) ?>",
      "url": "<?= e($CONFIG['site_url']) ?>/"
    }
  }
  </script>

  <style>
    .skip-link {
      position: absolute; top: -40px; left: 8px;
      background: var(--ink); color: var(--paper);
      padding: 10px 14px; border-radius: 8px; z-index: 999;
      font-family: var(--f-mono); font-size: 12px;
      transition: top 150ms ease;
    }
    .skip-link:focus { top: 8px; outline: 2px solid var(--accent); outline-offset: 2px; }

    html { scroll-behavior: smooth; scroll-padding-top: 90px; }

    .legal-head { padding: 56px 0 32px; border-bottom: 1px solid var(--line); }
    .legal-head h1 {
      font-size: clamp(34px, 4.4vw, 52px);
      line-height: 1.05; letter-spacing: -0.035em; font-weight: 500;
      margin: 18px 0 0;
    }
    .legal-head .lede {
      margin-top: 18px; max-width: 60ch;
      color: var(--muted); font-size: 16px; line-height: 1.6;
    }
    .legal-updated {
      margin-top: 22px; display: inline-flex; align-items: center; gap: 8px;
      font-family: var(--f-mono); font-size: 12px; color: var(--muted);
      background: var(--paper-2); border: 1px solid var(--line);
      padding: 8px 12px; border-radius: 999px;
    }

    .legal-layout {
      display: grid; grid-template-columns: 220px 1fr;
      gap: 56px; padding: 48px 0 80px; align-items: start;
    }
    @media (max-width: 900px) { .legal-layout { grid-template-columns: 1fr; gap: 32px; } }

    .legal-toc { position: sticky; top: 90px; }
    @media (max-width: 900px) { .legal-toc { position: static; } }
    .legal-toc h2 {
      font-family: var(--f-mono);
      font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase;
      color: var(--muted); margin: 0 0 16px; font-weight: 500;
    }
    .legal-toc ol { list-style: none; margin: 0; padding: 0; display: grid; gap: 9px; counter-reset: toc; }
    .legal-toc li { counter-increment: toc; }
    .legal-toc a {
      color: var(--muted); font-size: 13.5px; line-height: 1.4;
      display: flex; gap: 10px; transition: color 120ms ease;
    }
    .legal-toc a::before {
      content: counter(toc, decimal-leading-zero);
      font-family: var(--f-mono); font-size: 11px; color: var(--line-2);
      padding-top: 2px;
    }
    .legal-toc a:hover { color: var(--accent); }
    .legal-toc a:hover::before { color: var(--accent); }

    .legal-body { max-width: 68ch; }
    /* Opt out of the global `section` padding — prose sections set their own rhythm. */
    .legal-body section { padding: 0; }
    .legal-body section + section { margin-top: 44px; }
    .legal-body h2 {
      font-size: clamp(20px, 2vw, 24px); letter-spacing: -0.02em;
      font-weight: 500; margin: 0 0 14px; color: var(--ink);
      scroll-margin-top: 90px;
    }
    .legal-body h2 .n {
      font-family: var(--f-mono); font-size: 12px; color: var(--accent);
      margin-right: 10px; vertical-align: 2px;
    }
    .legal-body h3 {
      font-size: 15px; font-weight: 600; letter-spacing: -0.01em;
      margin: 24px 0 6px; color: var(--ink-2);
    }
    .legal-body p { color: var(--muted); line-height: 1.68; margin: 0 0 12px; }
    .legal-body ul { margin: 10px 0 12px; padding-left: 18px; display: grid; gap: 9px; }
    .legal-body li { color: var(--muted); line-height: 1.6; }
    .legal-body li strong { color: var(--ink-2); font-weight: 600; }
    .legal-body a { color: var(--accent); text-decoration: underline; text-underline-offset: 2px; }

    .legal-note {
      background: var(--paper-2); border: 1px solid var(--line);
      border-radius: 14px; padding: 20px 22px; margin-top: 18px;
    }
    .legal-note p:last-child { margin-bottom: 0; }

    .legal-contact {
      margin-top: 48px; padding: 28px;
      background: #fff; border: 1px solid var(--line); border-radius: 18px;
    }
    .legal-contact h2 { margin-bottom: 10px; }
    .legal-contact .rows { display: grid; gap: 10px; margin-top: 16px; }
    .legal-contact .row {
      display: flex; justify-content: space-between; gap: 16px;
      padding-bottom: 10px; border-bottom: 1px dashed var(--line);
      font-size: 14px;
    }
    .legal-contact .row:last-child { border-bottom: 0; padding-bottom: 0; }
    .legal-contact .row span:first-child {
      font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--muted); padding-top: 3px;
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
        <span class="brand-mark">
          <img src="<?= e($CONFIG['logo_full']) ?>" alt="<?= e($CONFIG['site_name']) ?>" height="32" />
        </span>
      </a>
      <div class="nav-links">
        <a href="/#saas">SaaS</a>
        <a href="/#apps">Shopify Apps</a>
        <a href="/#contact">Contact</a>
        <a href="/#contact" class="btn btn-accent btn-sm nav-cta">Get notified →</a>
      </div>
    </div>
  </nav>
</header>

<main id="main">

  <div class="wrap">
    <div class="legal-head">
      <div class="eyebrow">Legal · <?= e($CONFIG['site_name']) ?></div>
      <h1>Privacy Policy</h1>
      <p class="lede">
        Thank you for using Deque Lab. We respect your privacy and are committed to protecting the
        personal information of our users. This policy explains how we collect, use, and share
        information in connection with our SaaS products and our Shopify apps.
      </p>
      <div class="legal-updated">Last updated · <?= e($LAST_UPDATED) ?></div>
    </div>

    <div class="legal-layout">

      <aside class="legal-toc" aria-label="Sections">
        <h2>Contents</h2>
        <ol>
          <li><a href="#collect">Information we collect</a></li>
          <li><a href="#use">How we use your information</a></li>
          <li><a href="#share">How we share your information</a></li>
          <li><a href="#cookies">Cookies</a></li>
          <li><a href="#retention">Data retention</a></li>
          <li><a href="#rights">Your rights</a></li>
          <li><a href="#security">Security</a></li>
          <li><a href="#changes">Changes to this policy</a></li>
          <li><a href="#contact">Contact us</a></li>
        </ol>
      </aside>

      <div class="legal-body">

        <section id="scope">
          <h2>Who this policy covers</h2>
          <p>
            This policy applies to Deque Lab (“we”, “us”, “our”), this website, our SaaS products —
            including Alliances PRO and Muslim Daily Planner — and our Shopify apps, including
            DQ: Appointment Booking, DQ: Request a Quote &amp; Hide Price, and other apps we publish.
          </p>
          <p>
            Where we build an app for the Shopify ecosystem, we act as a processor of your store’s
            data on your behalf, and you remain the controller of your customers’ personal information.
          </p>
        </section>

        <section id="collect">
          <h2><span class="n">01</span>Information we collect</h2>
          <p>When you install and use a Deque Lab product, we may collect the following:</p>
          <ul>
            <li><strong>Store information.</strong> For our Shopify apps, we collect store details such as store name, store ID, myshopify domain, plan, and the email address on the store account.</li>
            <li><strong>Account information.</strong> For our SaaS products, we collect the details you provide when creating an account — name, email address, organization, and authentication credentials.</li>
            <li><strong>Usage data.</strong> Information about how you use the product, including actions taken in the app, settings changes, feature usage, and interactions with customer support.</li>
            <li><strong>Customer data.</strong> To deliver app functionality — such as creating quote requests, custom pricing, or bookings — we may process your customers’ information, including name, email address, and order history.</li>
            <li><strong>Billing information.</strong> Where a paid plan applies, billing is handled through the Shopify Billing API or our payment processor. We receive subscription and transaction records; we do not store full card numbers.</li>
            <li><strong>Contact form submissions.</strong> If you contact us through this website, we receive the name, email address, product interest, and message you submit.</li>
          </ul>
          <div class="legal-note">
            <p>
              We do not intentionally collect sensitive categories of personal data, and we ask that you
              do not submit them to us through free-text fields such as quote notes or support messages.
            </p>
          </div>
        </section>

        <section id="use">
          <h2><span class="n">02</span>How we use your information</h2>
          <p>We use the information we collect to:</p>
          <ul>
            <li>Provide, operate, and maintain the functionality of our products.</li>
            <li>Improve, personalize, and expand our services.</li>
            <li>Process orders, quote requests, and bookings, and manage your account.</li>
            <li>Communicate with you about updates, improvements, and support requests.</li>
            <li>Analyze usage patterns to improve reliability and the user experience.</li>
            <li>Detect, investigate, and prevent abuse, fraud, and security incidents.</li>
            <li>Comply with our legal and contractual obligations.</li>
          </ul>
          <p>We do not sell your personal information, and we do not use your customers’ data to train third-party AI models.</p>
        </section>

        <section id="share">
          <h2><span class="n">03</span>How we share your information</h2>
          <p>We share information only in the following circumstances:</p>
          <ul>
            <li><strong>With Shopify.</strong> Our apps operate inside the Shopify ecosystem, so certain data is exchanged with Shopify in accordance with Shopify’s own privacy policy and API terms.</li>
            <li><strong>Service providers.</strong> We share information with third-party providers that help us deliver our services — for example cloud hosting, error monitoring, analytics, email delivery, and customer support tooling. They may process the data only on our instructions.</li>
            <li><strong>Legal compliance.</strong> We may disclose information where required by law, or where we believe disclosure is reasonably necessary to comply with a legal obligation, enforce our terms, or protect the rights and safety of our users.</li>
            <li><strong>Business transfers.</strong> If a product is acquired or transferred — as happened with DQ: PreOrder &amp; Request Stock in 2024 — information may be transferred as part of that transaction. We will notify affected merchants before their data becomes subject to a different policy.</li>
          </ul>
        </section>

        <section id="cookies">
          <h2><span class="n">04</span>Cookies</h2>
          <p>
            This website sets a first-party session cookie that keeps your contact form submission
            secure against cross-site request forgery. It is strictly necessary for the site to work
            and is not used for advertising or cross-site tracking.
          </p>
          <p>
            Our apps and SaaS products use cookies and similar storage to keep you signed in and to
            remember your preferences. Blocking these will prevent the products from functioning.
          </p>
        </section>

        <section id="retention">
          <h2><span class="n">05</span>Data retention</h2>
          <p>
            We retain your information for as long as necessary to provide our services to you and to
            comply with our legal obligations. If you uninstall one of our Shopify apps, we securely
            delete the store’s data within a reasonable time frame, and in any case in line with
            Shopify’s data-retention requirements for app partners.
          </p>
          <p>
            If you close a SaaS account, we delete or anonymize the associated data after a short grace
            period that allows for accidental deletion to be reversed. Backups are purged on their
            normal rotation schedule.
          </p>
        </section>

        <section id="rights">
          <h2><span class="n">06</span>Your rights</h2>
          <p>Depending on where you live, you may have the right to:</p>
          <ul>
            <li><strong>Access.</strong> Request a copy of the data we hold about you.</li>
            <li><strong>Rectification.</strong> Ask us to correct inaccurate or incomplete information.</li>
            <li><strong>Deletion.</strong> Request deletion of your personal information, subject to legal retention obligations.</li>
            <li><strong>Portability.</strong> Receive your data in a structured, commonly used, machine-readable format.</li>
            <li><strong>Objection and restriction.</strong> Object to or ask us to restrict certain processing.</li>
          </ul>
          <p>
            To exercise any of these, email us at
            <a href="mailto:<?= e($CONFIG['contact_email']) ?>"><?= e($CONFIG['contact_email']) ?></a>.
            We respond within 30 days. If you are a shopper whose data we process on behalf of a
            merchant, please contact that merchant first — we will assist them in responding to you.
          </p>
        </section>

        <section id="security">
          <h2><span class="n">07</span>Security</h2>
          <p>
            We take reasonable measures to protect your data, including encryption in transit, access
            controls, and secure storage practices. However, no method of transmission over the
            internet or method of electronic storage is 100% secure, and we cannot guarantee absolute
            security.
          </p>
          <p>
            If you believe you have found a security issue in one of our products, please report it to
            <a href="mailto:<?= e($CONFIG['contact_email']) ?>"><?= e($CONFIG['contact_email']) ?></a>
            and we will work with you on a fix.
          </p>
        </section>

        <section id="changes">
          <h2><span class="n">08</span>Changes to this policy</h2>
          <p>
            We may update this Privacy Policy from time to time. If we make significant changes, we will
            notify you by email or through an in-app notification, and we will update the “last updated”
            date at the top of this page. Your continued use of our products after such changes
            constitutes acceptance of the updated policy.
          </p>
        </section>

        <div class="legal-contact" id="contact">
          <h2>Contact us</h2>
          <p>Questions about this policy, or about how we handle your data? Reach out — a person reads every message.</p>
          <div class="rows">
            <div class="row">
              <span>Email</span>
              <a href="mailto:<?= e($CONFIG['contact_email']) ?>"><?= e($CONFIG['contact_email']) ?></a>
            </div>
            <div class="row">
              <span>Contact form</span>
              <a href="/#contact">dequelab.com/#contact</a>
            </div>
            <div class="row">
              <span>LinkedIn</span>
              <a href="<?= e($CONFIG['linkedin']) ?>" target="_blank" rel="noopener noreferrer">/company/deque-lab</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</main>

<!-- ============== FOOTER ============== -->
<footer class="site-footer" role="contentinfo">
  <div class="wrap">
    <div class="footer-meta">
      <span>© <?= e(date('Y')) ?> <?= e($CONFIG['site_name']) ?></span>
      <span><a href="/" style="color:var(--muted)">← Back to Deque Lab</a></span>
    </div>
  </div>
</footer>

</body>
</html>
