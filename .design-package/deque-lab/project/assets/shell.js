// Deque Lab — shared shell: nav, footer, tweaks panel
(function () {
  const NAV = [
    { href: "#saas", label: "SaaS" },
    { href: "#apps", label: "Shopify Apps" },
    { href: "#contact", label: "Contact" },
  ];

  function mountNav() {
    const host = document.querySelector("[data-nav]");
    if (!host) return;
    const links = NAV.map(p => `<a href="${p.href}">${p.label}</a>`).join("");
    host.innerHTML = `
      <nav class="nav">
        <div class="wrap nav-inner">
          <a href="#top" class="brand" aria-label="Deque Lab home">
            <span class="brand-mark"><img src="assets/logo-mark.png" alt="Deque Lab"/></span>
          </a>
          <div class="nav-links">
            ${links}
            <a href="#contact" class="btn btn-primary btn-sm nav-cta">Get notified →</a>
          </div>
        </div>
      </nav>`;
  }

  function mountFooter() {
    const host = document.querySelector("[data-footer]");
    if (!host) return;
    host.innerHTML = `
      <footer class="site-footer">
        <div class="wrap">
          <div class="footer-grid">
            <div class="footer-col">
              <a href="#top" class="brand">
                <span class="brand-mark"><img src="assets/logo-mark.png" alt="Deque Lab"/></span>
              </a>
              <p style="color:#8a8d85; font-size:14px; margin-top:18px; max-width: 34ch;">
                A small product lab. We build SaaS and Shopify apps for teams that care about craft.
              </p>
            </div>
            <div class="footer-col">
              <h5>SaaS</h5>
              <ul>
                <li><a href="#saas-alliances">Alliances PRO</a></li>
                <li><a href="#saas-planner">Muslim Daily Planner</a></li>
                <li><a href="#saas">All SaaS products</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h5>Shopify Apps</h5>
              <ul>
                <li><a href="#app-appointment">Appointment Booking</a></li>
                <li><a href="#app-preorder">PreOrder (acquired)</a></li>
                <li><a href="#apps">All Shopify apps</a></li>
              </ul>
            </div>
            <div class="footer-col">
              <h5>Connect</h5>
              <ul>
                <li><a href="mailto:contact@dequelab.com">contact@dequelab.com</a></li>
                <li><a href="https://linkedin.com/company/deque-lab" target="_blank" rel="noopener">LinkedIn</a></li>
                <li><a href="https://x.com/dequelab" target="_blank" rel="noopener">X / Twitter</a></li>
              </ul>
            </div>
          </div>
          <div class="footer-meta">
            <span>© 2026 Deque Lab</span>
            <span>Built in-house · v1.0</span>
          </div>
        </div>
      </footer>`;
  }

  // ---------- Tweaks (accent color) ----------
  const ACCENTS = /*EDITMODE-BEGIN*/{
    "accent": "#4f8c3a"
  }/*EDITMODE-END*/;

  const PRESETS = [
    { name: "Forest",   color: "#4f8c3a" },
    { name: "Moss",     color: "#6ca544" },
    { name: "Olive",    color: "#8a9a2f" },
    { name: "Pine",     color: "#2f6b3a" },
    { name: "Emerald",  color: "#2f8a5c" },
    { name: "Cobalt",   color: "#2f5fba" },
    { name: "Rust",     color: "#c2552a" },
    { name: "Ink",      color: "#0e1210" },
  ];

  function applyAccent(hex) {
    document.documentElement.style.setProperty("--accent", hex);
    const ink = contrastInk(hex);
    document.documentElement.style.setProperty("--accent-ink", ink);
  }
  function contrastInk(hex) {
    const c = hex.replace("#","");
    const r = parseInt(c.slice(0,2),16);
    const g = parseInt(c.slice(2,4),16);
    const b = parseInt(c.slice(4,6),16);
    const l = (0.299*r + 0.587*g + 0.114*b) / 255;
    return l > 0.6 ? "#0e1210" : "#ffffff";
  }

  const saved = localStorage.getItem("dq-accent");
  if (saved) applyAccent(saved);
  else applyAccent(ACCENTS.accent);

  function buildTweakPanel() {
    let panel = document.getElementById("dq-tweaks");
    if (panel) return panel;
    panel = document.createElement("div");
    panel.id = "dq-tweaks";
    panel.innerHTML = `
      <style>
        #dq-tweaks {
          position: fixed; right: 20px; bottom: 20px; z-index: 200;
          background: #fff; border: 1px solid var(--line);
          border-radius: 14px; padding: 16px; width: 240px;
          box-shadow: 0 20px 48px rgba(14,18,16,0.12);
          font-family: var(--f-sans); display: none;
        }
        #dq-tweaks.open { display: block; }
        #dq-tweaks h6 {
          font-family: var(--f-mono); font-size: 11px; letter-spacing: 0.14em;
          text-transform: uppercase; color: var(--muted); margin: 0 0 12px; font-weight: 500;
        }
        #dq-tweaks .swatches { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
        #dq-tweaks button.sw {
          aspect-ratio: 1; border-radius: 8px; border: 2px solid transparent;
          transition: transform 120ms ease;
        }
        #dq-tweaks button.sw:hover { transform: scale(1.05); }
        #dq-tweaks button.sw.active { border-color: var(--ink); }
        #dq-tweaks .custom {
          display: flex; align-items: center; gap: 10px; margin-top: 14px;
          font-size: 12px; color: var(--muted);
        }
        #dq-tweaks .custom input[type=color] {
          width: 28px; height: 28px; border: 1px solid var(--line); border-radius: 6px;
          padding: 0; background: none;
        }
        #dq-tweaks .name { font-family: var(--f-mono); font-size: 11px; color: var(--ink); }
      </style>
      <h6>Tweaks · Accent</h6>
      <div class="swatches"></div>
      <div class="custom">
        <input type="color" id="dq-custom" value="#4f8c3a" />
        <span>Custom</span>
        <span class="name" id="dq-accent-name" style="margin-left:auto"></span>
      </div>`;
    document.body.appendChild(panel);

    const sw = panel.querySelector(".swatches");
    PRESETS.forEach(p => {
      const b = document.createElement("button");
      b.className = "sw";
      b.style.background = p.color;
      b.title = p.name;
      b.dataset.color = p.color;
      b.addEventListener("click", () => {
        setAccent(p.color, p.name);
        markActive();
      });
      sw.appendChild(b);
    });
    const custom = panel.querySelector("#dq-custom");
    custom.addEventListener("input", (e) => setAccent(e.target.value, "Custom"));

    function setAccent(hex, name) {
      applyAccent(hex);
      localStorage.setItem("dq-accent", hex);
      panel.querySelector("#dq-accent-name").textContent = name || hex.toUpperCase();
      window.parent.postMessage({ type: "__edit_mode_set_keys", edits: { accent: hex } }, "*");
    }
    function markActive() {
      const cur = getComputedStyle(document.documentElement).getPropertyValue("--accent").trim();
      sw.querySelectorAll("button.sw").forEach(b => {
        b.classList.toggle("active", b.dataset.color.toLowerCase() === cur.toLowerCase());
      });
    }

    const cur = localStorage.getItem("dq-accent") || ACCENTS.accent;
    custom.value = cur;
    panel.querySelector("#dq-accent-name").textContent = cur.toUpperCase();
    markActive();

    return panel;
  }

  window.addEventListener("message", (e) => {
    const data = e.data;
    if (!data || typeof data !== "object") return;
    if (data.type === "__activate_edit_mode") {
      buildTweakPanel().classList.add("open");
    } else if (data.type === "__deactivate_edit_mode") {
      const p = document.getElementById("dq-tweaks");
      if (p) p.classList.remove("open");
    }
  });
  try { window.parent.postMessage({ type: "__edit_mode_available" }, "*"); } catch (_) {}

  if (document.readyState !== "loading") { mountNav(); mountFooter(); }
  else document.addEventListener("DOMContentLoaded", () => { mountNav(); mountFooter(); });
})();
