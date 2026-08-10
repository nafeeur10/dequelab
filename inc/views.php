<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — flat-file page view counter
 * No database: counts live in data/views.json, guarded by flock.
 * Requires inc/lib.php to have run (it starts the session).
 * Idempotent.
 * ============================================================ */

if (defined('DQLAB_VIEWS')) {
    return;
}
define('DQLAB_VIEWS', true);

const DQ_VIEWS_FILE   = __DIR__ . '/../data/views.json';
const DQ_VIEWS_WINDOW = 43200; // 12h — one visitor counts once per window

/**
 * Record a view for $slug and return the running total.
 *
 * Deliberately total-failure-tolerant: a read-only filesystem, a corrupt
 * JSON file, or a disabled session must degrade to "no counter" rather than
 * take the article down. Callers should hide the counter when this returns 0.
 */
function dq_views_record(string $slug): int {
    $slug = preg_replace('~[^a-z0-9_-]~', '', strtolower($slug)) ?? '';
    if ($slug === '') {
        return 0;
    }

    // Crawlers and previewers read the page but are not readers.
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $is_bot = $ua === '' || preg_match('~bot|crawl|spider|slurp|preview|monitor|headless|lighthouse~i', $ua) === 1;

    // Same visitor, same article, within the window — read the total, don't add to it.
    $seen_at   = $_SESSION['dq_viewed'][$slug] ?? 0;
    $is_repeat = is_int($seen_at) && (time() - $seen_at) < DQ_VIEWS_WINDOW;

    $increment = !$is_bot && !$is_repeat;

    try {
        if (!is_dir(dirname(DQ_VIEWS_FILE)) && !@mkdir(dirname(DQ_VIEWS_FILE), 0775, true)) {
            return 0;
        }

        $fh = @fopen(DQ_VIEWS_FILE, 'c+');
        if ($fh === false) {
            return 0;
        }

        // Shared lock is enough for a pure read; only writers need exclusivity.
        if (!flock($fh, $increment ? LOCK_EX : LOCK_SH)) {
            fclose($fh);
            return 0;
        }

        $raw    = stream_get_contents($fh);
        $counts = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
        if (!is_array($counts)) {
            $counts = []; // corrupt file — start over rather than fail
        }

        $count = isset($counts[$slug]) && is_int($counts[$slug]) ? $counts[$slug] : 0;

        if ($increment) {
            $count++;
            $counts[$slug] = $count;
            $json = json_encode($counts, JSON_UNESCAPED_SLASHES);
            if ($json !== false) {
                rewind($fh);
                ftruncate($fh, 0);
                fwrite($fh, $json);
                fflush($fh);
                $_SESSION['dq_viewed'][$slug] = time();
            }
        }

        flock($fh, LOCK_UN);
        fclose($fh);

        return $count;
    } catch (\Throwable $ex) {
        return 0;
    }
}
