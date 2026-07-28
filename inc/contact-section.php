<?php
/* ============================================================
 * Deque Lab — contact section markup
 * Requires inc/lib.php + inc/contact-process.php to have run.
 * Optional vars (set before include to customize copy):
 *   $contact_eyebrow, $contact_heading (trusted HTML),
 *   $contact_lede, $contact_default_interest
 * ============================================================ */

$contact_eyebrow ??= 'Stay in the loop';
$contact_heading ??= 'Want early access<br>or a <em>question</em>?';
$contact_lede    ??= 'Drop us a line. We reply to every message — usually within a business day. Tell us which product, and we\'ll add you to that waitlist or get on a call.';
$contact_default_interest ??= '';
$contact_show_socials ??= true;   // set false to move social icons elsewhere (e.g. footer)
$contact_section_style ??= 'padding-top:0';    // e.g. 'padding-top:0' to tighten the gap above

// Preselect: sticky form value wins; otherwise the page default.
$contact_selected = $form_data['interest'] !== '' ? $form_data['interest'] : $contact_default_interest;
?>
<section id="contact" aria-labelledby="contact-title"<?= $contact_section_style ? ' style="' . e($contact_section_style) . '"' : '' ?>>
  <div class="wrap">
    <div class="sec-head">
      <div>
        <div class="eyebrow"><?= e($contact_eyebrow) ?></div>
        <h2 id="contact-title" style="margin-top: 14px">
          <?= $contact_heading /* trusted markup */ ?>
        </h2>
      </div>
    </div>

    <div class="contact-wrap">
      <div>
        <div class="founder-card">
          <span class="founder-arrow" aria-hidden="true">
            <svg viewBox="0 0 66 62" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M62 5C58 26 45 37 21 38" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
              <path d="M21 38l13-7M21 38l8 12" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </span>
          <img class="founder-avatar" src="/assets/10268310.png" width="120" height="120"
               alt="Nafeeur Rahman" loading="lazy" decoding="async" />
          <div class="founder-name">Nafeeur Rahman</div>
          <div class="founder-role">Founder</div>
          <p class="founder-tagline">I usually reply within a business day.</p>
          <span class="founder-divider" aria-hidden="true"></span>
          <ul class="founder-contacts">
            <li>
              <a href="https://wa.me/8801339364047" target="_blank" rel="noopener noreferrer" aria-label="Chat with us on WhatsApp">
                <span class="fc-ic" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.9c0 2.1.55 4.06 1.6 5.82L2 22l4.42-1.16a9.9 9.9 0 004.62 1.18h.01c5.46 0 9.9-4.45 9.9-9.9 0-2.65-1.03-5.14-2.9-7.02A9.82 9.82 0 0012.04 2zm5.8 14.13c-.25.7-1.44 1.33-1.99 1.38-.53.05-1.02.24-3.43-.72-2.9-1.14-4.75-4.12-4.9-4.31-.14-.19-1.17-1.55-1.17-2.96 0-1.41.74-2.1 1-2.39.26-.29.57-.36.76-.36l.55.01c.18.01.42-.07.65.5.25.6.85 2.08.92 2.23.07.15.12.33.02.52-.09.19-.14.31-.28.48-.14.17-.29.38-.42.5-.14.14-.28.29-.12.57.16.28.72 1.19 1.55 1.93 1.07.95 1.97 1.25 2.25 1.39.28.14.44.12.6-.07.16-.19.69-.8.87-1.08.18-.28.37-.23.62-.14.25.09 1.6.75 1.87.89.28.14.46.21.53.33.07.12.07.68-.18 1.38z"/></svg>
                </span>
                +880 1339 364047
              </a>
            </li>
            <li>
              <a href="mailto:nafeeur@dequelab.com">
                <span class="fc-ic" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="5" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.8"/><path d="M4 7.5l8 5.5 8-5.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                </span>
                nafeeur@dequelab.com
              </a>
            </li>
          </ul>
        </div>
      </div>

      <div class="form" id="briefForm">
        <?php if ($form_sent): ?>
          <div class="form-success" role="status" aria-live="polite">
            <div class="tick" aria-hidden="true">✓</div>
            <h4>Thanks — got it.</h4>
            <p>We'll reply within a business day at the email you provided.</p>
          </div>
        <?php else: ?>
          <?php if ($form_errors): ?>
            <div class="form-errors" role="alert">
              <ul>
                <?php foreach ($form_errors as $err): ?>
                  <li><?= e($err) ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endif; ?>

          <form method="post" action="<?= e($path) ?>#contact" novalidate>
            <input type="hidden" name="form_type" value="contact" />
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>" />
            <!-- Honeypot: must remain empty -->
            <div class="hp" aria-hidden="true">
              <label for="website">Website (leave blank)</label>
              <input type="text" name="website" id="website" tabindex="-1" autocomplete="off" />
            </div>

            <div class="form-row">
              <div class="grid2">
                <div class="f">
                  <label for="name">Name <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="text" name="name" id="name" placeholder="Jane Rivera" required maxlength="120"
                         autocomplete="name" value="<?= e($form_data['name']) ?>" />
                </div>
                <div class="f">
                  <label for="email">Email <span class="req" aria-hidden="true">*</span><span class="sr-only">required</span></label>
                  <input type="email" name="email" id="email" placeholder="jane@company.com" required maxlength="180"
                         autocomplete="email" value="<?= e($form_data['email']) ?>" />
                </div>
              </div>
              <div class="f">
                <label for="interest">I'm interested in</label>
                <select name="interest" id="interest">
                  <option value="">— Select a product —</option>
                  <optgroup label="SaaS">
                    <?php foreach ($INTEREST_SAAS as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $contact_selected === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <optgroup label="Shopify Apps">
                    <?php foreach ($INTEREST_SHOPIFY as $opt): ?>
                      <option value="<?= e($opt) ?>" <?= $contact_selected === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
                    <?php endforeach; ?>
                  </optgroup>
                  <option value="Just saying hi" <?= $contact_selected === 'Just saying hi' ? 'selected' : '' ?>>Just saying hi</option>
                </select>
              </div>
              <div class="f">
                <label for="msg">Message</label>
                <textarea name="msg" id="msg" maxlength="5000" placeholder="Anything you'd like to share — questions, feedback, or what you're hoping the product does."><?= e($form_data['message']) ?></textarea>
              </div>
            </div>

            <div class="submit">
              <button type="submit" class="btn btn-accent btn-arrow">Send message</button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
