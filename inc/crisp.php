<?php /* Crisp live-chat widget — included on every page (before </head>). */ ?>
<!-- Deferred loader. l.js pulls ~300 KB and opens a websocket; loading it during
     page load competed with the article for bandwidth and main thread. The
     queue array and website ID are still defined synchronously, so anything
     that pushes to $crisp early (the .chat-open buttons) keeps working — the
     commands sit in the queue and replay once l.js arrives.
     Load triggers: first real interaction, or browser idle as a safety net so
     a passive reader still gets the widget. -->
<link rel="dns-prefetch" href="https://client.crisp.chat" />
<script type="text/javascript">
window.$crisp = [];
window.CRISP_WEBSITE_ID = "021527cd-3645-4767-8a46-5cb1917b41fd";
(function () {
  var done = false;
  var events = ['pointerdown', 'keydown', 'touchstart'];

  function load() {
    if (done) { return; }
    done = true;
    events.forEach(function (ev) { window.removeEventListener(ev, load); });
    var d = document, s = d.createElement('script');
    s.src = 'https://client.crisp.chat/l.js';
    s.async = 1;
    d.getElementsByTagName('head')[0].appendChild(s);
  }

  events.forEach(function (ev) {
    window.addEventListener(ev, load, { passive: true, once: true });
  });

  // Idle fallback: after the page has settled, or 8s at the latest.
  if ('requestIdleCallback' in window) {
    requestIdleCallback(load, { timeout: 8000 });
  } else {
    window.addEventListener('load', function () { setTimeout(load, 3000); });
  }
})();
</script>
