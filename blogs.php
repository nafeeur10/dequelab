<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — blog index
 * Newsletter hero, then one filterable grid of every post.
 * Post data comes from inc/posts.php; the subscribe box is
 * stored and processed by inc/newsletter.php.
 * ============================================================ */

require __DIR__ . '/inc/lib.php';
require __DIR__ . '/inc/posts.php';
// Ahead of the contact processor: a newsletter POST answers with its own
// redirect and never reaches the contact form's validation at all.
require __DIR__ . '/inc/newsletter.php';

$CONFIG['page_title']       = 'Blog — Shopify B2B, wholesale and app guides · Deque Lab';
$CONFIG['page_description'] = 'Practical guides on selling B2B and wholesale on Shopify: what the native features cover, which apps are worth paying for, and how to build a quote flow that converts.';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/blogs';
// Shares the article's card rather than carrying its own. Everything on this
// index is Shopify B2B and wholesale today, so the artwork is representative —
// worth revisiting if the blog broadens beyond that.
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/og-shopify-b2b-starter-guide.jpg';

// The contact block at the foot of the page.
$contact_default_interest = 'Just saying hi';
$contact_eyebrow = 'Working on something similar?';
$contact_heading = 'Got a question<br>we have not written up?';
$contact_lede    = 'Ask it and we will answer — and if the answer is useful to more than one person, it becomes the next article.';

require __DIR__ . '/inc/contact-process.php';

$categories = dq_categories_in_use();
$all_posts  = dq_posts();
$live_count = count(dq_posts(null, true));

// ItemList rather than Blog: this page is a list of links to articles, and
// each article carries its own BlogPosting schema. Only live posts go in —
// a coming-soon card has no URL to point at.
$item_list = [];
foreach (dq_posts(null, true) as $i => $p) {
    $item_list[] = [
        '@type'    => 'ListItem',
        'position' => $i + 1,
        'url'      => $CONFIG['site_url'] . '/' . $p['slug'],
        'name'     => $p['title'],
    ];
}

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
            '@type'       => 'CollectionPage',
            '@id'         => $CONFIG['canonical_url'] . '#page',
            'url'         => $CONFIG['canonical_url'],
            'name'        => 'Blog Articles',
            'description' => $CONFIG['page_description'],
            'isPartOf'    => ['@id' => $CONFIG['site_url'] . '/#organization'],
            'inLanguage'  => 'en',
            'mainEntity'  => [
                '@type'           => 'ItemList',
                'numberOfItems'   => count($item_list),
                'itemListElement' => $item_list,
            ],
        ],
        [
            '@type'           => 'BreadcrumbList',
            '@id'             => $CONFIG['canonical_url'] . '#breadcrumb',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $CONFIG['site_url'] . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog'],
            ],
        ],
    ],
];
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title><?= e($CONFIG['page_title']) ?></title>
  <meta name="description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="author" content="<?= e($CONFIG['site_author']) ?>" />
  <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1" />
  <meta name="theme-color" content="#0e1210" />
  <meta name="color-scheme" content="light" />
  <link rel="canonical" href="<?= e($CONFIG['canonical_url']) ?>" />

  <meta property="og:type" content="website" />
  <meta property="og:site_name" content="<?= e($CONFIG['site_name']) ?>" />
  <meta property="og:locale" content="<?= e($CONFIG['site_locale']) ?>" />
  <meta property="og:title" content="Blog Articles · <?= e($CONFIG['site_name']) ?>" />
  <meta property="og:description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta property="og:url" content="<?= e($CONFIG['canonical_url']) ?>" />
  <meta property="og:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta property="og:image:width" content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:image:alt" content="Deque Lab blog — guides on Shopify B2B and wholesale." />

  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:site" content="<?= e($CONFIG['twitter_handle']) ?>" />
  <meta name="twitter:title" content="Blog Articles · <?= e($CONFIG['site_name']) ?>" />
  <meta name="twitter:description" content="<?= e($CONFIG['page_description']) ?>" />
  <meta name="twitter:image" content="<?= e($CONFIG['og_image']) ?>" />
  <meta name="twitter:image:alt" content="Deque Lab blog — guides on Shopify B2B and wholesale." />

  <link rel="icon" type="image/png" href="<?= e($CONFIG['logo']) ?>" />
  <link rel="apple-touch-icon" href="<?= e($CONFIG['logo']) ?>" />

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="/assets/styles.css" />

  <script type="application/ld+json"><?= json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_PRETTY_PRINT) ?></script>

  <style>
    html { scroll-padding-top: 90px; }
    /* The listing carries the page, so it starts tight under the hero rather
       than opening the full section rhythm twice in a row. */
    .blog-list { padding-top: 8px; }
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
        <a href="/blogs" class="active" aria-current="page">Blog</a>
      </div>
      <div class="nav-actions">
        <a href="/#contact" class="btn btn-accent btn-sm nav-cta">Contact →</a>
      </div>
    </div>
  </nav>
