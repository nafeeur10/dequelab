<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — shared library
 * Helpers, request/environment resolution, session + CSRF,
 * base config, and contact-form option lists.
 * Included at the top of every page. Idempotent.
 * ============================================================ */

if (defined('DQLAB_LIB')) {
    return;
}
define('DQLAB_LIB', true);

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

// ---------- Request / environment ----------
$scheme = (
    (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
    || (($_SERVER['SERVER_PORT'] ?? '') == 443)
) ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'dequelab.com';
$path = strtok($_SERVER['REQUEST_URI'] ?? '/', '?');

// ---------- Base config (pages may extend / override) ----------
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
$CONFIG['site_url']  = $scheme . '://' . $host;
$CONFIG['logo']      = '/assets/dqlab-small.png';   // square mark — hero, favicon, OG, schema
$CONFIG['logo_full'] = '/assets/dqlab.png';         // wordmark — header + footer
$CONFIG['og_image']  = $CONFIG['site_url'] . $CONFIG['logo'];
// Canonical defaults to the current path; pages with clean URLs override this.
$CONFIG['canonical_url'] = $CONFIG['site_url'] . $path;

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
