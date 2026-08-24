<?php
/* ============================================================
 * Deque Lab — analytics
 * Included on every page, immediately before </head>.
 *
 * One file so a tag can never end up on some pages and not
 * others. The previous setup pasted the Ahrefs script straight
 * into index.php, so the homepage was measured and the blog,
 * the articles and the app page were not.
 *
 * TO ACTIVATE: paste the GA4 Measurement ID below. Until that
 * is a real ID, nothing renders — a placeholder tag would send
 * junk to a property that does not exist, and failing silently
 * is worse than not loading at all.
 * Find it in GA4 → Admin → Data streams → your web stream.
 * ============================================================ */

$GA_MEASUREMENT_ID = 'G-RS9B34G5GY';   // <-- replace with your real ID

// Bail out while the placeholder is still in place. The literal is compared
// directly rather than only shape-checked: 'G-XXXXXXXXXX' is ten valid [A-Z]
// characters, so a pattern test on its own happily accepts it and ships a tag
// pointing at a property that does not exist. No const, because this file is
// required on every page and a constant would fatal on a second include.
if ($GA_MEASUREMENT_ID === 'G-XXXXXXXXXX'
    || !preg_match('/^G-[A-Z0-9]{6,}$/', $GA_MEASUREMENT_ID)) {
    return;
}
?>
<?php /* async so it never blocks rendering. In <head> rather than deferred like
   Crisp: a deferred tag misses readers who leave before first interaction,
   which is the group a bounce metric exists to capture. */ ?>
<link rel="dns-prefetch" href="https://www.googletagmanager.com" />
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= rawurlencode($GA_MEASUREMENT_ID) ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '<?= htmlspecialchars($GA_MEASUREMENT_ID, ENT_QUOTES, 'UTF-8') ?>');
</script>