</header>

<main id="main">

<!-- ============== HERO + SUBSCRIBE ============== -->
<section class="nl-hero" id="subscribe" aria-labelledby="blog-title">
  <div class="wrap">
    <div class="nl-grid">
      <div>
        <span class="nl-pill">🚀 Our blogs</span>
        <h1 id="blog-title">Get updated with the latest Shopify B2B guides</h1>
        <p class="lede">Everything we learn building Shopify apps and SaaS — written up
          so you do not have to learn it the expensive way. One email when a new guide lands.</p>

        <?php if ($nl_sent): ?>
          <p class="nl-msg ok" role="status">
            <span aria-hidden="true">✓</span>
            <span><b>You are on the list.</b> The next guide goes straight to your inbox — nothing else.</span>
          </p>
        <?php else: ?>
          <?php if ($nl_error !== ''): ?>
            <p class="nl-msg err" role="alert"><span aria-hidden="true">!</span><span><?= e($nl_error) ?></span></p>
          <?php endif; ?>
          <form class="nl-form" method="post" action="/blogs#subscribe" novalidate>
            <input type="hidden" name="form_type" value="newsletter" />
            <input type="hidden" name="nl_source" value="blogs-hero" />
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>" />
            <!-- Honeypot: a real reader never sees this, a bot fills it in. -->
            <span class="hp" aria-hidden="true">
              <label for="company_url">Company URL</label>
              <input type="text" id="company_url" name="company_url" tabindex="-1" autocomplete="off" />
            </span>
            <label class="sr-only" for="nl_email">Your email address</label>
            <div class="nl-shell">
              <input type="email" id="nl_email" name="nl_email" placeholder="Enter your email"
                     autocomplete="email" required value="<?= e($nl_email) ?>" />
              <button type="submit" class="btn btn-accent">Get updates</button>
            </div>
          </form>
          <p class="nl-note">No spam, ever. We care about your data.</p>
        <?php endif; ?>
      </div>

      <!-- Decorative: four tiles, arched corners running clockwise. Boxes and
           radii only, so it costs nothing over the wire and stays sharp. -->
      <div class="nl-art" aria-hidden="true">
        <div class="nl-tile t1">
          <span class="k">Guides live</span>
          <span class="v"><?= e(str_pad((string) $live_count, 2, '0', STR_PAD_LEFT)) ?></span>
        </div>
        <div class="nl-tile t2">
          <span class="k">Topics</span>
          <span class="v"><?= e(str_pad((string) count($categories), 2, '0', STR_PAD_LEFT)) ?></span>
        </div>
        <div class="nl-tile t3">
          <span class="k">We write about</span>
          <ul class="nl-topics">
            <li>Shopify B2B</li>
            <li>Wholesale pricing</li>
            <li>Quote flows</li>
          </ul>
        </div>
        <div class="nl-tile t4">
          <span class="k">New guides</span>
          <span class="v sm">Monthly</span>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============== LISTING ============== -->
