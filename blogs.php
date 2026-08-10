<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — blog index
 * Every post, grouped into a section per category, with client
 * side category tabs and a title search. All post data comes
 * from inc/posts.php.
 * ============================================================ */

require __DIR__ . '/inc/lib.php';
require __DIR__ . '/inc/posts.php';

$CONFIG['page_title']       = 'Blog — Shopify B2B, wholesale and app guides · Deque Lab';
$CONFIG['page_description'] = 'Practical guides on selling B2B and wholesale on Shopify: what the native features cover, which apps are worth paying for, and how to build a quote flow that converts.';
$CONFIG['canonical_url']    = $CONFIG['site_url'] . '/blogs';
$CONFIG['og_image']         = $CONFIG['site_url'] . '/assets/og-shopify-b2b-basic.png';

// The contact block at the foot of the page.
$contact_default_interest = 'Just saying hi';
$contact_eyebrow = 'Working on something similar?';
$contact_heading = 'Got a question<br>we have not <em>written</em> up?';
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

    .blog-hero { padding: 64px 0 40px; }
    .blog-hero h1 {
      font-size: clamp(38px, 5.6vw, 68px); line-height: 1.04;
      letter-spacing: -0.035em; font-weight: 500; margin: 18px 0 0;
    }
    .blog-hero .lede { margin-top: 20px; font-size: clamp(17px, 1.5vw, 21px); }
    .blog-hero .counts {
      margin-top: 26px; display: flex; gap: 22px; flex-wrap: wrap;
      font-family: var(--f-mono); font-size: 12.5px; color: var(--muted);
    }
    .blog-hero .counts b { color: var(--ink); font-weight: 600; }
    /* The listing owns the page, so it starts tight under the toolbar. */
    .blog-list { padding-top: 0; }
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

<!-- ============== HERO ============== -->
<section class="blog-hero" aria-labelledby="blog-title">
  <div class="wrap">
    <div class="eyebrow">Deque Lab journal</div>
    <h1 id="blog-title">Blog Articles</h1>
    <p class="lede">Stay updated with our latest insights and stories — what we learn building
      Shopify apps and SaaS, written up so you do not have to learn it the expensive way.</p>
    <div class="counts">
      <span><b><?= e((string) $live_count) ?></b> published</span>
      <span><b><?= e((string) count($categories)) ?></b> topics</span>
      <span>New guides monthly</span>
    </div>
  </div>
</section>

<!-- ============== LISTING ============== -->
<section class="blog-list" aria-labelledby="blog-title">
  <div class="wrap">

    <!-- Tabs and search are progressive enhancement: with JS off every post
         stays visible and the controls simply do nothing. -->
    <div class="blog-toolbar">
      <div class="blog-tabs" role="group" aria-label="Filter posts by topic">
        <button type="button" class="blog-tab" data-filter="all" aria-pressed="true">
          All Stories <span class="n"><?= e((string) count($all_posts)) ?></span>
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

    <?php foreach ($categories as $key => $label): $posts = dq_posts($key); ?>
    <div class="blog-section" data-section="<?= e($key) ?>" id="cat-<?= e($key) ?>">
      <h2><?= e($label) ?> <span class="n"><?= e((string) count($posts)) ?></span></h2>
      <div class="blog">
        <?php foreach ($posts as $p) { echo dq_post_card($p); } ?>
      </div>
    </div>
    <?php endforeach; ?>

    <p class="blog-empty" id="blog-empty" hidden>
      No articles match that search yet. Try a different word, or
      <a href="#contact">ask us directly</a>.
    </p>
    <p class="sr-only" role="status" aria-live="polite" id="blog-status"></p>

  </div>
</section>

<?php
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
  var tabs     = Array.prototype.slice.call(document.querySelectorAll('.blog-tab'));
  var sections = Array.prototype.slice.call(document.querySelectorAll('.blog-section'));
  var cards    = Array.prototype.slice.call(document.querySelectorAll('.post[data-title]'));
  var input    = document.getElementById('blog-q');
  var empty    = document.getElementById('blog-empty');
  var status   = document.getElementById('blog-status');
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

    // A section with nothing left in it is noise — hide the heading too.
    sections.forEach(function (sec) {
      var any = Array.prototype.slice.call(sec.querySelectorAll('.post[data-title]'))
        .some(function (c) { return !c.hidden; });
      sec.hidden = !any;
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

  // Deep link: /blogs#cat-guides opens with that tab already active.
  var hash = (location.hash || '').replace('#cat-', '');
  if (hash) {
    var match = tabs.filter(function (t2) { return t2.getAttribute('data-filter') === hash; })[0];
    if (match) { match.click(); }
  }
})();
</script>

</body>
</html>
