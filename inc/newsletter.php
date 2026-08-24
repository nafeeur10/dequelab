<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — newsletter signup
 * Store + form processor for the /blogs subscribe box.
 *
 * The site has no database and no DB credentials anywhere in the
 * tree, so subscribers live in data/subscribers.json under the
 * same flock discipline as data/views.json rather than in a table
 * that nothing on this host could connect to. Everything that
 * touches storage goes through dq_newsletter_store() and
 * dq_newsletter_count() — swapping the two bodies for INSERT and
 * SELECT COUNT(*) is the whole migration if a database ever
 * shows up.
 *
 * Requires inc/lib.php to have run ($CONFIG, $csrf_token, $host,
 * $path, e(), and the session). Populates:
 *   $nl_error (string), $nl_sent (bool), $nl_email (string)
 * Idempotent.
 * ============================================================ */

if (defined('DQLAB_NEWSLETTER')) {
    return;
}
define('DQLAB_NEWSLETTER', true);

const DQ_SUBSCRIBERS_FILE = __DIR__ . '/../data/subscribers.json';

/**
 * Record a subscriber.
 *
 * Keyed by the lowercased address, so re-subscribing updates the row instead
 * of adding a second one — the caller can then thank a returning reader
 * without leaking whether the address was already on the list.
 *
 * @return string 'new' | 'existing' | 'error'
 */
function dq_newsletter_store(string $email, string $source = 'blogs'): string {
    $key = strtolower(trim($email));
    if ($key === '') {
        return 'error';
    }

    try {
        $dir = dirname(DQ_SUBSCRIBERS_FILE);
        if (!is_dir($dir) && !@mkdir($dir, 0775, true)) {
            return 'error';
        }

        $fh = @fopen(DQ_SUBSCRIBERS_FILE, 'c+');
        if ($fh === false || !flock($fh, LOCK_EX)) {
            if ($fh !== false) {
                fclose($fh);
            }
            return 'error';
        }

        $raw  = stream_get_contents($fh);
        $rows = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
        if (!is_array($rows)) {
            // A truncated write must not cost us the whole list: keep the bad
            // file beside the new one rather than overwriting it in place.
            @copy(DQ_SUBSCRIBERS_FILE, DQ_SUBSCRIBERS_FILE . '.corrupt-' . gmdate('Ymd-His'));
            $rows = [];
        }

        $existing = isset($rows[$key]) && is_array($rows[$key]);
        $now      = gmdate('c');

        $rows[$key] = [
            'email'          => $key,
            'subscribed_at'  => $existing ? ($rows[$key]['subscribed_at'] ?? $now) : $now,
            'last_seen_at'   => $now,
            'source'         => $source,
            'ip'             => (string) ($_SERVER['REMOTE_ADDR'] ?? ''),
            'user_agent'     => substr((string) ($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 200),
            'confirmed'      => $existing ? (bool) ($rows[$key]['confirmed'] ?? false) : false,
        ];

        $json = json_encode($rows, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        $ok   = false;
        if ($json !== false) {
            rewind($fh);
            ftruncate($fh, 0);
            $ok = fwrite($fh, $json) !== false;
            fflush($fh);
        }

        flock($fh, LOCK_UN);
        fclose($fh);

        if (!$ok) {
            return 'error';
        }
        return $existing ? 'existing' : 'new';
    } catch (\Throwable $ex) {
        return 'error';
    }
}

/**
 * How many people are on the list. Used for the "N readers" line in the hero,
 * which is hidden when this returns 0 — an empty list should say nothing
 * rather than advertise itself.
 */
function dq_newsletter_count(): int {
    try {
        if (!is_file(DQ_SUBSCRIBERS_FILE)) {
            return 0;
        }
        $raw  = @file_get_contents(DQ_SUBSCRIBERS_FILE);
        $rows = is_string($raw) && $raw !== '' ? json_decode($raw, true) : [];
        return is_array($rows) ? count($rows) : 0;
    } catch (\Throwable $ex) {
        return 0;
    }
}

/* ------------------------------------------------------------
 * Form processor
 * ---------------------------------------------------------- */

$nl_error = '';
$nl_sent  = false;
$nl_email = '';

// Flash success from the PRG redirect below.
if (!empty($_SESSION['nl_sent'])) {
    $nl_sent = true;
    unset($_SESSION['nl_sent']);
}

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['form_type'] ?? '') === 'newsletter') {

    $redirect = static function () use ($path): void {
        $_SESSION['nl_sent'] = true;
        header('Location: ' . $path . '#subscribe', true, 303);
        exit;
    };

    // Honeypot — bots fill the hidden field. Look successful, store nothing.
    if (!empty($_POST['company_url'])) {
        $redirect();
    }

    $nl_email = trim((string) ($_POST['nl_email'] ?? ''));

    if (!hash_equals($csrf_token, (string) ($_POST['csrf_token'] ?? ''))) {
        $nl_error = 'Your session expired. Please refresh and try again.';
    } else {
        // Rate limit: 5 attempts per session per hour. A signup box is a cheap
        // target for someone walking a list of addresses through it.
        $now = time();
        $_SESSION['nl_log'] = array_values(array_filter(
            (array) ($_SESSION['nl_log'] ?? []),
            static fn($t) => (int) $t > $now - 3600
        ));
        if (count($_SESSION['nl_log']) >= 5) {
            $nl_error = 'Too many attempts. Please try again in an hour.';
        } elseif ($nl_email === '' || mb_strlen($nl_email) > 190 || !filter_var($nl_email, FILTER_VALIDATE_EMAIL)) {
            $nl_error = 'Please enter a valid email address.';
        } elseif (preg_match('/[\r\n]/', $nl_email)) {
            $nl_error = 'Invalid characters in email address.';
        } else {
            $result = dq_newsletter_store($nl_email, (string) ($_POST['nl_source'] ?? 'blogs'));

            if ($result === 'error') {
                $nl_error = 'Sorry, we could not save that just now. Please email us at ' . $CONFIG['contact_email'] . '.';
            } else {
                $_SESSION['nl_log'][] = $now;

                // Notify, but never let a mail failure fail the signup — the
                // address is already stored, which is the part that matters.
                if ($result === 'new') {
                    $domain  = preg_replace('/^www\./', '', $host);
                    $from    = 'noreply@' . $domain;
                    $body    = "New newsletter subscriber\n"
                             . str_repeat('-', 40) . "\n"
                             . "Email:     " . $nl_email . "\n"
                             . "Source:    " . $CONFIG['site_url'] . $path . "\n"
                             . "Total:     " . dq_newsletter_count() . "\n"
                             . "Submitted: " . gmdate('Y-m-d H:i:s') . " UTC\n";
                    $headers = "From: Deque Lab Website <{$from}>\r\n"
                             . "Reply-To: " . $nl_email . "\r\n"
                             . "MIME-Version: 1.0\r\n"
                             . "Content-Type: text/plain; charset=UTF-8\r\n";
                    @mail($CONFIG['contact_email'], '[Deque Lab] New subscriber', $body, $headers, '-f' . $from);
                }

                $redirect();
            }
        }
    }
}