<section class="blog-list" aria-labelledby="all-articles">
  <div class="wrap">
    <h2 class="sr-only" id="all-articles">All articles</h2>

    <!-- Tabs and search are progressive enhancement: with JS off every post
         stays visible and the controls simply do nothing. -->
    <div class="blog-toolbar">
      <div class="blog-tabs" role="group" aria-label="Filter posts by topic">
        <button type="button" class="blog-tab" data-filter="all" aria-pressed="true">
          All <span class="n"><?= e((string) count($all_posts)) ?></span>
        </button>
        <?php foreach ($categories as $key => $label): ?>
        <button type="button" class="blog-tab" data-filter="<?= e($key) ?>" aria-pressed="false">
          <?= e($label) ?> <span class="n"><?= e((string) count(dq_posts($key))) ?></span>
        </button>
        <?php endforeach; ?>
      </div>

      <div class="blog-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
          <circle cx="11" cy="11" r="7" /><path d="M20 20l-3.5-3.5" stroke-linecap="round" />
        </svg>
        <label class="sr-only" for="blog-q">Search posts by title</label>
        <input type="search" id="blog-q" placeholder="Search articles…" autocomplete="off" />
      </div>
    </div>

    <!-- One grid, newest first. The tabs filter it in place rather than
         scrolling to a section, so the reader never loses their position. -->
    <div class="blog blog-ed">
      <?php foreach ($all_posts as $p) { echo dq_post_card($p, ['layout' => 'editorial']); } ?>
    </div>

    <p class="blog-empty" id="blog-empty" hidden>
      No articles match that search yet. Try a different word, or
      <a href="#contact">ask us directly</a>.
    </p>
    <p class="sr-only" role="status" aria-live="polite" id="blog-status"></p>

  </div>
</section>

<?php
  $contact_section_style = 'padding-top: 0px';
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
          <li><a href="/dq-b2b-request-quote-rfq">B2B Request a Quote (RFQ)</a></li>
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
  var tabs   = Array.prototype.slice.call(document.querySelectorAll('.blog-tab'));
  var cards  = Array.prototype.slice.call(document.querySelectorAll('.post[data-title]'));
  var input  = document.getElementById('blog-q');
  var empty  = document.getElementById('blog-empty');
  var status = document.getElementById('blog-status');
  if (!tabs.length || !input) { return; }

  var filter = 'all';

  function apply() {
    var q = input.value.trim().toLowerCase();
    var shown = 0;

    cards.forEach(function (card) {
      var okCat  = filter === 'all' || card.getAttribute('data-cat') === filter;
      var okText = q === '' || card.getAttribute('data-title').indexOf(q) !== -1;
      var show   = okCat && okText;
      card.hidden = !show;
      if (show) { shown++; }
    });

    empty.hidden = shown !== 0;
    status.textContent = shown === 1 ? '1 article' : shown + ' articles';
  }

  tabs.forEach(function (tab) {
    tab.addEventListener('click', function () {
      filter = tab.getAttribute('data-filter');
      tabs.forEach(function (t) {
        t.setAttribute('aria-pressed', String(t === tab));
      });
      apply();
    });
  });

  var t;
  input.addEventListener('input', function () {
    clearTimeout(t);
    t = setTimeout(apply, 120);
  });
  // Escape clears the field, the behaviour type="search" already implies.
  input.addEventListener('keydown', function (ev) {
    if (ev.key === 'Escape') { input.value = ''; apply(); }
  });

  // Deep link: /blogs#cat-marketing opens with that tab already active.
  var hash = (location.hash || '').replace('#cat-', '');
  if (hash && hash !== location.hash) {
    var match = tabs.filter(function (t2) { return t2.getAttribute('data-filter') === hash; })[0];
    if (match) { match.click(); }
  }
})();
</script>

</body>
</html>
