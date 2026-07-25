<?php
declare(strict_types=1);

/* ============================================================
 * Deque Lab — contact form processor
 * Requires inc/lib.php to have run first ($CONFIG, $csrf_token,
 * $host, $path, $INTEREST_ALL). Populates:
 *   $form_errors[], $form_data[], $form_sent (bool)
 * Post/Redirect/Get back to $path#contact on success.
 * ============================================================ */

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
        $body .= "Page:        " . $CONFIG['site_url'] . $path . "\n";
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
