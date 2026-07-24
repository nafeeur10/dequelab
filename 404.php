<?php http_response_code(404); ?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="robots" content="noindex, follow" />
  <title>404 — Page not found · Deque Lab</title>
  <meta name="description" content="The page you were looking for doesn't exist. Head back to Deque Lab." />
  <link rel="icon" type="image/png" href="/assets/logo-mark.png" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Geist:wght@300;400;500;600;700&family=Geist+Mono:wght@400;500&display=swap" />
  <link rel="stylesheet" href="/assets/styles.css" />
  <style>
    .err-wrap {
      min-height: 80vh;
      display: grid; place-items: center;
      padding: 80px 20px;
      text-align: center;
    }
    .err-code {
      font-family: var(--f-mono); font-size: clamp(80px, 14vw, 180px);
      letter-spacing: -0.04em; line-height: 1; color: var(--ink);
    }
    .err-code .x { color: var(--accent); }
    .err-msg {
      margin: 20px 0 8px;
      font-size: clamp(22px, 3vw, 32px); font-weight: 500; letter-spacing: -0.02em;
    }
    .err-sub { color: var(--muted); max-width: 44ch; margin: 0 auto 28px; }
  </style>
</head>
<body>
  <main class="wrap">
    <div class="err-wrap">
      <div>
        <div class="err-code">4<span class="x">0</span>4</div>
        <div class="err-msg">Page not found</div>
        <p class="err-sub">The link you followed may be broken, or the page may have moved. No worries — head back to the lab.</p>
        <a href="/" class="btn btn-accent btn-arrow">Back to Deque Lab</a>
      </div>
    </div>
  </main>
</body>
</html>
